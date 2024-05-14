<?php
declare(strict_types=1);

namespace App\Test\Repository\Peripheral;

use App\Models\GenericObject;
use App\Repository\GenericObjectRepository;
use App\Test\Repository\RepositoryTestUtil;
use PDO;
use PHPUnit\Framework\TestCase;
use AbstractRepo\Exceptions\RepositoryException as AbstractRepositoryException;
use App\Models\Peripheral\Peripheral;
use App\Models\Peripheral\PeripheralType;
use App\Repository\Peripheral\PeripheralRepository;
use App\Repository\Peripheral\PeripheralTypeRepository;

final class PeripheralRepositoryTest extends TestCase
{
    public static ?PDO $pdo;
    public static GenericObject $sampleGenericObject;
    public static PeripheralType $samplePeripheralType;
    public static Peripheral $samplePeripheral;

    public static GenericObjectRepository $genericObjectRepository;
    public static PeripheralTypeRepository $peripheralTypeRepository;
    public static PeripheralRepository $peripheralRepository;

    public static function setUpBeforeClass(): void
    {
        self::$pdo = RepositoryTestUtil::getTestPdo();
        self::$pdo = RepositoryTestUtil::dropTestDB(self::$pdo);
        self::$pdo = RepositoryTestUtil::createTestDB(self::$pdo);

        self::$peripheralTypeRepository = new PeripheralTypeRepository(self::$pdo);
        self::$genericObjectRepository = new GenericObjectRepository(self::$pdo);
        self::$peripheralRepository = new PeripheralRepository(self::$pdo,);

        self::$sampleGenericObject = new GenericObject('objID');

        self::$samplePeripheralType = new PeripheralType(
            "Mouse",
            1
        );

        self::$samplePeripheral = new Peripheral(
            self::$sampleGenericObject,
            "Peripheral 1.0",
            self::$samplePeripheralType
        );

        self::$peripheralTypeRepository->save(self::$samplePeripheralType);
    }

    public function setUp(): void
    {
        //Peripheral saved to test duplicated supports errors
        self::$genericObjectRepository->save(self::$sampleGenericObject);
        self::$peripheralRepository->save(self::$samplePeripheral);
    }

    public function tearDown(): void
    {
        //Clear the table
        self::$pdo->exec("SET FOREIGN_KEY_CHECKS=0; TRUNCATE TABLE Peripheral; TRUNCATE TABLE GenericObject; SET FOREIGN_KEY_CHECKS=1;");
    }

    //INSERT TESTS
    public function testGoodInsert(): void
    {
        $genericObject = clone self::$sampleGenericObject;
        $genericObject->id = "objID2";

        $peripheral = clone self::$samplePeripheral;
        $peripheral->genericObject = $genericObject;
        $peripheral->modelName = "Peripheral 2";

        self::$genericObjectRepository->save($genericObject);
        self::$peripheralRepository->save($peripheral);

        $this->assertEquals(self::$peripheralRepository->findById("objID2")->modelName, "Peripheral 2");
    }

    public function testBadInsert(): void
    {
        $this->expectException(AbstractRepositoryException::class);
        //Peripheral already saved in the setUp() method
        self::$peripheralRepository->save(self::$samplePeripheral);
    }

    //SELECT TESTS
    public function testGoodSelectById(): void
    {
        $this->assertNotNull(self::$peripheralRepository->findById("objID"));
    }

    public function testBadSelectById(): void
    {
        $this->assertNull(self::$peripheralRepository->findById("WRONGID"));
    }

    public function testGoodSelectAll(): void
    {
        for ($i = 0 ; $i < 3; $i++) {
            $genericObject = clone self::$sampleGenericObject;
            $genericObject->id = "objID" . $i;

            $peripheral = clone self::$samplePeripheral;
            $peripheral->genericObject = $genericObject;

            self::$genericObjectRepository->save($genericObject);
            self::$peripheralRepository->save($peripheral);
        }

        $peripherals = self::$peripheralRepository->find();
        $this->assertEquals(count($peripherals), 4);
        $this->assertNotNull($peripherals[1]);
    }

    public function testGoodSelectByKey(): void
    {
        $genericObject = clone self::$sampleGenericObject;
        $genericObject->id = "objID5";

        $peripheral = clone self::$samplePeripheral;
        $peripheral->genericObject = $genericObject;
        $peripheral->modelName = "Peripheral Test";

        self::$genericObjectRepository->save($genericObject);
        self::$peripheralRepository->save($peripheral);

        $this->assertEquals(count(self::$peripheralRepository->findByQuery("mous")), 2);
    }

    public function testBadSelectByKey(): void
    {
        $this->assertEquals(self::$peripheralRepository->findByQuery("wrongkey"), []);
    }

    //UPDATE TESTS
    public function testGoodUpdate(): void
    {
        $peripheral = clone self::$samplePeripheral;
        $peripheral->modelName = "NEW MODELNAME";

        self::$peripheralRepository->update($peripheral);

        $this->assertEquals("NEW MODELNAME", self::$peripheralRepository->findById("objID")->modelName);
    }

    //DELETE TESTS
    public function testGoodDelete(): void
    {
        self::$peripheralRepository->delete("objID");

        $this->assertNull(self::$peripheralRepository->findById("objID"));
    }

    public static function tearDownAfterClass(): void
    {
        self::$pdo = RepositoryTestUtil::dropTestDB(self::$pdo);
        self::$pdo = null;
    }
}