<?php

declare(strict_types=1);

namespace App\Test\Service;

use App\Service\UserService;
use PDO;
use PDOStatement;
use PHPUnit\Framework\TestCase;

abstract class BaseServiceTest extends TestCase
{
    public mixed $sampleGenericObject;
    public mixed $sampleObject;
    public mixed $sampleResponse;
}