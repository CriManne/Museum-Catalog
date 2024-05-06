<?php
declare(strict_types=1);

namespace App\Test\Repository\Peripheral;

use App\Test\Repository\RepositoryTestUtil;
use PDO;
use PHPUnit\Framework\TestCase;
use App\Repository\Peripheral\PeripheralTypeRepository;
use App\Model\Peripheral\PeripheralType;
use AbstractRepo\Exceptions\RepositoryException as AbstractRepositoryException;

final class PeripheralTypeRepositoryTest extends TestCase
{
    public static PeripheralTypeRepository $peripheralTypeRepository;
    public static ?PDO $pdo;

    public static function setUpBeforeClass(): void
    {
        self::$pdo = RepositoryTestUtil::getTestPdo();

        self::$pdo = RepositoryTestUtil::dropTestDB(self::$pdo);
        self::$pdo = RepositoryTestUtil::createTestDB(self::$pdo);

        self::$peripheralTypeRepository = new PeripheralTypeRepository(self::$pdo);          
    }

    public function setUp():void{
        //PeripheralType saveed to test duplicated os errors
        $peripheralType = new PeripheralType('Mouse');
        self::$peripheralTypeRepository->save($peripheralType);
    }

    public function tearDown():void{
        //Clear the table
        self::$pdo->exec("SET FOREIGN_KEY_CHECKS=0; TRUNCATE TABLE PeripheralType; SET FOREIGN_KEY_CHECKS=1;");
    }

    //INSERT TESTS
    public function testGoodInsert():void{                
        $peripheralType = new PeripheralType('Keyboard');

        self::$peripheralTypeRepository->save($peripheralType);

        $this->assertEquals(self::$peripheralTypeRepository->findById(2)->name,"Keyboard");
    }
    public function testBadInsert():void{        
        $this->expectException(AbstractRepositoryException::class);

        //PeripheralType already saved in the setUp() method
        $peripheralType = new PeripheralType('Mouse');

        self::$peripheralTypeRepository->save($peripheralType);
    }
    
    //SELECT TESTS
    public function testGoodSelectById(): void
    {
        $this->assertNotNull(self::$peripheralTypeRepository->findById(1));
    }
    
    public function testBadSelectById(): void
    {
        $this->assertNull(self::$peripheralTypeRepository->findById(3));
    }

    public function testGoodSelectByKey(): void
    {
        $this->assertNotEmpty(self::$peripheralTypeRepository->findByQuery("ous"));
    }
    
    public function testBadSelectByKey(): void
    {
        $this->assertEmpty(self::$peripheralTypeRepository->findByQuery("WRONG-PERIPHERALTYPE-NAME"));
    }
    
    
    public function testGoodSelectAll():void{
        $peripheralType1 = new PeripheralType('PT1');
        $peripheralType2 = new PeripheralType('PT2');
        $peripheralType3 = new PeripheralType('PT3');
        self::$peripheralTypeRepository->save($peripheralType1);
        self::$peripheralTypeRepository->save($peripheralType2);
        self::$peripheralTypeRepository->save($peripheralType3);
        
        $peripheralTypes = self::$peripheralTypeRepository->find();
        
        $this->assertEquals(count($peripheralTypes),4);
        $this->assertNotNull($peripheralTypes[1]);       
    }    
    
    //UPDATE TESTS
    public function testGoodUpdate():void{
        $peripheralType = new PeripheralType('Keyboard',1);
        
        self::$peripheralTypeRepository->update($peripheralType);
        
        $this->assertEquals("Keyboard",self::$peripheralTypeRepository->findById(1)->name);
    }
    
    //DELETE TESTS
    public function testGoodDelete():void{       
        
        self::$peripheralTypeRepository->delete(1);
        
        $this->assertNull(self::$peripheralTypeRepository->findById(1));
    }
    
    public static function tearDownAfterClass():void{
        self::$pdo = RepositoryTestUtil::dropTestDB(self::$pdo);        
        self::$pdo = null;
    }    
}