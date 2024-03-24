<?php
declare(strict_types=1);

namespace App\Test\Repository\Peripheral;

use App\Test\Repository\RepositoryTestUtil;
use PDO;
use PHPUnit\Framework\TestCase;

use App\Exception\RepositoryException;
use App\Model\Peripheral\Peripheral;
use App\Model\Peripheral\PeripheralType;
use App\Repository\Peripheral\PeripheralRepository;
use App\Repository\Peripheral\PeripheralTypeRepository;

final class PeripheralRepositoryTest extends TestCase
{
    public static ?PDO $pdo;
    public static PeripheralType $samplePeripheralType;
    public static Peripheral $samplePeripheral;

    public static PeripheralTypeRepository $peripheralTypeRepository;
    public static PeripheralRepository $peripheralRepository;

    public static function setUpBeforeClass(): void
    {
        self::$pdo = RepositoryTestUtil::getTestPdo();
        self::$pdo = RepositoryTestUtil::dropTestDB(self::$pdo);
        self::$pdo = RepositoryTestUtil::createTestDB(self::$pdo);

        // Repository to handle relations
        self::$peripheralTypeRepository = new PeripheralTypeRepository(self::$pdo);

        // Repository to handle peripheral
        self::$peripheralRepository = new PeripheralRepository(
            self::$pdo,
            self::$peripheralTypeRepository
        );

        self::$samplePeripheralType = new PeripheralType(
            "Mouse",
            1
        );

        self::$samplePeripheral = new Peripheral(
            "objID",
            "Peripheral 1.0",
            self::$samplePeripheralType,
            null,
            null,
            null,
        );

        self::$peripheralTypeRepository->save(self::$samplePeripheralType);
    }

    public function setUp():void{
        //Peripheral saveed to test duplicated supports errors
        self::$peripheralRepository->save(self::$samplePeripheral);
    }

    public function tearDown():void{
        //Clear the table
        self::$pdo->exec("SET FOREIGN_KEY_CHECKS=0; TRUNCATE TABLE Peripheral; TRUNCATE TABLE GenericObject; SET FOREIGN_KEY_CHECKS=1;");
    }

    //INSERT TESTS
    public function testGoodInsert():void{
        $peripheral = clone self::$samplePeripheral;
        $peripheral->objectId = "objID2";
        $peripheral->modelName = "Peripheral 2";

        self::$peripheralRepository->save($peripheral);

        $this->assertEquals(self::$peripheralRepository->findById("objID2")->modelName,"Peripheral 2");
    }

    public function testBadInsert():void{
        $this->expectException(RepositoryException::class);
        //Peripheral already saveed in the setUp() method  
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

    public function testGoodSelectAll():void{
        $peripheral1 = clone self::$samplePeripheral;
        $peripheral1->objectId = "objID1";

        $peripheral2 = clone self::$samplePeripheral;
        $peripheral2->objectId = "objID2";

        $peripheral3 = clone self::$samplePeripheral;
        $peripheral3->objectId = "objID3";

        self::$peripheralRepository->save($peripheral1);
        self::$peripheralRepository->save($peripheral2);
        self::$peripheralRepository->save($peripheral3);

        $peripherals = self::$peripheralRepository->findAll();

        $this->assertEquals(count($peripherals),4);
        $this->assertNotNull($peripherals[1]);
    }

    public function testGoodfindByModelName():void{

        $peripheral = clone self::$samplePeripheral;
        $peripheral->objectId = "objID2";
        $peripheral->modelName = "Peripheral Test";

        self::$peripheralRepository->save($peripheral);

        $this->assertEquals(self::$peripheralRepository->findByModelName("Peripheral Test")->modelName,"Peripheral Test");
    }

    public function testGoodSelectByKey():void{

        $peripheral = clone self::$samplePeripheral;
        $peripheral->objectId = "objID2";
        $peripheral->modelName = "Peripheral Test";

        self::$peripheralRepository->save($peripheral);

        $this->assertEquals(count(self::$peripheralRepository->findByKey("mous")),2);
    }

    public function testBadSelectByKey():void{
        $this->assertEquals(self::$peripheralRepository->findByKey("wrongkey"),[]);
    }

    //UPDATE TESTS
    public function testGoodUpdate():void{
        $peripheral = clone self::$samplePeripheral;
        $peripheral->modelName = "NEW MODELNAME";

        self::$peripheralRepository->update($peripheral);

        $this->assertEquals("NEW MODELNAME",self::$peripheralRepository->findById("objID")->modelName);
    }

    //DELETE TESTS
    public function testGoodDelete():void{

        self::$peripheralRepository->delete("objID");

        $this->assertNull(self::$peripheralRepository->findById("objID"));
    }

    public static function tearDownAfterClass():void{
        self::$pdo = RepositoryTestUtil::dropTestDB(self::$pdo);
        self::$pdo = null;
    }
}