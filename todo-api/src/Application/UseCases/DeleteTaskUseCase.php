<?php

declare(strict_types=1);

namespace App\Application\UseCases;

use App\Domain\Interfaces\TaskRepositoryInterface;

class DeleteTaskUseCase
{
    public function __construct(private TaskRepositoryInterface $repo) {}

    public function execute(int $id): bool
    {
        $task = $this->repo->findById($id);
        if ($task === null) {
            return false;
        }

        $this->repo->delete($task);
        return true;
    }
}
