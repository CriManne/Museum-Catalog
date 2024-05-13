<?php
declare(strict_types=1);

namespace App\Test\Repository\Computer;

use App\Test\Repository\RepositoryTestUtil;
use PDO;
use PHPUnit\Framework\TestCase;
use App\Repository\Computer\RamRepository;
use App\Model\Computer\Ram;

final class RamRepositoryTest extends TestCase
{
    public static RamRepository $ramRepository;
    public static ?PDO $pdo;

    public static function setUpBeforeClass(): void
    {
        self::$pdo = RepositoryTestUtil::getTestPdo();

        self::$pdo = RepositoryTestUtil::dropTestDB(self::$pdo);
        self::$pdo = RepositoryTestUtil::createTestDB(self::$pdo);

        self::$ramRepository = new RamRepository(self::$pdo);          
    }

    public function setUp():void{
        //Ram saved to test duplicated ram errors
        $ram= new Ram('Ram 1.0',"256KB");
        self::$ramRepository->save($ram);        
    }

    public function tearDown():void{
        //Clear the table
        self::$pdo->exec("SET FOREIGN_KEY_CHECKS=0; TRUNCATE TABLE Ram; SET FOREIGN_KEY_CHECKS=1;");
    }

    //INSERT TESTS
    public function testGoodInsert():void{                
        $ram= new Ram('Ram 2.0',"256KB");

        self::$ramRepository->save($ram);

        $this->assertEquals(self::$ramRepository->findById(2)->modelName,"Ram 2.0");
    }

    //No bad save test because the ModelName is not unique.
    //E.G:
    //Ram1 model: Logitech size: 125KB
    //Ram2 model: Logitech size:512KB
    
    //SELECT TESTS
    public function testGoodSelectById(): void
    {
        $this->assertNotNull(self::$ramRepository->findById(1));
    }
    
    public function testBadSelectById(): void
    {
        $this->assertNull(self::$ramRepository->findById(3));
    }

    public function testGoodSelectByKey(): void
    {
        $this->assertNotEmpty(self::$ramRepository->findByQuery("256"));
    }
    
    public function testBadSelectByKey(): void
    {
        $this->assertEmpty(self::$ramRepository->findByQuery("WRONG-RAM-NAME"));
    }
    
    
    public function testGoodSelectAll():void{
        $ram1 = new Ram('Ram 4.0',"256KB");
        $ram2 = new Ram('Ram 5.0',"1024KB");
        $ram3 = new Ram('Ram 6.0',"512KB");
        self::$ramRepository->save($ram1);
        self::$ramRepository->save($ram2);
        self::$ramRepository->save($ram3);
        
        $rams = self::$ramRepository->find();
        
        $this->assertEquals(count($rams),4);
        $this->assertNotNull($rams[1]);       
    }    
    
    //UPDATE TESTS
    public function testGoodUpdate():void{
        $ram= new Ram('Ram 2.0',"256KB",1);
        
        self::$ramRepository->update($ram);
        
        $this->assertEquals("Ram 2.0",self::$ramRepository->findById(1)->modelName);
    }
    
    //DELETE TESTS
    public function testGoodDelete():void{       
        
        self::$ramRepository->delete(1);
        
        $this->assertNull(self::$ramRepository->findById(1));
    }
    
    public static function tearDownAfterClass():void{
        self::$pdo = RepositoryTestUtil::dropTestDB(self::$pdo);        
        self::$pdo = null;
    }    
}