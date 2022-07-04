<?php
declare(strict_types=1);

namespace App\Test;

use PHPUnit\Framework\TestCase;
use App\Filter;

class FilterTest extends TestCase
{
    public function testValidEmail()
    {
        $filter = new Filter();
        $this->assertTrue($filter->isEmail('foo@bar.com'));
    }

    public function testInvalidEmail()
    {
        $filter = new Filter();
        $this->assertFalse($filter->isEmail('foo'));
    }
}
