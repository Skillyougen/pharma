<?php
class Controller {
    protected function render(string $view, array $data = []): void {
        extract($data);
        $path = BASE_PATH.'/views/'.$view.'.php';
        if (!file_exists($path)) { http_response_code(404); require BASE_PATH.'/views/errors/404.php'; exit; }
        require $path;
    }
    protected function redirect(string $url): void { header('Location: '.$url); exit; }
    protected function isAdmin(): bool { return isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin'; }
    protected function requireAdmin(): void {
        if (!$this->isAdmin()) $this->redirect(BASE_URL.'/index.php?page=auth&action=login');
    }
    protected function isLoggedIn(): bool { return isset($_SESSION['user']); }
    protected function json(array $data): void { header('Content-Type: application/json'); echo json_encode($data); exit; }
}
