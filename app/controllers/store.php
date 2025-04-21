<?php
// app/controllers/Store.php
class Store extends Controller
{
    private $shopModel;
    private $cartModel;
    private $paymentModel;

    public function __construct()
    {
        $this->shopModel = $this->model('M_Shop');
        $this->cartModel = $this->model('M_Cart');
        $this->paymentModel = $this->model('M_Payment');
    }

    // Display all products
    public function index()
    {
        // Check if user has a cart
        $cart = null;
        if (isset($_SESSION['user_id'])) {
            $cart = $this->cartModel->getCartByUserId($_SESSION['user_id']);
        }

        $data = [
            'title' => 'SimplEx Solar Store',
            'products' => $this->shopModel->getProducts(),
            'cart' => $cart
        ];

        $this->view('store/v_home', $data);
    }

    // Product details page
    public function product($id)
    {
        $product = $this->shopModel->getProductById($id);
        $features = $this->shopModel->getProductFeatures($id);

        if (!$product) {
            redirect('store');
        }

        $data = [
            'product' => $product,
            'features' => $features
        ];

        $this->view('store/v_productDetails', $data);
    }

    // Add to cart
    public function addToCart()
    {
        if (!isLoggedIn()) {
            $_SESSION['redirect_after_login'] = 'store';
            redirect('users/index');
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $productId = $_POST['product_id'];
            $quantity = $_POST['quantity'];

            // Get product details for current price
            $product = $this->shopModel->getProductById($productId);

            $cartData = [
                'user_id' => $_SESSION['user_id'],
                'product_id' => $productId,
                'quantity' => $quantity,
                'price_at_time' => $product->price // Store current price
            ];

            if ($this->cartModel->addToCart($cartData)) {
                flash('cart_message', 'Product added to cart');
            } else {
                flash('cart_message', 'Failed to add product to cart', 'alert alert-danger');
            }

            redirect('store/cart');
        }
    }

    // View cart
    public function cart()
    {
        if (!isLoggedIn()) {
            $_SESSION['redirect_after_login'] = 'store/cart';
            redirect('users/index');
        }

        $cartItems = $this->cartModel->getCartItems($_SESSION['user_id']);

        $data = [
            'cartItems' => $cartItems,
            'total' => $this->cartModel->getCartTotal($_SESSION['user_id'])
        ];

        $this->view('store/v_cart', $data);
    }

    // Update cart quantity
    public function updateCart()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $cartId = $_POST['cart_id'];
            $quantity = $_POST['quantity'];

            if ($this->cartModel->updateQuantity($cartId, $quantity)) {
                flash('cart_message', 'Cart updated');
            } else {
                flash('cart_message', 'Failed to update cart', 'alert alert-danger');
            }

