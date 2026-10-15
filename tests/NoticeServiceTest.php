<?php

declare(strict_types=1);

namespace Tests;

use App\Service\NoticeService;
use PHPUnit\Framework\TestCase;

final class NoticeServiceTest extends TestCase
{
    public function testListsSeedNotices(): void
    {
        $service = new NoticeService();
        self::assertCount(2, $service->all());
    }

    public function testCreatesNotice(): void
    {
        $service = new NoticeService();
        $created = $service->create('Intentos fallidos', 'high');
        self::assertSame(3, $created['id']);
        self::assertSame($created, $service->find(3));
    }
}
