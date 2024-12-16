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
    public function productDetail()
    {
        $data = [
            // 'product' => $this->shopModel->getProductById($productId)
        ];
        $this->view('shop/v_productDetails', $data);
    }
    public function purchaseRequest()
    {
        $data = [
            // 'product' => $this->shopModel->getProductById($productId)
        ];
        $this->view('shop/v_purchaseRequest', $data);
    }
}
