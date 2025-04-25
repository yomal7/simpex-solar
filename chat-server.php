<?php
// chat-server.php - Run with: php chat-server.php

// Require Composer autoload file - make sure you've installed Ratchet via Composer
// composer require cboden/ratchet

require __DIR__ . '/vendor/autoload.php';

use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

/**
 * Chat WebSocket Server
 * Handles real-time communication between clients and coordinators
 */
class ChatServer implements MessageComponentInterface {
    protected $clients;      // Connected clients
    protected $userMapping;  // Maps user IDs to connection objects

    public function __construct() {
        // Initialize storage
        $this->clients = new \SplObjectStorage;
        $this->userMapping = [];
        
        echo "Chat server started at " . date('Y-m-d H:i:s') . "\n";
        echo "Listening for WebSocket connections...\n";
    }

    /**
     * When a client connects
     */
    public function onOpen(ConnectionInterface $conn) {
        // Store the new connection
        $this->clients->attach($conn);
        
        echo "New connection: {$conn->resourceId}\n";
    }

    /**
     * When a client sends a message
     */
    public function onMessage(ConnectionInterface $from, $msg) {
        // Decode the JSON message
        $data = json_decode($msg, true);
        
        if (!$data || !isset($data['type'])) {
            echo "Received invalid message format\n";
            return;
        }
        
        echo "Received {$data['type']} message from connection {$from->resourceId}\n";
        
        // Handle different message types
        switch ($data['type']) {
            case 'register':
                // User is registering their connection with their user ID
                if (isset($data['user_id']) && isset($data['user_role'])) {
                    $userId = $data['user_id'];
                    $userRole = $data['user_role'];
                    
                    // Store the user ID and role on the connection object
                    $from->userId = $userId;
                    $from->userRole = $userRole;
                    
                    // Map the user ID to this connection
                    $this->userMapping[$userId] = $from;
                    
                    echo "User {$userId} ({$userRole}) registered with connection {$from->resourceId}\n";
                }
                break;
                
            case 'message':
                // User is sending a message to another user
                if (isset($data['to_user_id'])) {
                    $receiverId = $data['to_user_id'];
                    
                    // Check if the recipient is connected
                    if (isset($this->userMapping[$receiverId])) {
                        $recipient = $this->userMapping[$receiverId];
                        
                        // Add current timestamp
                        $data['timestamp'] = date('Y-m-d H:i:s');
                        
                        // Forward the message to the recipient
                        $recipient->send(json_encode($data));
                        
                        echo "Message forwarded to user {$receiverId}\n";
                    } else {
                        echo "User {$receiverId} is not connected, message will be delivered when they connect\n";
                    }
                }
                break;
                
            default:
                echo "Unknown message type: {$data['type']}\n";
                break;
        }
    }

    /**
     * When a client disconnects
     */
    public function onClose(ConnectionInterface $conn) {
        // Remove the connection
        $this->clients->detach($conn);
        
        // Remove from user mapping if this was a registered user
        if (isset($conn->userId)) {
            unset($this->userMapping[$conn->userId]);
            echo "User {$conn->userId} disconnected (connection {$conn->resourceId})\n";
        } else {
            echo "Connection {$conn->resourceId} disconnected\n";
        }
    }

    /**
     * When an error occurs
     */
    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "Error: {$e->getMessage()}\n";
        
        // Close the connection
        $conn->close();
    }
}

// Create the server
$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new ChatServer()
        )
    ),
    8080  // WebSocket server port
);

echo "WebSocket server running on port 8080\n";
echo "Press Ctrl+C to stop\n";

// Run the server
$server->run();