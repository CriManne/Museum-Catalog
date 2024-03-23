<?php
declare(strict_types=1);

namespace App\Test\Repository;

use PDO;
use PHPUnit\Framework\TestCase;

use App\Exception\RepositoryException;
use App\Model\Computer\Computer;
use App\Model\Computer\Cpu;
use App\Model\Computer\Os;
use App\Model\Computer\Ram;
use App\Repository\Computer\ComputerRepository;
use App\Repository\Computer\CpuRepository;
use App\Repository\Computer\OsRepository;
use App\Repository\Computer\RamRepository;

final class ComputerRepositoryTest extends TestCase
{
    public static ?PDO $pdo;
    public static Computer $sampleComputer;
    public static Os $sampleOs;
    public static Ram $sampleRam;
    public static Cpu $sampleCpu;

    public static RamRepository $ramRepository;
    public static CpuRepository $cpuRepository;
    public static OsRepository $osRepository;
    public static ComputerRepository $computerRepository;

    public static function setUpBeforeClass(): void
    {
        self::$pdo = RepositoryTestUtil::getTestPdo();
        self::$pdo = RepositoryTestUtil::dropTestDB(self::$pdo);
        self::$pdo = RepositoryTestUtil::createTestDB(self::$pdo);

        // Repository to handle relations
        self::$ramRepository = new RamRepository(self::$pdo);
        self::$cpuRepository = new CpuRepository(self::$pdo);
        self::$osRepository = new OsRepository(self::$pdo);


        // Repository to handle computer
        self::$computerRepository = new ComputerRepository(
            self::$pdo,
            self::$cpuRepository,
            self::$ramRepository,            
            self::$osRepository
        );        
        
        self::$sampleOs = new Os(            
            "Windows",
            1
        );

        self::$sampleCpu = new Cpu(
            'Cpu 1.0',
            "2GHZ",
            1
        );              

        self::$sampleRam = new Ram(
            "RAM 1.0",
            "4GB",
            1
        );

        self::$sampleComputer = new Computer(
            "objID",
            null,
            null,
            null,
            "Computer 1.0",
            2005,
            "1TB",
            self::$sampleCpu,
            self::$sampleRam,
            self::$sampleOs
        );

        self::$osRepository->insert(self::$sampleOs);
        self::$cpuRepository->insert(self::$sampleCpu);
        self::$ramRepository->insert(self::$sampleRam);
    }

    public function setUp():void{
        //Computer inserted to test duplicated supports errors
        self::$computerRepository->insert(self::$sampleComputer);
    }

    public function tearDown():void{
        //Clear the table
        self::$pdo->exec("SET FOREIGN_KEY_CHECKS=0; TRUNCATE TABLE computer; TRUNCATE TABLE genericobject; SET FOREIGN_KEY_CHECKS=1;");
    }

    //INSERT TESTS
    public function testGoodInsert():void{                
        $computer = clone self::$sampleComputer;
        $computer->objectId = "objID2";
        $computer->ModelName = "Computer 2";
        
        self::$computerRepository->insert($computer);

        $this->assertEquals(self::$computerRepository->selectById("objID2")->ModelName,"Computer 2");
    }
    public function testBadInsert():void{        
        $this->expectException(RepositoryException::class);
        //Computer already inserted in the setUp() method  
        self::$computerRepository->insert(self::$sampleComputer);
    }
    
    //SELECT TESTS
    public function testGoodSelectById(): void
    {
        $this->assertNotNull(self::$computerRepository->selectById("objID"));
    }
    
    public function testBadSelectById(): void
    {
        $this->assertNull(self::$computerRepository->selectById("WRONGID"));
    }     
    
    public function testGoodSelectByKey():void{
        $newPc = clone self::$sampleComputer;
        $newPc->objectId = "OBJ2";
        $newPc->ModelName = "Computer 2";
        self::$computerRepository->insert($newPc);

        $this->assertEquals(count(self::$computerRepository->selectByKey("comp")),2);
    }

    public function testBadSelectByKey():void{
        $this->assertEquals(self::$computerRepository->selectByKey("wrongkey"),[]);
    }
    
    public function testGoodSelectAll():void{
        $computer1 = clone self::$sampleComputer;
        $computer1->objectId = "objID1";
        
        $computer2 = clone self::$sampleComputer;
        $computer2->objectId = "objID2";
        
        $computer3 = clone self::$sampleComputer;
        $computer3->objectId = "objID3";
                
        self::$computerRepository->insert($computer1);
        self::$computerRepository->insert($computer2);
        self::$computerRepository->insert($computer3);
        
        $computers = self::$computerRepository->selectAll();
        
        $this->assertEquals(count($computers),4);
        $this->assertNotNull($computers[1]);       
    } 
    
    public function testGoodSelectByModelName():void{

        $computer = clone self::$sampleComputer;
        $computer->objectId = "objID2";
        $computer->ModelName = "Computer Test";
        
        self::$computerRepository->insert($computer);

        $this->assertEquals(self::$computerRepository->selectByModelName("Computer Test")->ModelName,"Computer Test");
    }

    //UPDATE TESTS
    public function testGoodUpdate():void{
        $computer = clone self::$sampleComputer;
        $computer->ModelName = "NEW MODELNAME";
        
        self::$computerRepository->update($computer);
        
        $this->assertEquals("NEW MODELNAME",self::$computerRepository->selectById("objID")->ModelName);
    }
    
    //DELETE TESTS
    public function testGoodDelete():void{       
        
        self::$computerRepository->delete("objID");
        
        $this->assertNull(self::$computerRepository->selectById("objID"));
    }

    public static function tearDownAfterClass():void{
        self::$pdo = RepositoryTestUtil::dropTestDB(self::$pdo);        
        self::$pdo = null;
    }    
}