<?php

declare(strict_types=1);

namespace Tests\Integration\UseCases;

use App\Application\UseCases\ListTasksUseCase;
use App\Domain\Entities\Task;
use App\Domain\Interfaces\TaskRepositoryInterface;
use PHPUnit\Framework\TestCase;

final class ListTasksUseCaseTest extends TestCase
{
  public function testExecuteReturnsListFromRepository(): void
  {
    $task1 = new Task('t1', 'd1');
    $task2 = new Task('t2', 'd2');

    $repo = $this->createMock(TaskRepositoryInterface::class);
    $repo->method('listAll')->willReturn([$task1, $task2]);

    $useCase = new ListTasksUseCase($repo);

    $result = $useCase->execute();

    $this->assertIsArray($result);
    $this->assertCount(2, $result);
    $this->assertInstanceOf(Task::class, $result[0]);
  }
}
