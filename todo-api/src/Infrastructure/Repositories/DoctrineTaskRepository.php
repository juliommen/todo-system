<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\Entities\Task;
use App\Domain\Interfaces\TaskRepositoryInterface;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;

class DoctrineTaskRepository implements TaskRepositoryInterface
{
    public function __construct(private EntityManager $em) {}

    public static function create(): EntityManager
    {
        $paths = [__DIR__ . '/../../../Domain/Entities'];
        $isDevMode = true;

        $config = ORMSetup::createAttributeMetadataConfiguration($paths, $isDevMode);

        $connection = DriverManager::getConnection([
            'driver' => getenv('DB_DRIVER'),
            'host' => getenv('DB_HOST'),
            'port' => getenv('DB_PORT'),
            'dbname' => getenv('DB_NAME'),
            'user' => getenv('DB_USER'),
            'password' => getenv('DB_PASSWORD'),
        ], $config);

        return new EntityManager($connection, $config);
    }


    /** @return Task[] */
    public function listAll(): array
    {
        return $this->em->getRepository(Task::class)->findAll();
    }

    public function save(Task $task): Task
    {
        $this->em->persist($task);
        $this->em->flush();
        return $task;
    }

    public function findById(int $id): ?Task
    {
        return $this->em->find(Task::class, $id);
    }

    public function update(Task $task): Task
    {
        $this->em->persist($task);
        $this->em->flush();
        return $task;
    }

    public function delete(Task $task): void
    {
        $this->em->remove($task);
        $this->em->flush();
    }
}
