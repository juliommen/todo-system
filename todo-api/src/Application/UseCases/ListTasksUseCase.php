<?php

declare(strict_types=1);

namespace App\Application\UseCases;

use App\Domain\Interfaces\TaskRepositoryInterface;

class ListTasksUseCase
{
    public function __construct(private TaskRepositoryInterface $repo) {}

    /** @return Task[] */
    public function execute(): array
    {
        return $this->repo->listAll();
    }
}
