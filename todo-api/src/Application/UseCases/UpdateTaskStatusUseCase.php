<?php

declare(strict_types=1);

namespace App\Application\UseCases;

use App\Domain\Entities\Task;
use App\Domain\Interfaces\TaskRepositoryInterface;

class UpdateTaskStatusUseCase
{
    public function __construct(private TaskRepositoryInterface $repo) {}

    public function execute(int $id, string $status): ?Task
    {
        $task = $this->repo->findById($id);
        if ($task === null) {
            return null;
        }

        if ($status === 'completed') {
            $task->markCompleted();
        }

        if ($status === 'pending') {
            $task->markPending();
        }

        return $this->repo->update($task);
    }
}
