<?php

declare(strict_types=1);

namespace App\Test\Repository;

use PDO;
use PHPUnit\Framework\TestCase;
use App\Repository\GenericObjectRepository;
use App\Exception\RepositoryException;
use App\Model\Computer\Computer;
use App\Model\Computer\Cpu;
use App\Model\Computer\Os;
use App\Model\Computer\Ram;
use App\Model\Software\Software;
use App\Model\Software\SoftwareType;
use App\Model\Software\SupportType;
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

final class GenericObjectRepositoryTest extends TestCase {
    public static GenericObjectRepository $genericObjectRepository;
    public static SoftwareRepository $softwareRepository;
    public static ComputerRepository $computerRepository;
    public static BookRepository $bookRepository;
    public static MagazineRepository $magazineRepository;
    public static OsRepository $osRepository;
    public static CpuRepository $cpuRepository;
    public static RamRepository $ramRepository;
    public static PeripheralRepository $peripheralRepository;

    public static ?PDO $pdo;

    public static function setUpBeforeClass(): void {
        self::$pdo = RepositoryTestUtil::getTestPdo();

        self::$pdo = RepositoryTestUtil::dropTestDB(self::$pdo);
        self::$pdo = RepositoryTestUtil::createTestDB(self::$pdo);            

        self::$softwareRepository = new SoftwareRepository(
            self::$pdo,
            new SoftwareTypeRepository(self::$pdo),
            new SupportTypeRepository(self::$pdo),
            new OsRepository(self::$pdo)
        );

        self::$cpuRepository = new CpuRepository(self::$pdo);
        self::$ramRepository = new RamRepository(self::$pdo);
        self::$osRepository = new OsRepository(self::$pdo);

        self::$computerRepository = new ComputerRepository(
            self::$pdo,
            self::$cpuRepository,
            self::$ramRepository,
            self::$osRepository
        );

        self::$bookRepository = new BookRepository(
            self::$pdo,
            new PublisherRepository(self::$pdo),
            new AuthorRepository(self::$pdo),
            new BookAuthorRepository(self::$pdo)
        );

        self::$magazineRepository = new MagazineRepository(
            self::$pdo,
            new PublisherRepository(self::$pdo)
        );

        self::$peripheralRepository = new PeripheralRepository(
            self::$pdo,
            new PeripheralTypeRepository(self::$pdo)
        );

        self::$genericObjectRepository = new GenericObjectRepository(
            self::$pdo,
            self::$softwareRepository,
            self::$computerRepository,
            self::$bookRepository,
            self::$peripheralRepository,
            self::$magazineRepository            
        );         

        self::$cpuRepository->insert(new Cpu(1, "I7", "4GHZ"));
        self::$ramRepository->insert(new Ram(1, "Ram 1", "64GB"));
        self::$osRepository->insert(new Os(1, "Windows 10"));

        self::$computerRepository->insert(new Computer(
            "OBJ1",
            null,
            null,
            null,
            "",
            null,
            "Computer1",
            2018,
            "1TB",
            new Cpu(1, "I7", "4GHZ"),
            new Ram(1, "Ram 1", "64GB"),
            new Os(1, "Windows 10")
        ));
    }

    //SELECT TESTS
    public function testGoodSelectByIdComputer(): void {
        $obj = self::$genericObjectRepository->selectById("OBJ1");
        $this->assertEquals(
            [
                "Year"=>"2018",
                "Hdd size"=>"1TB",
                "Cpu" => "I7 4GHZ",
                "Ram" => "Ram 1 64GB",
                "Os" => "Windows 10"
            ],
            $obj->Descriptors
        );
        $this->assertEquals("Computer1", $obj->Title);
    }

    public function testBadSelectById(): void {
        $this->assertNull(self::$genericObjectRepository->selectById("wrong"));
    }

    public static function tearDownAfterClass(): void {
        self::$pdo = RepositoryTestUtil::dropTestDB(self::$pdo);
        self::$pdo = null;
    }
}