            redirect('store/cart');
        }
    }

    // Remove from cart
    public function removeFromCart($cartId)
    {
        if ($this->cartModel->removeFromCart($cartId)) {
            flash('cart_message', 'Item removed from cart');
        } else {
            flash('cart_message', 'Failed to remove item', 'alert alert-danger');
        }

        redirect('store/cart');
    }

    // Checkout
    public function checkout()
    {
        if (!isLoggedIn()) {
            redirect('users/index');
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $cartItems = $this->cartModel->getCartItems($_SESSION['user_id']);

            if (empty($cartItems)) {
                flash('cart_message', 'Your cart is empty', 'alert alert-danger');
                redirect('store/cart');
            }

            $total = $this->cartModel->getCartTotal($_SESSION['user_id']);

            $data = [
                'cartItems' => $cartItems,
                'total' => $total
            ];

            $this->view('store/v_checkout', $data);
        } else {
            redirect('store/cart');
        }
    }

    public function paymentCheckout($orderId)
    {
        if (!isLoggedIn()) {
            redirect('users/index');
        }

        $orderItems = $this->shopModel->getOrderItems($orderId);
        $order = $this->shopModel->getOrderById($orderId);

        if (empty($orderItems) || !$order) {
            flash('cart_message', 'Order not found', 'alert alert-danger');
            redirect('store/orders');
        }

        $total = $this->shopModel->getOrderTotal($orderId);

        $data = [
            'orderItems' => $orderItems,
            'orderId' => $orderId,
            'shipping_address' => $order->shipping_address,
            'contact_phone' => $order->contact_phone,
            'payment_method' => $order->payment_method,
            'total' => $total
        ];

        $this->view('store/v_paymentCheckout', $data);
    }

    // Process order
    public function processOrder()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $cartItems = $this->cartModel->getCartItems($_SESSION['user_id']);

            if (empty($cartItems)) {
                flash('cart_message', 'Your cart is empty', 'alert alert-danger');
                redirect('store/cart');
            }

            $total = $this->cartModel->getCartTotal($_SESSION['user_id']);

            $orderData = [
                'user_id' => $_SESSION['user_id'],
                'total_amount' => $total,
                'shipping_address' => $_POST['shipping_address'],
                'contact_phone' => $_POST['contact_phone'],
                'payment_method' => $_POST['payment_method'],
                'status' => 'pending'
            ];

            $orderId = $this->shopModel->createOrder($orderData, $cartItems);

            if ($orderId) {
                // Clear cart
                $this->cartModel->clearCart($_SESSION['user_id']);

                // Redirect to payment
                $_SESSION['order_id'] = $orderId;
                redirect('store/payment/' . $orderId);
            } else {
                flash('order_message', 'Failed to create order', 'alert alert-danger');
                redirect('store/cart');
            }
        }
    }

    public function updateOrder($orderId)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $shippingAddress = $_POST['shipping_address'];
            $contactPhone = $_POST['contact_phone'];
            $paymentMethod = $_POST['payment_method'];

            if (empty($shippingAddress) || empty($contactPhone) || empty($paymentMethod)) {
                flash('order_message', 'Please fill in all fields', 'alert alert-danger');
                redirect('store/paymentCheckout/' . $orderId);
            }

            $data = [
                'shipping_address' => $shippingAddress,
                'contact_phone' => $contactPhone,
                'payment_method' => $paymentMethod
            ];

            if ($this->shopModel->updateOrder($orderId, $data)) {
                flash('order_message', 'Order updated successfully');
                redirect('store/payment/' . $orderId);
            } else {
                flash('order_message', 'Failed to update order', 'alert alert-danger');
                redirect('store/orderDetails/' . $orderId);
            }
        } else {
            redirect('store/orders');
        }
    }

    // Payment page
    public function payment($orderId)
    {
        if (!isLoggedIn()) {
            redirect('users/index');
        }

        $order = $this->shopModel->getOrderById($orderId);

        if (!$order || $order->user_id != $_SESSION['user_id']) {
            redirect('store');
        }

        $data = [
            'order' => $order
        ];

        $this->view('store/v_payment', $data);
    }

    // Process payment
    public function processPayment()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $orderId = $_POST['order_id'];
            $paymentMethod = $_POST['payment_method'];

            // Handle different payment methods
            switch ($paymentMethod) {
                case 'bank_deposit':
                    if (isset($_FILES['bank_slip']) && $_FILES['bank_slip']['error'] == 0) {
                        $uploadDir = 'uploads/bank_slips/';
                        $fileName = time() . '_' . basename($_FILES['bank_slip']['name']);
                        $targetPath = $uploadDir . $fileName;

                        if (move_uploaded_file($_FILES['bank_slip']['tmp_name'], $targetPath)) {
                            $paymentData = [
                                'order_id' => $orderId,
                                'payment_method' => 'bank_deposit',
                                'bank_slip' => $fileName,
                                'status' => 'pending_verification'
                            ];

                            if ($this->paymentModel->createPayment($paymentData)) {
                                flash('order_message', 'Payment submitted successfully. We will verify and process your order.');
                                redirect('store/orderConfirmation/' . $orderId);
                            }
                        }
                    }
                    break;

                case 'online':
                    // Implement online payment gateway
                    break;

                case 'cash':
                    $paymentData = [
                        'order_id' => $orderId,
                        'payment_method' => 'cash',
                        'status' => 'pending'
                    ];

                    if ($this->paymentModel->createPayment($paymentData)) {
                        flash('order_message', 'Order placed successfully. Please pay when you receive the delivery.');
                        redirect('store/orderConfirmation/' . $orderId);
                    }
                    break;
            }
        }
    }

    // Order confirmation
    public function orderConfirmation($orderId)
    {
        if (!isLoggedIn()) {
            redirect('users/index');
        }

        $order = $this->shopModel->getOrderById($orderId);

        if (!$order || $order->user_id != $_SESSION['user_id']) {
            redirect('store');
        }

        $data = [
            'order' => $order
        ];

        $this->view('store/v_orderConfirmation', $data);
    }

    // Order tracking
    public function orders()
    {
        if (!isLoggedIn()) {
            redirect('users/index');
        }

        $orders = $this->shopModel->getUserOrders($_SESSION['user_id']);

        $data = [
            'orders' => $orders
        ];

        $this->view('store/v_orders', $data);
    }

    // View order details
    public function orderDetails($orderId)
    {
        if (!isLoggedIn()) {
            redirect('users/index');
        }

        $order = $this->shopModel->getOrderById($orderId);

        if (!$order || $order->user_id != $_SESSION['user_id']) {
            redirect('store/orders');
        }

        $orderItems = $this->shopModel->getOrderItems($orderId);

        $data = [
            'order' => $order,
            'orderItems' => $orderItems
        ];

        $this->view('store/v_orderDetails', $data);
    }
}
