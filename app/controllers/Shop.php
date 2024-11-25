<?php

class Shop extends Controller
{
    private $productModel;

    public function __construct()
    {
        $this->productModel = $this->model('M_Shop');
    }

    public function index()
    {
        // Get category from URL, defaulting to 'all' if not set
        $category = isset($_GET['category']) ? trim($_GET['category']) : 'all';

        try {
            // Fetch products based on category
            if ($category === 'all') {
                $products = $this->productModel->getAllProducts();
            } else {
                $products = $this->productModel->getProductsByCategory($category);
            }

            // Initialize products as empty array if null
            if ($products === null) {
                $products = [];
            }

            $data = [
                'products' => $products,
                'category' => $category,
                'title' => 'Online Store'
            ];

            $this->view('shop/v_shopLanding', $data);
        } catch (Exception $e) {
            // Log error and show user-friendly message
            error_log($e->getMessage());
            $data = [
                'products' => [],
                'category' => $category,
                'error' => 'Unable to fetch products. Please try again later.',
                'title' => 'Online Store'
            ];
            $this->view('shop/v_shopLanding', $data);
        }
    }

    public function detail($id = null)
    {
        try {
            if ($id === null) {
                redirect('shop/index');
            }

            $product = $this->productModel->getProductById($id);

            if ($product) {
                $data = [
                    'product' => $product,
                    'title' => $product->name
                ];
                $this->view('shop/v_productDetail', $data);
            } else {
                throw new Exception('Product not found');
            }
        } catch (Exception $e) {
            $data = ['error' => 'Product not found'];
            $this->view('errors/404', $data);
        }
    }
}
