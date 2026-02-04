<?php
// Load middleware
require_once __DIR__ . '/Middleware/AuthMiddleware.php';
require_once __DIR__ . '/Middleware/AdminMiddleware.php';

class Router
{
    private $controllerNamespace = ''; // Namespace hiện tại (Admin hoặc Client)
    private $controllerPath = '';      // Đường dẫn file tương ứng

    public function dispatch(string $uri): void
    {
        // 1. Khởi động session
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $path = parse_url($uri, PHP_URL_PATH) ?? '';
        $path = trim($path, '/');

        // Xử lý base path (nếu chạy localhost/folder_du_an)
        $basePath = $this->basePath();
        if ($basePath !== '' && str_starts_with($path, $basePath)) {
            $path = trim(substr($path, strlen($basePath)), '/');
        }

        $segments = $path === '' ? [] : explode('/', $path);

        // --- LOGIC PHÂN LUỒNG ADMIN / CLIENT ---
        
        $isAdmin = false;
        
        // Kiểm tra xem URL có bắt đầu bằng 'admin' không
        if (isset($segments[0]) && strtolower($segments[0]) === 'admin') {
            $isAdmin = true;
            array_shift($segments); // Bỏ chữ 'admin' ra khỏi mảng
            
            // Cấu hình cho Admin
            $this->controllerNamespace = 'App\\Controllers\\Admin\\';
            $folder = 'Admin';
            $defaultController = 'DashboardController';
        } else {
            // Cấu hình cho Client
            $this->controllerNamespace = 'App\\Controllers\\Client\\';
            $folder = 'Client';
            $defaultController = 'HomeController';
        }

        // Xác định Controller và Action
        $controllerName = isset($segments[0]) && $segments[0] !== '' 
            ? ucfirst($segments[0]) . 'Controller' 
            : $defaultController;

        $action = $segments[1] ?? 'index';
        $params = array_slice($segments, 2);

        // --- XỬ LÝ LOAD FILE CONTROLLER ---
        
        // Đường dẫn file vật lý: app/Controllers/Admin/TenController.php
        $fileController = __DIR__ . "/../Controllers/$folder/$controllerName.php";

        if (!file_exists($fileController)) {
            $this->notFound("File not found: $fileController");
            return;
        }

        require_once $fileController;

        // Tên class đầy đủ (bao gồm namespace giả lập nếu bạn dùng namespace thật)
        // Vì bạn chưa dùng namespace chuẩn PSR-4 trong các file cũ, 
        // nên ta sẽ new Class trực tiếp hoặc sửa file Controller để thêm namespace.
        // Dưới đây tôi giả định bạn SẼ THÊM namespace vào file Controller.
        
        $fullClassName = $this->controllerNamespace . $controllerName;
        
        // Fallback: Nếu trong file controller bạn chưa kịp thêm namespace 
        // thì thử gọi tên class không có namespace
        if (!class_exists($fullClassName)) {
            if (class_exists($controllerName)) {
                $fullClassName = $controllerName;
            } else {
                $this->notFound("Class '$fullClassName' not found");
                return;
            }
        }

        $controller = new $fullClassName();

        if (!method_exists($controller, $action)) {
            $this->notFound("Method '$action' not found in '$controllerName'");
            return;
        }

        // Kích hoạt Middleware (đã cập nhật logic namespace)
        $this->runMiddleware($controllerName, $action, $isAdmin);

        call_user_func_array([$controller, $action], $params);
    }

    private function runMiddleware($controllerName, $action, $isAdmin)
    {
        if (!class_exists('AuthMiddleware') || !class_exists('AdminMiddleware')) {
            return;
        }

        $auth = new AuthMiddleware();
        $admin = new AdminMiddleware();

        // NẾU LÀ ROUTE ADMIN -> BẮT BUỘC CHECK QUYỀN LUÔN (Logic tự động)
        // Không cần khai báo thủ công từng controller nữa
        if ($isAdmin && $controllerName !== 'AuthController') {
            $auth->handle();
            $admin->handle();
            return; // Đã check xong cho Admin
        }

        // CẤU HÌNH CHO CLIENT (Hoặc các trường hợp ngoại lệ)
        $config = [
            // Ví dụ UserController bên Client cần đăng nhập mới xem được hồ sơ
            'UserController' => [$auth],
            
            'AuthController' => [
                'logout' => [$auth], 
                'changePassword' => [$auth],
                // Login/Register không cần chặn
            ]
        ];

        // Lấy tên class gốc (bỏ namespace để so sánh config cho dễ)
        $simpleName = (strpos($controllerName, '\\') !== false) 
            ? substr(strrchr($controllerName, '\\'), 1) 
            : $controllerName;

        if (isset($config[$simpleName])) {
            $rules = $config[$simpleName];
            if (array_is_list($rules)) {
                foreach ($rules as $middleware) $middleware->handle();
            } else if (isset($rules[$action])) {
                foreach ($rules[$action] as $middleware) $middleware->handle();
            }
        }
    }

    public function basePath(): string
    {
        $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
        return trim(dirname($scriptName), '/');
    }

    public function notFound($message): void
    {
        http_response_code(404);
        echo "<h1 style='color: red; text-align: center; margin-top: 50px;'>404 Not Found</h1>";
        echo "<p style='text-align: center;'>$message</p>";
    }
}