<?php

declare(strict_types=1);

namespace App\Domain\Interfaces;

use App\Domain\Entities\Task;

interface TaskRepositoryInterface
{
    /** @return Task[] */
    public function listAll(): array;

    public function save(Task $task): Task;

    public function findById(int $id): ?Task;

    public function update(Task $task): Task;

    public function delete(Task $task): void;
}
