<?php

declare(strict_types=1);

namespace Tests\Unit\Infrastructure\Repositories;

use App\Domain\Entities\Task;
use App\Infrastructure\Repositories\DoctrineTaskRepository;
use Doctrine\ORM\EntityManager;
use PHPUnit\Framework\TestCase;

final class DoctrineTaskRepositoryTest extends TestCase
{
  public function testListAllReturnsTasks(): void
  {
    $task = new Task('t', 'd');

    $repoMock = $this->getMockBuilder(\Doctrine\ORM\EntityRepository::class)
      ->disableOriginalConstructor()
      ->getMock();
    $repoMock->method('findAll')->willReturn([$task]);

    $em = $this->createMock(EntityManager::class);
    $em->method('getRepository')->willReturn($repoMock);

    $repository = new DoctrineTaskRepository($em);

    $result = $repository->listAll();

    $this->assertIsArray($result);
    $this->assertCount(1, $result);
    $this->assertInstanceOf(Task::class, $result[0]);
  }

  public function testSavePersistsAndFlushes(): void
  {
    $task = new Task('t', 'd');

    $em = $this->createMock(EntityManager::class);
    $em->expects($this->once())->method('persist')->with($task);
    $em->expects($this->once())->method('flush');

    $repository = new DoctrineTaskRepository($em);

    $returned = $repository->save($task);

    $this->assertSame($task, $returned);
  }

  public function testFindByIdReturnsTaskOrNull(): void
  {
    $task = new Task('t', 'd');

    $em = $this->createMock(EntityManager::class);
    $em->method('find')->willReturnOnConsecutiveCalls($task, null);

    $repository = new DoctrineTaskRepository($em);

    $this->assertSame($task, $repository->findById(1));
    $this->assertNull($repository->findById(2));
  }

  public function testUpdatePersistsAndFlushes(): void
  {
    $task = new Task('t', 'd');

    $em = $this->createMock(EntityManager::class);
    $em->expects($this->once())->method('persist')->with($task);
    $em->expects($this->once())->method('flush');

    $repository = new DoctrineTaskRepository($em);

    $this->assertSame($task, $repository->update($task));
  }

  public function testDeleteRemovesAndFlushes(): void
  {
    $task = new Task('t', 'd');

    $em = $this->createMock(EntityManager::class);
    $em->expects($this->once())->method('remove')->with($task);
    $em->expects($this->once())->method('flush');

    $repository = new DoctrineTaskRepository($em);

    $repository->delete($task);

    $this->assertTrue(true);
  }
}
