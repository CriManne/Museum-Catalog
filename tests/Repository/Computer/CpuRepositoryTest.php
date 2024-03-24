<?php
declare(strict_types=1);

namespace App\Test\Repository\Computer;

use App\Test\Repository\RepositoryTestUtil;
use PDO;
use PHPUnit\Framework\TestCase;
use App\Repository\Computer\CpuRepository;
use App\Model\Computer\Cpu;

final class CpuRepositoryTest extends TestCase
{
    public static CpuRepository $cpuRepository;
    public static ?PDO $pdo;

    public static function setUpBeforeClass(): void
    {
        self::$pdo = RepositoryTestUtil::getTestPdo();

        self::$pdo = RepositoryTestUtil::dropTestDB(self::$pdo);
        self::$pdo = RepositoryTestUtil::createTestDB(self::$pdo);

        self::$cpuRepository = new CpuRepository(self::$pdo);          
    }

    public function setUp():void{
        //Cpu inserted to test duplicated cpu errors
        $cpu= new Cpu('Cpu 1.0',"4GHZ");
        self::$cpuRepository->insert($cpu);        
    }

    public function tearDown():void{
        //Clear the table
        self::$pdo->exec("SET FOREIGN_KEY_CHECKS=0; TRUNCATE TABLE Cpu; SET FOREIGN_KEY_CHECKS=1;");
    }

    //INSERT TESTS
    public function testGoodInsert():void{                
        $cpu= new Cpu('Cpu 2.0',"4GHZ");

        self::$cpuRepository->insert($cpu);

        $this->assertEquals(self::$cpuRepository->findById(2)->modelName,"Cpu 2.0");
    }

    //No bad insert test because the ModelName is not unique.
    
    //SELECT TESTS
    public function testGoodSelectById(): void
    {
        $this->assertNotNull(self::$cpuRepository->findById(1));
    }
    
    public function testBadSelectById(): void
    {
        $this->assertNull(self::$cpuRepository->findById(3));
    }
    
    public function testGoodSelectByName(): void
    {
        $this->assertNotNull(self::$cpuRepository->selectByName("Cpu 1.0"));
    }
    
    public function testBadSelectByName(): void
    {
        $this->assertNull(self::$cpuRepository->selectByName("WRONG-CPU-NAME"));
    }

    public function testGoodSelectByKey(): void
    {
        $this->assertNotEmpty(self::$cpuRepository->selectByKey("pu 1"));
    }
    
    public function testBadSelectByKey(): void
    {
        $this->assertEmpty(self::$cpuRepository->selectByKey("WRONG-CPU-NAME"));
    }
    
    
    public function testGoodSelectAll():void{
        $cpu1 = new Cpu('Cpu 4.0',"4GHZ");
        $cpu2 = new Cpu('Cpu 5.0',"8GHZ");
        $cpu3 = new Cpu('Cpu 6.0',"12GHZ");
        self::$cpuRepository->insert($cpu1);
        self::$cpuRepository->insert($cpu2);
        self::$cpuRepository->insert($cpu3);
        
        $cpus = self::$cpuRepository->findAll();
        
        $this->assertEquals(count($cpus),4);
        $this->assertNotNull($cpus[1]);       
    }    
    
    //UPDATE TESTS
    public function testGoodUpdate():void{
        $cpu= new Cpu('Cpu 2.0',"4GHZ",1);
        
        self::$cpuRepository->update($cpu);
        
        $this->assertEquals("Cpu 2.0",self::$cpuRepository->findById(1)->modelName);
    }
    
    //DELETE TESTS
    public function testGoodDelete():void{       
        
        self::$cpuRepository->delete(1);
        
        $this->assertNull(self::$cpuRepository->findById(1));
    }
    
    public static function tearDownAfterClass():void{
        self::$pdo = RepositoryTestUtil::dropTestDB(self::$pdo);        
        self::$pdo = null;
    }    
}