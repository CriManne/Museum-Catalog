<?php

declare(strict_types=1);

namespace App\Test\Repository;

use PDO;
use PHPUnit\Framework\TestCase;
use App\SearchEngine\ComponentSearchEngine;
use App\Exception\RepositoryException;
use App\Model\Book\Publisher;
use App\Model\Computer\Computer;
use App\Model\Computer\Cpu;
use App\Model\Computer\Os;
use App\Model\Computer\Ram;
use App\Model\Magazine\Magazine;
use App\Repository\Book\AuthorRepository;
use App\Repository\Book\BookAuthorRepository;
use App\Repository\Book\BookRepository;
use App\Repository\Book\PublisherRepository;
use App\Repository\Computer\ComputerRepository;
use App\Repository\Computer\CpuRepository;
use App\Repository\Computer\OsRepository;
use App\Repository\Computer\RamRepository;
use App\Repository\Magazine\MagazineRepository;
use App\Repository\Peripheral\PeripheralRepository;
use App\Repository\Peripheral\PeripheralTypeRepository;
use App\Repository\Software\SoftwareRepository;
use App\Repository\Software\SoftwareTypeRepository;
use App\Repository\Software\SupportTypeRepository;
use App\Util\DIC;

final class ComponentSearchEngineTest extends TestCase
{
    public static ComponentSearchEngine $componentSearchEngine;
    public static OsRepository $osRepository;
    public static CpuRepository $cpuRepository;
    public static RamRepository $ramRepository;

    public static ?PDO $pdo;

    public static function setUpBeforeClass(): void
    {
        self::$pdo = RepositoryTestUtil::getTestPdo();

        self::$pdo = RepositoryTestUtil::dropTestDB(self::$pdo);
        self::$pdo = RepositoryTestUtil::createTestDB(self::$pdo);

        self::$cpuRepository = new CpuRepository(self::$pdo);
        self::$ramRepository = new RamRepository(self::$pdo);
        self::$osRepository = new OsRepository(self::$pdo);

        $cpu = new Cpu("I7", "4GHZ", 1);
        $ram = new Ram("Ram 1", "64GB", 1);
        $cpu2 = new Cpu("I9", "6GHZ", 2);
        $ram2 = new Ram("Ram 2", "128GB", 2);
        $cpu3 = new Cpu("I5", "8GHZ", 3);
        $ram3 = new Ram("Ram 3", "32GB", 3);

        self::$cpuRepository->insert($cpu);
        self::$ramRepository->insert($ram);
        self::$cpuRepository->insert($cpu2);
        self::$ramRepository->insert($ram2);
        self::$cpuRepository->insert($cpu3);
        self::$ramRepository->insert($ram3);

        self::$componentSearchEngine = new ComponentSearchEngine(
            "config/test_container.php"
        );
    }

    public function testGoodSelectAll(): void
    {
        $this->assertEquals(count(self::$componentSearchEngine->select("Cpu")), 3);
    }

    public function testBadSelectAll(): void
    {
        $this->expectException(RepositoryException::class);
        self::$componentSearchEngine->select("Bad category");
    }

    public function testGoodSelectByQuery(): void
    {
        $result = self::$componentSearchEngine->select("Ram", "gb");
        $this->assertEquals(3, count($result));
    }

    public function testGoodSelectByQuery2(): void
    {
        $result = self::$componentSearchEngine->select("Cpu", "hz");
        $this->assertEquals(3, count($result));
    }

    public function testBadSelectByQuery(): void
    {
        $result = self::$componentSearchEngine->select("Cpu", "WRONG_SEARCH");
        $this->assertEquals(0, count($result));
    }

    public static function tearDownAfterClass(): void
    {
        self::$pdo = RepositoryTestUtil::dropTestDB(self::$pdo);
        self::$pdo = null;
    }
}
