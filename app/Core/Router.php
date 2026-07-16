<?php
namespace App\Core;

class Router {
    private $routes = [];

    public function add($method, $path, $handler) {
        // Normalize path
        $path = '/' . trim($path, '/');
        if ($path === '//') {
            $path = '/';
        }
        $this->routes[] = [
            'method' => strtoupper($method),
            'path' => $path,
            'handler' => $handler
        ];
    }

    public function get($path, $handler) {
        $this->add('GET', $path, $handler);
    }

    public function post($path, $handler) {
        $this->add('POST', $path, $handler);
    }

    public function dispatch($url, $method) {
        // Get path from URL and normalize it
        $path = parse_url($url, PHP_URL_PATH);
        
        // Strip the base path prefix to get the route path.
        // Try SCRIPT_NAME directory first (works for direct access), then BASE_URL path (works for .htaccess rewrites).
        $scriptDir = rtrim(dirname($_SERVER['SCRIPT_NAME'] ?? ''), '\\/');
        $baseUrlPath = parse_url(rtrim(BASE_URL, '/'), PHP_URL_PATH);
        foreach ([$scriptDir, $baseUrlPath] as $prefix) {
            if (!empty($prefix) && strpos($path, $prefix) === 0) {
                $path = substr($path, strlen($prefix));
                break;
            }
        }
        
        $path = '/' . trim($path, '/');
        if ($path === '//') {
            $path = '/';
        }

        $method = strtoupper($method);

        foreach ($this->routes as $route) {
            if ($route['method'] === $method && $route['path'] === $path) {
                $handler = $route['handler'];
                if (is_array($handler)) {
                    $controllerClass = $handler[0];
                    $action = $handler[1];
                    
                    if (class_exists($controllerClass)) {
                        $controller = new $controllerClass();
                        if (method_exists($controller, $action)) {
                            $controller->$action();
                            return;
                        } else {
                            die("Action '$action' not found in controller '$controllerClass'");
                        }
                    } else {
                        die("Controller class '$controllerClass' not found");
                    }
                } elseif (is_callable($handler)) {
                    call_user_func($handler);
                    return;
                }
            }
        }

        // Standard beautifully designed 404 error page
        http_response_code(404);
        $this->render404();
    }

    private function render404() {
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>404 - Page Not Found</title>
            <script src="https://cdn.tailwindcss.com"></script>
        </head>
        <body class="bg-gray-100 flex items-center justify-center h-screen">
            <div class="text-center">
                <h1 class="text-9xl font-extrabold text-blue-600">404</h1>
                <p class="text-2xl font-bold md:text-3xl mt-4">Oops! Page not found</p>
                <p class="text-gray-500 mt-2">The page you are looking for might have been removed or is temporarily unavailable.</p>
                <a href="<?php echo BASE_URL; ?>" class="inline-block px-6 py-3 mt-6 text-sm font-semibold text-white bg-blue-600 rounded-md hover:bg-blue-700 transition">Go to Homepage</a>
            </div>
        </body>
        </html>
        <?php
    }
}
