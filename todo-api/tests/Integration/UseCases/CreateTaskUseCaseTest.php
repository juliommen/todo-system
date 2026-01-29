<?php

declare(strict_types=1);

namespace Tests\Integration\UseCases;

use App\Application\UseCases\CreateTaskUseCase;
use App\Domain\Entities\Task;
use App\Domain\Interfaces\TaskRepositoryInterface;
use PHPUnit\Framework\TestCase;

final class CreateTaskUseCaseTest extends TestCase
{
  public function testExecuteCallsSaveAndReturnsTask(): void
  {
    $repo = $this->createMock(TaskRepositoryInterface::class);

    $repo->expects($this->once())
      ->method('save')
      ->with($this->isInstanceOf(Task::class))
      ->willReturnCallback(fn(Task $t) => $t);

    $useCase = new CreateTaskUseCase($repo);

    $task = $useCase->execute('title', 'description');

    $this->assertInstanceOf(Task::class, $task);
    $this->assertSame('title', $task->getTitle());
    $this->assertSame('description', $task->getDescription());
    $this->assertSame('pending', $task->getStatus());
  }
}
