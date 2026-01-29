<?php

declare(strict_types=1);

namespace App\Application\UseCases;

use App\Domain\Entities\Task;
use App\Domain\Interfaces\TaskRepositoryInterface;

class CreateTaskUseCase
{
    public function __construct(private TaskRepositoryInterface $repo) {}

    public function execute(string $title, string $description): Task
    {
        $task = new Task($title, $description);
        return $this->repo->save($task);
    }
}
