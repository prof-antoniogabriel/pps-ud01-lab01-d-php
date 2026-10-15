<?php

declare(strict_types=1);

use App\Config\Settings;
use App\Controller\NoticeController;
use App\Service\NoticeService;
use Slim\Factory\AppFactory;

require dirname(__DIR__) . '/vendor/autoload.php';

$app = AppFactory::create();
$app->addBodyParsingMiddleware();
$app->addErrorMiddleware(Settings::environment() === 'development', true, true);

$controller = new NoticeController(new NoticeService());
$app->get('/health', function ($request, $response) {
    $response->getBody()->write(json_encode(['status' => 'ok']));
    return $response->withHeader('Content-Type', 'application/json');
});
$app->get('/api/notices', [$controller, 'index']);
$app->get('/api/notices/{id:[0-9]+}', [$controller, 'show']);
$app->post('/api/notices', [$controller, 'store']);

return $app;
