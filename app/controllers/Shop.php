<?php
class Shop extends Controller
{
    private $shopModel;
    // private $blogService;

    public function __construct()
    {
        $this->shopModel = $this->model('M_Shop');
        // $this->blogModel = $this->model('M_Blog');
        // require_once '../app/services/BlogService.php';
        // $this->blogService = new BlogService($this->blogModel);
    }

    public function index()
    {
        $products = $this->shopModel->getProducts();

        $data = [
            'title' => 'Welcome to Solar Store',
            'products' => $products
        ];

        $this->view('shop/v_home', $data);
    }
    public function product($id)
    {
        $product = $this->shopModel->getProductById($id);
        $features = $this->shopModel->getProductFeatures($id);

        if (!$product) {
            redirect('shop');
        }

        $data = [
            'product' => $product,
            'features' => $features
        ];

        $this->view('shop/v_productDetails', $data);
    }

    public function requestPurchase($productId)
    {
        if (!isLoggedIn()) {
            $_SESSION['intended_product_id'] = $productId;
            flash('login_required', 'Please login to submit a purchase request');
            redirect('users/login');
        }

        $product = $this->shopModel->getProductById($productId);

        if (!$product) {
            redirect('shop');
        }

        $quantity = isset($_GET['quantity']) ? (int)$_GET['quantity'] : 1;
        $deliveryOption = isset($_GET['delivery']) ? $_GET['delivery'] : 'deliver';

        $subtotal = $product->price * $quantity;
        $deliveryFee = ($deliveryOption === 'deliver') ? 450.00 : 0;
        $total = $subtotal + $deliveryFee;

        $data = [
            'product' => $product,
            'quantity' => $quantity,
            'delivery_option' => $deliveryOption,
            'subtotal' => $subtotal,
            'delivery_fee' => $deliveryFee,
            'total' => $total,
            'title' => 'Purchase Request - ' . $product->name
        ];

        $this->view('shop/v_purchaseRequest', $data);
    }

    public function submitPurchaseRequest()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'product_id' => trim($_POST['product_id']),
                'user_id' => $_SESSION['user_id'],
                'delivery_option' => trim($_POST['delivery_option']),
                'full_name' => trim($_POST['full_name']),
                'email' => trim($_POST['email']),
                'phone_number' => trim($_POST['phone_number']),
                'street_address' => trim($_POST['street_address']),
                'city' => trim($_POST['city']),
                'province' => trim($_POST['province']),
                'postal_code' => trim($_POST['postal_code']),
                'address_notes' => !empty($_POST['address_notes']) ? trim($_POST['address_notes']) : null,
                'quantity' => trim($_POST['quantity'])
            ];

            if (empty($data['product_id']) || empty($data['full_name']) || empty($data['email'])) {
                flash('purchase_error', 'Please fill all required fields');
                redirect('shop/purchaseRequest/' . $data['product_id']);
                return;
            }

            if ($this->shopModel->addPreOrder($data)) {
                flash('purchase_success', 'Your purchase request has been submitted successfully');
                redirect('client/shop');
            } else {
                flash('purchase_error', 'Something went wrong, please try again');
                redirect('shop/purchaseRequest/' . $data['product_id']);
            }
        } else {
            redirect('shop');
        }
    }
}
