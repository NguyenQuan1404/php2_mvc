<?php

use Jenssegers\Blade\Blade;

class Controller
{
    public function view(string $view, array $data = []): void
    {
        // Đường dẫn đến thư mục views
        // Lưu ý: Đảm bảo VIEW_PATH đã được define trong bootstrap.php hoặc index.php
        // Nếu chưa, hãy sửa thành: __DIR__ . '/../views';
        $views = defined('VIEW_PATH') ? VIEW_PATH : __DIR__ . '/../views';
        $cache = __DIR__ . '/../../storage/cache'; // Sửa lại đường dẫn cache cho đúng cấp

        if (!file_exists($cache)) {
            mkdir($cache, 0777, true);
        }

        $blade = new Blade($views, $cache);

        echo $blade->render($view, $data);
    }

    protected function normalizeViewName(string $view): string
    {
        $view = trim($view);
        $view = str_replace(['\\', '/'], '.', $view);
        $view = preg_replace('/\.+/', '.', $view);
        return trim($view, '.');
    }

    public function model($name)
    {
        // $name ví dụ: 'UserClient' hoặc 'Product'
        // Mặc định Model nằm ở app/models/
        
        $modelPath = __DIR__ . "/../models/$name.php";
        
        if (file_exists($modelPath)) {
            require_once $modelPath;
        }
        
        // Vì Model của bạn (theo file model.php) không có namespace
        // Nên gọi trực tiếp class đó
        $class = ucfirst($name);
        
        // Nếu sau này bạn thêm namespace cho Model (ví dụ App\Models\User)
        // thì sửa logic ở đây. Hiện tại giữ nguyên theo code cũ:
        if (!class_exists($class)) {
            // Thử namespace (dự phòng)
            $classWithNamespace = "App\\Models\\$class";
            if (class_exists($classWithNamespace)) {
                return new $classWithNamespace();
            }
            throw new Exception("Model class '$name' not found");
        }
        
        return new $class();
    }

    public function redirect($path)
    {
        // Helper redirect đơn giản
        header('Location: ' . $path);
        exit;
    }
    
    // ... giữ nguyên các hàm khác
}