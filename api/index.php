<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

set_exception_handler("ErrorHandler::handleException");

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$parts = explode('/', $path);

$resource = $parts[3];

$id = $parts[4] ?? null;

if ($resource != 'artists') {
    http_response_code(404);
    exit;
}

header('Content-Type: application/json; charset=UTF-8');

$db = new Database($_ENV['DB_HOST'], $_ENV['DB_NAME'], $_ENV['DB_USER'], $_ENV['DB_PASS']);

$artistGateway = new ArtistGateway($db);

$controller = new ArtistController($artistGateway);

$controller->processRequest($_SERVER['REQUEST_METHOD'], $id);


