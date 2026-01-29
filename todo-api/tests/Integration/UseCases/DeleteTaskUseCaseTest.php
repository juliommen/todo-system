<?php

declare(strict_types=1);

namespace Tests\Integration\UseCases;

use App\Application\UseCases\DeleteTaskUseCase;
use App\Domain\Entities\Task;
use App\Domain\Interfaces\TaskRepositoryInterface;
use PHPUnit\Framework\TestCase;

final class DeleteTaskUseCaseTest extends TestCase
{
  public function testExecuteReturnsFalseWhenNotFound(): void
  {
    $repo = $this->createMock(TaskRepositoryInterface::class);
    $repo->method('findById')->willReturn(null);

    $useCase = new DeleteTaskUseCase($repo);

    $this->assertFalse($useCase->execute(1));
  }

  public function testExecuteDeletesAndReturnsTrueWhenFound(): void
  {
    $task = new Task('t', 'd');

    $repo = $this->createMock(TaskRepositoryInterface::class);
    $repo->method('findById')->willReturn($task);
    $repo->expects($this->once())->method('delete')->with($this->isInstanceOf(Task::class));

    $useCase = new DeleteTaskUseCase($repo);

    $this->assertTrue($useCase->execute(1));
  }
}
