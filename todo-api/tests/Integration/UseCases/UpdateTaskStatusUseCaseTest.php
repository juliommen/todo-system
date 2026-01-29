<?php

declare(strict_types=1);

namespace Tests\Integration\UseCases;

use App\Application\UseCases\UpdateTaskStatusUseCase;
use App\Domain\Entities\Task;
use App\Domain\Interfaces\TaskRepositoryInterface;
use PHPUnit\Framework\TestCase;

final class UpdateTaskStatusUseCaseTest extends TestCase
{
  public function testExecuteReturnsNullWhenNotFound(): void
  {
    $repo = $this->createMock(TaskRepositoryInterface::class);
    $repo->method('findById')->willReturn(null);

    $useCase = new UpdateTaskStatusUseCase($repo);

    $this->assertNull($useCase->execute(1, 'completed'));
  }

  public function testExecuteUpdatesStatusCompletedAndCallsUpdate(): void
  {
    $task = new Task('t', 'd');

    $repo = $this->createMock(TaskRepositoryInterface::class);
    $repo->method('findById')->willReturn($task);
    $repo->expects($this->once())->method('update')->with($this->isInstanceOf(Task::class))->willReturnCallback(fn(Task $t) => $t);

    $useCase = new UpdateTaskStatusUseCase($repo);

    $result = $useCase->execute(1, 'completed');

    $this->assertInstanceOf(Task::class, $result);
    $this->assertSame('completed', $result->getStatus());
  }

  public function testExecuteUpdatesStatusPendingAndCallsUpdate(): void
  {
    $task = new Task('t', 'd');

    $repo = $this->createMock(TaskRepositoryInterface::class);
    $repo->method('findById')->willReturn($task);
    $repo->expects($this->once())->method('update')->with($this->isInstanceOf(Task::class))->willReturnCallback(fn(Task $t) => $t);

    $useCase = new UpdateTaskStatusUseCase($repo);

    $result = $useCase->execute(1, 'pending');

    $this->assertInstanceOf(Task::class, $result);
    $this->assertSame('pending', $result->getStatus());
  }
}
