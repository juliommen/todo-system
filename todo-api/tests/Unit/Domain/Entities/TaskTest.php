<?php

declare(strict_types=1);

namespace App\Domain\Entities;

use PHPUnit\Framework\TestCase;

final class TaskTest extends TestCase
{
    public function testConstructorAndGetters(): void
    {
        $task = new Task('My title', 'My description');

        $this->assertNull($task->getId());
        $this->assertSame('My title', $task->getTitle());
        $this->assertSame('My description', $task->getDescription());
        $this->assertSame('pending', $task->getStatus());
        $this->assertInstanceOf(\DateTimeImmutable::class, $task->getCreatedAt());
        $this->assertInstanceOf(\DateTimeImmutable::class, $task->getUpdatedAt());
    }

    public function testMarkCompletedUpdatesStatusAndUpdatedAt(): void
    {
        $task = new Task('t', 'd');
        $before = $task->getUpdatedAt()->getTimestamp();

        usleep(1000000);

        $task->markCompleted();

        $this->assertSame('completed', $task->getStatus());
        $this->assertGreaterThan($before, $task->getUpdatedAt()->getTimestamp());
    }

    public function testMarkPendingUpdatesStatusAndUpdatedAt(): void
    {
        $task = new Task('t', 'd');
        $before = $task->getUpdatedAt()->getTimestamp();

        usleep(1000000);

        $task->markPending();

        $this->assertSame('pending', $task->getStatus());
        $this->assertGreaterThan($before, $task->getUpdatedAt()->getTimestamp());
    }

    public function testToArrayContainsExpectedKeysAndFormats(): void
    {
        $task = new Task('t', 'd');
        $arr = $task->toArray();

        $this->assertArrayHasKey('id', $arr);
        $this->assertArrayHasKey('title', $arr);
        $this->assertArrayHasKey('description', $arr);
        $this->assertArrayHasKey('status', $arr);
        $this->assertArrayHasKey('created_at', $arr);
        $this->assertArrayHasKey('updated_at', $arr);

        $this->assertSame('t', $arr['title']);
        $this->assertSame('d', $arr['description']);
        $this->assertSame('pending', $arr['status']);

        $this->assertNotEmpty($arr['created_at']);
        $this->assertNotEmpty($arr['updated_at']);
    }
}
