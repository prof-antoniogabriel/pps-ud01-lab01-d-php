<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\NoticeService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Psr7\Response;

final class NoticeController
{
    public function __construct(private NoticeService $service) {}

    private function json(array $payload, int $status = 200): ResponseInterface
    {
        $response = new Response($status);
        $response->getBody()->write(json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function index(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        return $this->json($this->service->all());
    }

    public function show(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $notice = $this->service->find((int) $args['id']);
        return $notice ? $this->json($notice) : $this->json(['error' => 'Aviso no encontrado'], 404);
    }

    public function store(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $data = json_decode((string) $request->getBody(), true);
        if (!is_array($data) || !isset($data['title'], $data['severity'])
            || !is_string($data['title']) || trim($data['title']) === ''
            || !in_array($data['severity'], ['low', 'medium', 'high'], true)) {
            return $this->json(['error' => 'Datos no válidos'], 400);
        }
        return $this->json($this->service->create(trim($data['title']), $data['severity']), 201);
    }
}
