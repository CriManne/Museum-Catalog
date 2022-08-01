<?php
declare(strict_types=1);

namespace App\Test\Repository;

use PDO;
use PHPUnit\Framework\TestCase;
use App\Repository\Software\SoftwareRepository;
use App\Exception\RepositoryException;
use App\Model\Software\Software;
use App\Model\Computer\Os;
use App\Model\Software\SupportType;
use App\Model\Software\SoftwareType;

final class SoftwareRepositoryTest extends TestCase
{
    public static SoftwareRepository $softwareRepository;
    public static ?PDO $pdo;

    public static function setUpBeforeClass(): void
    {
        self::$pdo = RepositoryTestUtil::getTestPdo();

        self::$pdo = RepositoryTestUtil::dropTestDB(self::$pdo);
        self::$pdo = RepositoryTestUtil::createTestDB(self::$pdo);

        self::$softwareRepository = new SoftwareRepository(self::$pdo);          
    }

    public function setUp():void{
        //Support inserted to test duplicated supports errors
        $software = new Software(
            "objID",
            null,
            null,
            null,
            "1",
            null,
            'Paint',
            new Os(1, "Windows"),
            new SoftwareType(1,'Office'),
            new SupportType(1,'CD-ROM')
        );
        self::$softwareRepository->insert($software);
    }

    public function tearDown():void{
        //Clear the table
        self::$pdo->exec("SET FOREIGN_KEY_CHECKS=0; TRUNCATE TABLE software; SET FOREIGN_KEY_CHECKS=1;");
    }

    //INSERT TESTS
    public function testGoodInsert():void{                
        $software = new Software(
            "objID2",
            null,
            null,
            null,
            "1",
            null,
            'Test',
            new Os(1, "Windows"),
            new SoftwareType(1,'Office'),
            new SupportType(1,'CD-ROM')
        );

        self::$softwareRepository->insert($software);

        $this->assertEquals(self::$softwareRepository->selectById(2)->Title,"Game");
    }
    public function testBadInsert():void{        
        $this->expectException(RepositoryException::class);

        //Software already inserted in the setUp() method
        $software = new Software(
            "objID",
            null,
            null,
            null,
            "1",
            null,
            'Test',
            new Os(null, "Windows"),
            new SoftwareType(null,'Office'),
            new SupportType(null,'CD-ROM')
        );

        self::$softwareRepository->insert($software);
    }
    
    //SELECT TESTS
    public function testGoodSelectById(): void
    {
        $this->assertNotNull(self::$softwareRepository->selectById(1));
    }
    
    public function testBadSelectById(): void
    {
        $this->assertNull(self::$softwareRepository->selectById(3));
    }   
    
    public function testGoodSelectAll():void{
        $software1 = new Software("objID1",null,null,null,"1",null,'Test1',
                                    new Os(null, "Windows"),new SoftwareType(null,'Office'),
                                    new SupportType(null,'CD-ROM')
                                );
        $software2 = new Software("objID2",null,null,null,"1",null,'Test2',
                                    new Os(null, "Windows"),new SoftwareType(null,'Office'),
                                    new SupportType(null,'CD-ROM')
                                );
        $software3 = new Software("objID3",null,null,null,"1",null,'Test3',
                                    new Os(null, "Windows"),new SoftwareType(null,'Office'),
                                    new SupportType(null,'CD-ROM')
                                );
        self::$softwareRepository->insert($software1);
        self::$softwareRepository->insert($software2);
        self::$softwareRepository->insert($software3);
        
        $softwares = self::$softwareRepository->selectAll();
        
        $this->assertEquals(count($softwares),4);
        $this->assertNotNull($softwares[1]);       
    }    
    
    //UPDATE TESTS
    public function testGoodUpdate():void{
        $software = new Software(
            "objID",
            null,
            null,
            null,
            "1",
            null,
            'TestUpdate',
            new Os(null, "Windows"),
            new SoftwareType(null,'Office'),
            new SupportType(null,'CD-ROM')
        );
        
        self::$softwareRepository->update($software);
        
        $this->assertEquals("TestUpdate",self::$softwareRepository->selectById(1)->Title);
    }
    
    //DELETE TESTS
    public function testGoodDelete():void{       
        
        self::$softwareRepository->delete(1);
        
        $this->assertNull(self::$softwareRepository->selectById(1));
        $this->assertNotNull(self::$softwareRepository->selectById(1,true));
    }
    
    public static function tearDownAfterClass():void{
        self::$pdo = RepositoryTestUtil::dropTestDB(self::$pdo);        
        self::$pdo = null;
    }    
}