<?php

declare(strict_types=1);

namespace App\Service;

final class NoticeService
{
    private array $notices = [
        ['id' => 1, 'title' => 'Acceso sospechoso', 'severity' => 'high'],
        ['id' => 2, 'title' => 'Actualización pendiente', 'severity' => 'medium'],
    ];

    public function all(): array
    {
        return array_values($this->notices);
    }

    public function find(int $id): ?array
    {
        foreach ($this->notices as $notice) {
            if ($notice['id'] === $id) {
                return $notice;
            }
        }
        return null;
    }

    public function create(string $title, string $severity): array
    {
        $ids = array_column($this->notices, 'id');
        $notice = [
            'id' => $ids ? max($ids) + 1 : 1,
            'title' => $title,
            'severity' => $severity,
        ];
        $this->notices[] = $notice;
        return $notice;
    }
}
