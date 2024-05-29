<?php

declare(strict_types=1);

namespace App\Test\SearchEngine;

use App\Models\GenericObject;
use App\Repository\GenericObjectRepository;
use App\Test\Repository\BaseRepositoryTest;
use App\SearchEngine\ArtifactSearchEngine;
use App\Exception\ServiceException;
use App\Models\Book\Publisher;
use App\Models\Computer\Computer;
use App\Models\Computer\Cpu;
use App\Models\Computer\Os;
use App\Models\Computer\Ram;
use App\Models\Magazine\Magazine;
use App\Repository\Book\BookRepository;
use App\Repository\Book\PublisherRepository;
use App\Repository\Computer\ComputerRepository;
use App\Repository\Computer\CpuRepository;
use App\Repository\Computer\OsRepository;
use App\Repository\Computer\RamRepository;
use App\Repository\Magazine\MagazineRepository;
use App\Repository\Peripheral\PeripheralRepository;
use App\Repository\Software\SoftwareRepository;

final class SearchArtifactEngineTest extends BaseRepositoryTest
{
    public static GenericObjectRepository $genericObjectRepository;
    public static ArtifactSearchEngine    $artifactSearchEngine;
    public static SoftwareRepository      $softwareRepository;
    public static ComputerRepository      $computerRepository;
    public static BookRepository          $bookRepository;
    public static MagazineRepository      $magazineRepository;
    public static OsRepository            $osRepository;
    public static CpuRepository           $cpuRepository;
    public static RamRepository           $ramRepository;
    public static PeripheralRepository    $peripheralRepository;
    public static PublisherRepository     $publisherRepository;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        self::$genericObjectRepository = new GenericObjectRepository(self::$pdo);
        self::$softwareRepository      = new SoftwareRepository(self::$pdo);
        self::$cpuRepository           = new CpuRepository(self::$pdo);
        self::$ramRepository           = new RamRepository(self::$pdo);
        self::$osRepository            = new OsRepository(self::$pdo);
        self::$computerRepository      = new ComputerRepository(self::$pdo);
        self::$bookRepository          = new BookRepository(self::$pdo);
        self::$publisherRepository     = new PublisherRepository(self::$pdo);
        self::$magazineRepository      = new MagazineRepository(self::$pdo);
        self::$peripheralRepository    = new PeripheralRepository(self::$pdo);

        self::$artifactSearchEngine = new ArtifactSearchEngine();

        $genericObject1 = new GenericObject(
            id: 'OBJ1',
            note: null,
            url: null,
            tag: null
        );

        $genericObject2 = new GenericObject(
            id: 'OBJ2',
            note: null,
            url: null,
            tag: null
        );

        $cpu       = new Cpu("I7", "4GHZ", 1);
        $ram       = new Ram("Ram 1", "64GB", 1);
        $os        = new Os("Windows 10", 1);
        $publisher = new Publisher("Einaudi", 1);

        self::$cpuRepository->save($cpu);
        self::$ramRepository->save($ram);
        self::$osRepository->save($os);

        self::$genericObjectRepository->save($genericObject1);
        self::$computerRepository->save(new Computer(
            $genericObject1,
            "Computer1",
            2018,
            "1TB",
            $cpu,
            $ram,
            $os,
        ));

        self::$publisherRepository->save($publisher);

        self::$genericObjectRepository->save($genericObject2);

        self::$magazineRepository->save(new Magazine(
            $genericObject2,
            "Compass",
            2017,
            23,
            $publisher
        ));
    }

    //SELECT TESTS
    public function testGoodSelectGenericById(): void
    {
        $obj = self::$artifactSearchEngine->selectGenericById("OBJ1");
        $this->assertEquals(
            [
                "Year"     => 2018,
                "Hdd size" => "1TB",
                "Cpu"      => "I7 4GHZ",
                "Ram"      => "Ram 1 64GB",
                "Os"       => "Windows 10"
            ],
            $obj->descriptors
        );
        $this->assertEquals("Computer1", $obj->title);
    }


    public function testBadSelectGenericById(): void
    {
        $this->expectException(ServiceException::class);
        self::$artifactSearchEngine->selectGenericById("wrong");
    }

    public function testGoodSelectSpecificByIdAndCategory(): void
    {
        $obj = self::$artifactSearchEngine->selectSpecificByIdAndCategory("OBJ1", "Computer");
        $this->assertEquals(2018, $obj->year);
        $this->assertEquals("Computer1", $obj->modelName);
    }

    public function testBadSelectSpecificByIdAndCategory(): void
    {
        $this->expectException(ServiceException::class);
        self::$artifactSearchEngine->selectSpecificByIdAndCategory("OBJ1", "wrong-category");
    }

    public function testBadSelectSpecificByIdAndCategory2(): void
    {
        $this->expectException(ServiceException::class);
        self::$artifactSearchEngine->selectSpecificByIdAndCategory("wrong", "Computer");
    }

    public function testGoodSelectAll(): void
    {
        $this->assertEquals(count(self::$artifactSearchEngine->selectGenerics()), 2);
    }

    public function testGoodSelectByQuery(): void
    {
        $result = self::$artifactSearchEngine->selectGenerics(null, "cOmP");
        $this->assertEquals(2, count($result));
    }

    public function testGoodSelectByQueryWithCategory(): void
    {
        $result = self::$artifactSearchEngine->selectGenerics("Computer", "cOmP");
        $this->assertEquals(1, count($result));
    }

    public function testBadSelectByQuery(): void
    {
        $result = self::$artifactSearchEngine->selectGenerics("WRONG", null);
        $this->assertEquals(0, count($result));
    }

    public function testBadSelectByQueryWithWrongCategory(): void
    {
        $result = self::$artifactSearchEngine->selectGenerics("magazine", "comp");
        $this->assertEquals(0, count($result));
    }
}
