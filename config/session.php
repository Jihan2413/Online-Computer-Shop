<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!defined('BASE_URL')) {
    $docRoot     = realpath($_SERVER['DOCUMENT_ROOT']);
    $projectRoot = realpath(__DIR__ . '/../');
    $baseUrl     = str_replace('\\', '/', str_replace($docRoot, '', $projectRoot));
    $baseUrl     = '/' . trim($baseUrl, '/');
    if ($baseUrl === '/') {
        $baseUrl = '';
    }
    define('BASE_URL', $baseUrl);
}

function base_url(string $uri = ''): string
{
    $uri = ltrim($uri, '/');
    if (BASE_URL === '') {
        return $uri === '' ? '/' : "/{$uri}";
    }
    return BASE_URL . '/' . $uri;
}

function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function validate_csrf_token(string $token): bool
{
    return !empty($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}
?>
