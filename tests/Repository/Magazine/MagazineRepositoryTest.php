<?php
declare(strict_types=1);

namespace App\Test\Repository\Magazine;

use App\Model\GenericObject;
use App\Repository\GenericObjectRepository;
use App\Test\Repository\RepositoryTestUtil;
use PDO;
use PHPUnit\Framework\TestCase;

use App\Exception\RepositoryException;
use App\Model\Magazine\Magazine;
use App\Model\Book\Publisher;
use App\Repository\Book\PublisherRepository;
use App\Repository\Magazine\MagazineRepository;

final class MagazineRepositoryTest extends TestCase
{
    public static ?PDO $pdo;
    public static Publisher $samplePublisher;
    public static Magazine $sampleMagazine;
    public static GenericObject $sampleGenericObject;

    public static GenericObjectRepository $genericObjectRepository;
    public static PublisherRepository $publisherRepository;
    public static MagazineRepository $magazineRepository;

    public static function setUpBeforeClass(): void
    {
        self::$pdo = RepositoryTestUtil::getTestPdo();
        self::$pdo = RepositoryTestUtil::dropTestDB(self::$pdo);
        self::$pdo = RepositoryTestUtil::createTestDB(self::$pdo);

        // Repository to handle relations
        self::$publisherRepository = new PublisherRepository(self::$pdo);
        self::$genericObjectRepository = new GenericObjectRepository(self::$pdo);

        // Repository to handle magazine
        self::$magazineRepository = new MagazineRepository(
            self::$pdo,
            self::$publisherRepository
        );

        self::$sampleGenericObject = new GenericObject(
            "objID"
        );

        self::$samplePublisher = new Publisher(
            "Einaudi",
            1
        );

        self::$sampleMagazine = new Magazine(
            self::$sampleGenericObject,
            "Magazine 1.0",
            2005,
            10492,
            self::$samplePublisher
        );

        self::$publisherRepository->save(self::$samplePublisher);
    }

    public function setUp(): void
    {
        //Magazine saved to test duplicated supports errors
        self::$genericObjectRepository->save(self::$sampleGenericObject);
        self::$magazineRepository->save(self::$sampleMagazine);
    }

    public function tearDown(): void
    {
        //Clear the table
        self::$pdo->exec("SET FOREIGN_KEY_CHECKS=0; TRUNCATE TABLE Magazine; TRUNCATE TABLE GenericObject; SET FOREIGN_KEY_CHECKS=1;");
    }

    //INSERT TESTS
    public function testGoodInsert(): void
    {
        $magazine = clone self::$sampleMagazine;
        $genericObject = new GenericObject("objID2");

        self::$genericObjectRepository->save($genericObject);
        $magazine->genericObject = $genericObject;
        $magazine->title = "Magazine 2";

        self::$magazineRepository->save($magazine);

        $this->assertEquals(self::$magazineRepository->findById("objID2")->title, "Magazine 2");
    }

    public function testBadInsert(): void
    {
        $this->expectException(\AbstractRepo\Exceptions\RepositoryException::class);
        //Magazine already saved in the setUp() method
        self::$magazineRepository->save(self::$sampleMagazine);
    }

    //SELECT TESTS
    public function testGoodSelectById(): void
    {
        $this->assertNotNull(self::$magazineRepository->findById("objID"));
    }

    public function testBadSelectById(): void
    {
        $this->assertNull(self::$magazineRepository->findById("WRONGID"));
    }

    public function testGoodSelectAll(): void
    {
        for ($i = 1; $i < 4; $i++) {
            $genericObject = clone self::$sampleGenericObject;

            $genericObject->id = "objID" . $i;

            self::$genericObjectRepository->save($genericObject);

            $magazine = clone self::$sampleMagazine;
            $magazine->genericObject = $genericObject;

            self::$magazineRepository->save($magazine);
        }

        $magazines = self::$magazineRepository->find();

        $this->assertEquals(count($magazines), 4);
        $this->assertNotNull($magazines[1]);
    }

    public function testGoodSelectByTitle(): void
    {
        $genericObject = clone self::$sampleGenericObject;

        $genericObject->id = "objID1";

        self::$genericObjectRepository->save($genericObject);

        $magazine = clone self::$sampleMagazine;
        $magazine->genericObject = $genericObject;
        $magazine->title = "Magazine Test";
        
        self::$magazineRepository->save($magazine);

        $this->assertEquals(self::$magazineRepository->findByTitle("Magazine Test")->title, "Magazine Test");
    }

    public function testGoodSelectByKey(): void
    {
        $genericObject = clone self::$sampleGenericObject;

        $genericObject->id = "objID1";

        self::$genericObjectRepository->save($genericObject);

        $magazine = clone self::$sampleMagazine;
        $magazine->genericObject = $genericObject;
        $magazine->title = "Magazine Test";
        
        self::$magazineRepository->save($magazine);

        $this->assertEquals(count(self::$magazineRepository->findByQuery("maGazIn")), 2);
    }

    public function testBadSelectByKey(): void
    {
        $this->assertEquals(self::$magazineRepository->findByQuery("wrongkey"), []);
    }

    //UPDATE TESTS
    public function testGoodUpdate(): void
    {
        $genericObject = clone self::$sampleGenericObject;

        $genericObject->id = "objID1";

        self::$genericObjectRepository->save($genericObject);

        $magazine = clone self::$sampleMagazine;
        $magazine->title = "NEW TITLE";
        $magazine->genericObject = $genericObject;

        var_dump($magazine);
        self::$magazineRepository->update($magazine);

        $this->assertEquals("NEW TITLE", self::$magazineRepository->findById("objID")->title);
    }

    //DELETE TESTS
    public function testGoodDelete(): void
    {
        self::$magazineRepository->delete("objID");

        $this->assertNull(self::$magazineRepository->findById("objID"));
    }

    public static function tearDownAfterClass(): void
    {
        self::$pdo = RepositoryTestUtil::dropTestDB(self::$pdo);
        self::$pdo = null;
    }
}