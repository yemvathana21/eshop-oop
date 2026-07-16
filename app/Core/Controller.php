<?php
namespace App\Core;

class Controller {
    protected function render($view, $data = [], $layout = 'customer') {
        // Extract parameters to local variables
        extract($data);

        // Determine the view file path
        $viewFile = APP_PATH . 'Views' . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $view) . '.php';

        if (!file_exists($viewFile)) {
            die("View '$view' not found at '$viewFile'");
        }

        // Buffer the view content
        ob_start();
        include $viewFile;
        $content = ob_get_clean();

        // Include the specified layout
        $layoutFile = APP_PATH . 'Views' . DIRECTORY_SEPARATOR . 'layouts' . DIRECTORY_SEPARATOR . $layout . '.php';
        if (file_exists($layoutFile)) {
            include $layoutFile;
        } else {
            // Fallback to direct output if layout doesn't exist
            echo $content;
        }
    }

    protected function redirect($url) {
        header('Location: ' . BASE_URL . ltrim($url, '/'));
        exit;
    }

    protected function json($data) {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}
