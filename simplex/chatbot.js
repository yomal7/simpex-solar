const chatContainer = document.getElementById('chat-container');
const chatMessages = document.getElementById('chat-messages');
const userInput = document.getElementById('user-input');
const sendButton = document.getElementById('send-button');
const chatToggle = document.getElementById('chat-toggle');
const closeChat = document.getElementById('close-chat');

function addMessage(message, isUser) {
    const messageElement = document.createElement('div');
    messageElement.classList.add('message');
    messageElement.classList.add(isUser ? 'user-message' : 'bot-message');
    messageElement.textContent = message;
    chatMessages.appendChild(messageElement);
    chatMessages.scrollTop = chatMessages.scrollHeight;
}

function getBotResponse(message) {
    const responses = [
        "I'm sorry, I don't understand. Can you please rephrase that?",
        "That's an interesting question! Let me think about it.",
        "I'm not sure about that. Can you provide more information?",
        "I'm still learning, but I'll do my best to help you.",
        "That's a great point! I hadn't considered that before."
    ];
    return responses[Math.floor(Math.random() * responses.length)];
}

function sendMessage() {
    const message = userInput.value.trim();
    if (message) {
        addMessage(message, true);
        userInput.value = '';
        setTimeout(() => {
            const botResponse = getBotResponse(message);
            addMessage(botResponse, false);
        }, 500);
    }
}

function toggleChat() {
    chatContainer.classList.toggle('active');
}

sendButton.addEventListener('click', sendMessage);
userInput.addEventListener('keypress', (e) => {
    if (e.key === 'Enter') {
        sendMessage();
    }
});

chatToggle.addEventListener('click', toggleChat);
closeChat.addEventListener('click', toggleChat);