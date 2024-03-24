<?php

declare(strict_types=1);

namespace App\Test\Service;

use App\Service\UserService;
use PDO;
use PDOStatement;
use PHPUnit\Framework\TestCase;

class BaseServiceTest extends TestCase
{
    public PDO $pdo;
    public PDOStatement $sth;
    public UserService $userService;
    public mixed $sampleObject;
    public mixed $sampleObjectRaw;
    public mixed $sampleResponse;

    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->pdo = $this->createMock(PDO::class);
        $this->sth = $this->createMock(PDOStatement::class);
        $this->pdo->method('prepare')->willReturn($this->sth);
        $this->sth->method('execute')->willReturn(true);
    }
}