<?php
declare(strict_types=1);

namespace App\Test\Repository\Software;

use App\Test\Repository\RepositoryTestUtil;
use PDO;
use PHPUnit\Framework\TestCase;

use App\Model\Software\Software;
use App\Model\Software\SupportType;
use App\Model\Software\SoftwareType;
use App\Model\Computer\Os;

use App\Repository\Software\SoftwareRepository;
use App\Repository\Software\SoftwareTypeRepository;
use App\Repository\Software\SupportTypeRepository;
use App\Repository\Computer\OsRepository;

use App\Exception\RepositoryException;

final class SoftwareRepositoryTest extends TestCase
{
    public static ?PDO $pdo;
    public static Software $sampleSoftware;
    public static SoftwareType $sampleSoftwareType;
    public static SupportType $sampleSupportType;
    public static Os $sampleOs;

    public static SoftwareRepository $softwareRepository;
    public static SoftwareTypeRepository $softwareTypeRepository;
    public static SupportTypeRepository $supportTypeRepository;
    public static OsRepository $osRepository;

    public static function setUpBeforeClass(): void
    {
        self::$pdo = RepositoryTestUtil::getTestPdo();
        self::$pdo = RepositoryTestUtil::dropTestDB(self::$pdo);
        self::$pdo = RepositoryTestUtil::createTestDB(self::$pdo);

        // Repository to handle relations
        self::$osRepository = new OsRepository(self::$pdo);
        self::$softwareTypeRepository = new SoftwareTypeRepository(self::$pdo);
        self::$supportTypeRepository = new SupportTypeRepository(self::$pdo);

        // Repository to handle software
        self::$softwareRepository = new SoftwareRepository(
            self::$pdo,
            self::$softwareTypeRepository,
            self::$supportTypeRepository,
            self::$osRepository            
        );        
        
        self::$sampleOs = new Os(
            "Windows",
            1
        );

        self::$sampleSoftwareType = new SoftwareType(
            'Office',
            1
        );

        self::$sampleSupportType = new SupportType(
            'CD-ROM',
            1
        );      

        self::$sampleSoftware = new Software(
            "objID",
            'Paint',
            self::$sampleOs,
            self::$sampleSoftwareType,
            self::$sampleSupportType,
            null,
            null,
            null,
        );


        self::$softwareTypeRepository->save(self::$sampleSoftwareType);
        self::$supportTypeRepository->save(self::$sampleSupportType);
        self::$osRepository->save(self::$sampleOs);
    }

    public function setUp():void{
        //Software saveed to test duplicated supports errors
        self::$softwareRepository->save(self::$sampleSoftware);
    }

    public function tearDown():void{
        //Clear the table
        self::$pdo->exec("SET FOREIGN_KEY_CHECKS=0; TRUNCATE TABLE Software; TRUNCATE TABLE GenericObject; SET FOREIGN_KEY_CHECKS=1;");
    }

    //INSERT TESTS
    public function testGoodInsert():void{                
        $software = clone self::$sampleSoftware;
        $software->objectId = "objID2";
        $software->title = "Game";
        
        self::$softwareRepository->save($software);

        $this->assertEquals(self::$softwareRepository->findById("objID2")->title,"Game");
    }
    public function testBadInsert():void{        
        $this->expectException(RepositoryException::class);
        //Software already saveed in the setUp() method  
        self::$softwareRepository->save(self::$sampleSoftware);
    }
    //SELECT TESTS
    public function testGoodSelectById(): void
    {
        $this->assertNotNull(self::$softwareRepository->findById("objID"));
    }
    
    public function testBadSelectById(): void
    {
        $this->assertNull(self::$softwareRepository->findById("WRONGID"));
    }       
    
    public function testGoodSelectAll():void{
        $software1 = clone self::$sampleSoftware;
        $software1->objectId = "objID1";
        
        $software2 = clone self::$sampleSoftware;
        $software2->objectId = "objID2";
        
        $software3 = clone self::$sampleSoftware;
        $software3->objectId = "objID3";
                
        self::$softwareRepository->save($software1);
        self::$softwareRepository->save($software2);
        self::$softwareRepository->save($software3);
        
        $softwares = self::$softwareRepository->find();
        
        $this->assertEquals(count($softwares),4);
        $this->assertNotNull($softwares[1]);       
    } 
    
    public function testGoodSelectBytitle():void{

        $software = clone self::$sampleSoftware;
        $software->objectId = "objID2";
        $software->title = "Visual studio";
        
        self::$softwareRepository->save($software);

        $this->assertEquals(self::$softwareRepository->findByTitle("Visual studio")->title,"Visual studio");
    }

    public function testGoodSelectByKey():void{

        $software = clone self::$sampleSoftware;
        $software->objectId = "objID2";
        $software->title = "Visual studio";
        
        self::$softwareRepository->save($software);

        $this->assertEquals(count(self::$softwareRepository->findByQuery("oFFic")),2);
    }

    public function testBadSelectByKey():void{
        $this->assertEquals(self::$softwareRepository->findByQuery("wrongkey"),[]);
    }

    //UPDATE TESTS
    public function testGoodUpdate():void{
        $software = clone self::$sampleSoftware;
        $software->title = "NEW TITLE";
        
        self::$softwareRepository->update($software);
        
        $this->assertEquals("NEW TITLE",self::$softwareRepository->findById("objID")->title);
    }
    
    //DELETE TESTS
    public function testGoodDelete():void{       
        
        self::$softwareRepository->delete("objID");
        
        $this->assertNull(self::$softwareRepository->findById("objID"));
    }

    public static function tearDownAfterClass():void{
        self::$pdo = RepositoryTestUtil::dropTestDB(self::$pdo);        
        self::$pdo = null;
    }    
}