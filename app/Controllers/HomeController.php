<?php
class HomeController extends Controller
{
    public function index()
    {

        $products = $this->model('Product')->getActiveProducts();
        
        $categories = $this->model('Category')->index();

        $this->view('home/index', [
            'products' => $products,
            'categories' => $categories,
            'title' => 'Trang chủ - Shop Giày Thể Thao'
        ]);
    }
}