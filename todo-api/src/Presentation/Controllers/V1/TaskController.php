<?php

declare(strict_types=1);

namespace App\Presentation\Controllers\V1;

use Psr\Log\LoggerInterface;
use Slim\App;
use Slim\Psr7\Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Application\UseCases\ListTasksUseCase;
use App\Application\UseCases\CreateTaskUseCase;
use App\Application\UseCases\UpdateTaskStatusUseCase;
use App\Application\UseCases\DeleteTaskUseCase;
use App\Domain\Interfaces\TaskRepositoryInterface;

class TaskController
{
    public function __construct(private TaskRepositoryInterface $repo) {}

    public function registerRoutes(App $app): void
    {
        $app->get('/api/v1/tasks', [$this, 'list']);
        $app->post('/api/v1/tasks', [$this, 'create']);
        $app->patch('/api/v1/tasks/{id}', [$this, 'updateStatus']);
        $app->delete('/api/v1/tasks/{id}', [$this, 'delete']);
    }

    public function list(Request $request, Response $response): Response
    {
        $useCase = new ListTasksUseCase($this->repo);
        $tasks = $useCase->execute();
        $payload = array_map(fn($t) => method_exists($t, 'toArray') ? $t->toArray() : (array)$t, $tasks);
        $response->getBody()->write(json_encode($payload));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function create(Request $request, Response $response): Response
    {
        $data = (array)$request->getParsedBody();
        $errors = [];
        if (!isset($data['title']) || trim((string)$data['title']) === '') {
            $errors['title'] = 'title is required';
        }
        if (!isset($data['description']) || trim((string)$data['description']) === '') {
            $errors['description'] = 'description is required';
        }

        if (!empty($errors)) {
            $response->getBody()->write(json_encode(['error' => $errors]));
            return $response->withStatus(422)->withHeader('Content-Type', 'application/json');
        }

        $useCase = new CreateTaskUseCase($this->repo);
        $task = $useCase->execute((string)$data['title'], (string)$data['description']);

        $response->getBody()->write(json_encode($task->toArray()));
        return $response->withStatus(201)->withHeader('Content-Type', 'application/json');
    }

    public function updateStatus(Request $request, Response $response, array $args): Response
    {
        $id = (int)$args['id'];
        $data = (array)$request->getParsedBody();
        $status = isset($data['status']) ? trim((string)$data['status']) : 'pending';

        $allowed = ['pending', 'completed'];
        if (!in_array($status, $allowed, true)) {
            $response->getBody()->write(json_encode(['error' => ['status' => 'status must be "pending" or "completed"']]));
            return $response->withStatus(422)->withHeader('Content-Type', 'application/json');
        }

        $useCase = new UpdateTaskStatusUseCase($this->repo);

        $task = $useCase->execute($id, $status);

        if ($task === null) {
            $response->getBody()->write(json_encode(['error' => ['msg' => 'task not found']]));
            return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
        }

        $response->getBody()->write(json_encode($task->toArray()));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function delete(Request $request, Response $response, array $args): Response
    {
        $id = (int)$args['id'];

        $useCase = new DeleteTaskUseCase($this->repo);
        $deleted = $useCase->execute($id);

        if (!$deleted) {
            $response->getBody()->write(json_encode(['error' => ['msg' => 'task not found']]));
            return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
        }

        return $response->withStatus(204);
    }
}
