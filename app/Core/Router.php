<?php
// SỬA: Dùng __DIR__ để định vị chính xác thư mục chứa file hiện tại
// Đảm bảo file tồn tại trước khi require để tránh lỗi Fatal
$authMiddlewarePath = __DIR__ . '/Middleware/AuthMiddleware.php';
$adminMiddlewarePath = __DIR__ . '/Middleware/AdminMiddleware.php';

if (file_exists($authMiddlewarePath)) require_once $authMiddlewarePath;
if (file_exists($adminMiddlewarePath)) require_once $adminMiddlewarePath;

class Router
{
    public function dispatch(string $uri): void
    {
        // 1. KHỞI ĐỘNG SESSION TOÀN CỤC (QUAN TRỌNG)
        // Đảm bảo session luôn có sẵn cho mọi Controller và Middleware
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $path = parse_url($uri, PHP_URL_PATH) ?? '';
        $path = trim($path, '/');

        $basePath = $this->basePath();
        if ($basePath !== '' && str_starts_with($path, $basePath)) {
            $path = trim(substr($path, strlen($basePath)), '/');
        }

        $segments = $path === '' ? [] : explode('/', $path);
        $controllerName = ucfirst($segments[0] ?? 'home') . 'Controller';
        $action = $segments[1] ?? 'index';
        $params = array_slice($segments, 2);

        if (!class_exists($controllerName)) {
            $this->notFound("Class '$controllerName' not found");
            return;
        }

        $controller = new $controllerName();

        if (!method_exists($controller, $action)) {
            $this->notFound("Method '$action' not found in '$controllerName'");
            return;
        }

        // Kích hoạt Middleware
        $this->runMiddleware($controllerName, $action);

        call_user_func_array([$controller, $action], $params);
    }

    private function runMiddleware($controllerName, $action)
    {
        // Kiểm tra xem class Middleware có tồn tại không trước khi new
        if (!class_exists('AuthMiddleware') || !class_exists('AdminMiddleware')) {
            return; // Nếu chưa tạo file Middleware thì bỏ qua (để không lỗi web)
        }

        $auth = new AuthMiddleware();
        $admin = new AdminMiddleware();

        /**
         * CẤU HÌNH PHÂN QUYỀN
         */
        $config = [
            // Admin only
            'DashboardController' => [$auth, $admin],
            'CategoryController' => [$auth, $admin],
            
            // User login required
            'UserController' => [$auth],
            
            // Auth specific actions
            'AuthController' => [
                'logout' => [$auth], 
                'changePassword' => [$auth]
            ]
        ];

        if (isset($config[$controllerName])) {
            $rules = $config[$controllerName];

            if (array_is_list($rules)) {
                foreach ($rules as $middleware) {
                    $middleware->handle();
                }
            } else if (isset($rules[$action])) {
                foreach ($rules[$action] as $middleware) {
                    $middleware->handle();
                }
            }
        }
    }

    public function basePath(): string
    {
        $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
        $dir = trim(dirname($scriptName), '/');
        return $dir;
    }

    public function notFound($message): void
    {
        http_response_code(404);
        echo "<h1 style='color: red'> 404 Not Found - " . htmlspecialchars($message) . " </h1>";
    }
}