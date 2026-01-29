<?php

declare(strict_types=1);

namespace Tests\Unit\Presentation\Controllers\V1;

use App\Domain\Entities\Task;
use App\Domain\Interfaces\TaskRepositoryInterface;
use App\Presentation\Controllers\V1\TaskController;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Psr7\Response;

final class TaskControllerTest extends TestCase
{
  public function testListReturnsJsonArray(): void
  {
    $task = new Task('t', 'd');

    $repo = $this->createMock(TaskRepositoryInterface::class);
    $repo->method('listAll')->willReturn([$task]);

    $controller = new TaskController($repo, $this->createMock(\Psr\Log\LoggerInterface::class));

    $response = new Response();
    $req = $this->createMock(Request::class);

    $res = $controller->list($req, $response);

    $this->assertSame(200, $res->getStatusCode());
    $body = (string)$res->getBody();
    $this->assertStringContainsString('title', $body);
    $this->assertStringContainsString('description', $body);
  }

  public function testCreateValidationErrorsReturn422(): void
  {
    $repo = $this->createMock(TaskRepositoryInterface::class);
    $controller = new TaskController($repo, $this->createMock(\Psr\Log\LoggerInterface::class));

    $request = $this->createMock(Request::class);
    $request->method('getParsedBody')->willReturn(['title' => '', 'description' => '']);

    $response = new Response();

    $res = $controller->create($request, $response);

    $this->assertSame(422, $res->getStatusCode());
    $this->assertStringContainsString('error', (string)$res->getBody());
  }

  public function testCreateSuccessReturns201AndTask(): void
  {
    $repo = $this->createMock(TaskRepositoryInterface::class);

    $repo->expects($this->once())->method('save')->with($this->isInstanceOf(Task::class))->willReturnCallback(fn(Task $t) => $t);

    $controller = new TaskController($repo, $this->createMock(\Psr\Log\LoggerInterface::class));

    $request = $this->createMock(Request::class);
    $request->method('getParsedBody')->willReturn(['title' => 'hello', 'description' => 'world']);

    $response = new Response();

    $res = $controller->create($request, $response);

    $this->assertSame(201, $res->getStatusCode());
    $this->assertStringContainsString('hello', (string)$res->getBody());
  }

  public function testUpdateStatusInvalidReturns422(): void
  {
    $repo = $this->createMock(TaskRepositoryInterface::class);
    $controller = new TaskController($repo, $this->createMock(\Psr\Log\LoggerInterface::class));

    $request = $this->createMock(Request::class);
    $request->method('getParsedBody')->willReturn(['status' => 'invalid_status']);

    $response = new Response();

    $res = $controller->updateStatus($request, $response, ['id' => '1']);

    $this->assertSame(422, $res->getStatusCode());
    $this->assertStringContainsString('status must', (string)$res->getBody());
  }

  public function testUpdateStatusNotFoundReturns404(): void
  {
    $repo = $this->createMock(TaskRepositoryInterface::class);
    $repo->method('findById')->willReturn(null);

    $controller = new TaskController($repo, $this->createMock(\Psr\Log\LoggerInterface::class));

    $request = $this->createMock(Request::class);
    $request->method('getParsedBody')->willReturn(['status' => 'completed']);

    $response = new Response();

    $res = $controller->updateStatus($request, $response, ['id' => '5']);

    $this->assertSame(404, $res->getStatusCode());
    $this->assertStringContainsString('task not found', (string)$res->getBody());
  }

  public function testUpdateStatusSuccessReturnsTask(): void
  {
    $task = new Task('t', 'd');

    $repo = $this->createMock(TaskRepositoryInterface::class);
    $repo->method('findById')->willReturn($task);
    $repo->expects($this->once())->method('update')->with($this->isInstanceOf(Task::class))->willReturnCallback(fn(Task $t) => $t);

    $controller = new TaskController($repo, $this->createMock(\Psr\Log\LoggerInterface::class));

    $request = $this->createMock(Request::class);
    $request->method('getParsedBody')->willReturn(['status' => 'completed']);

    $response = new Response();

    $res = $controller->updateStatus($request, $response, ['id' => '2']);

    $this->assertSame(200, $res->getStatusCode());
    $this->assertStringContainsString('completed', (string)$res->getBody());
  }

  public function testDeleteNotFoundReturns404(): void
  {
    $repo = $this->createMock(TaskRepositoryInterface::class);
    $repo->method('findById')->willReturn(null);

    $controller = new TaskController($repo, $this->createMock(\Psr\Log\LoggerInterface::class));

    $request = $this->createMock(Request::class);
    $response = new Response();

    $res = $controller->delete($request, $response, ['id' => '9']);

    $this->assertSame(404, $res->getStatusCode());
    $this->assertStringContainsString('task not found', (string)$res->getBody());
  }

  public function testDeleteSuccessReturns204(): void
  {
    $task = new Task('t', 'd');

    $repo = $this->createMock(TaskRepositoryInterface::class);
    $repo->method('findById')->willReturn($task);
    $repo->expects($this->once())->method('delete')->with($this->isInstanceOf(Task::class));

    $controller = new TaskController($repo, $this->createMock(\Psr\Log\LoggerInterface::class));

    $request = $this->createMock(Request::class);
    $response = new Response();

    $res = $controller->delete($request, $response, ['id' => '3']);

    $this->assertSame(204, $res->getStatusCode());
  }
}
