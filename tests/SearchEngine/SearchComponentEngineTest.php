<?php

declare(strict_types=1);

namespace App\Test\SearchEngine;

use App\Test\Repository\RepositoryTestUtil;
use PDO;
use PHPUnit\Framework\TestCase;
use App\SearchEngine\ComponentSearchEngine;
use App\Exception\ServiceException;
use App\Models\Computer\Cpu;
use App\Models\Computer\Ram;
use App\Repository\Computer\CpuRepository;
use App\Repository\Computer\OsRepository;
use App\Repository\Computer\RamRepository;

final class SearchComponentEngineTest extends TestCase
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

        self::$cpuRepository->save($cpu);
        self::$ramRepository->save($ram);
        self::$cpuRepository->save($cpu2);
        self::$ramRepository->save($ram2);
        self::$cpuRepository->save($cpu3);
        self::$ramRepository->save($ram3);

        self::$componentSearchEngine = new ComponentSearchEngine(
            "config/test_container.php"
        );
    }

    public function testGoodSelectSpecificByIdAndCategory():void{
        $obj = self::$componentSearchEngine->selectSpecificByIdAndCategory(1,"App\\Service\\Computer\\CpuService");
        $this->assertEquals("4GHZ",$obj->speed);
    }

    public function testBadSelectSpecificByIdAndCategory(): void
    {
        $this->expectException(ServiceException::class);
        self::$componentSearchEngine->selectSpecificByIdAndCategory(1,"App\\Service\\Computer\\WRONG");
    }    

    public function testGoodSelectAll(): void
    {
        $this->assertEquals(count(self::$componentSearchEngine->selectGenerics("Cpu")), 3);
    }

    public function testBadSelectAll(): void
    {
        $this->expectException(ServiceException::class);
        self::$componentSearchEngine->selectGenerics("Bad category");
    }

    public function testGoodSelectByQuery(): void
    {
        $result = self::$componentSearchEngine->selectGenerics("Ram", "gb");
        $this->assertEquals(3, count($result));
    }

    public function testGoodSelectByQuery2(): void
    {
        $result = self::$componentSearchEngine->selectGenerics("Cpu", "hz");
        $this->assertEquals(3, count($result));
    }

    public function testBadSelectByQuery(): void
    {
        $result = self::$componentSearchEngine->selectGenerics("Cpu", "WRONG_SEARCH");
        $this->assertEquals(0, count($result));
    }

    public static function tearDownAfterClass(): void
    {
        self::$pdo = RepositoryTestUtil::dropTestDB(self::$pdo);
        self::$pdo = null;
    }
}
