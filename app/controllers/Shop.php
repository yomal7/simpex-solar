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
        // Check if user is logged in
        if (!isLoggedIn()) {
            flash('login_required', 'Please login to submit a purchase request');
            redirect('users/login');
        }

        // Get product details
        $product = $this->shopModel->getProductById($productId);

        if (!$product) {
            redirect('shop');
        }

        $data = [
            'product' => $product,
            'title' => 'Purchase Request - ' . $product->name
        ];

        $this->view('shop/v_purchaseRequest', $data);
    }
}
