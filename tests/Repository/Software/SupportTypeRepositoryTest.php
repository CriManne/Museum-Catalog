<?php
declare(strict_types=1);

namespace App\Test\Repository\Software;

use App\Test\Repository\RepositoryTestUtil;
use PDO;
use PHPUnit\Framework\TestCase;
use App\Repository\Software\SupportTypeRepository;
use App\Exception\RepositoryException;
use App\Model\Software\SupportType;

final class SupportTypeRepositoryTest extends TestCase
{
    public static SupportTypeRepository $supportTypeRepository;
    public static ?PDO $pdo;

    public static function setUpBeforeClass(): void
    {
        self::$pdo = RepositoryTestUtil::getTestPdo();

        self::$pdo = RepositoryTestUtil::dropTestDB(self::$pdo);
        self::$pdo = RepositoryTestUtil::createTestDB(self::$pdo);

        self::$supportTypeRepository = new SupportTypeRepository(self::$pdo);          
    }

    public function setUp():void{
        //Support saveed to test duplicated supports errors
        $supportType = new SupportType('CD-ROM');
        self::$supportTypeRepository->save($supportType);
    }

    public function tearDown():void{
        //Clear the table
        self::$pdo->exec("SET FOREIGN_KEY_CHECKS=0; TRUNCATE TABLE SupportType; SET FOREIGN_KEY_CHECKS=1;");
    }

    //INSERT TESTS
    public function testGoodInsert():void{                
        $supportType = new SupportType('FLOPPY');

        self::$supportTypeRepository->save($supportType);

        $this->assertEquals(self::$supportTypeRepository->findById(2)->name,"FLOPPY");
    }
    public function testBadInsert():void{        
        $this->expectException(RepositoryException::class);

        //SupportType already saveed in the setUp() method
        $supportType = new SupportType('CD-ROM');

        self::$supportTypeRepository->save($supportType);
    }
    
    //SELECT TESTS
    public function testGoodSelectById(): void
    {
        $this->assertNotNull(self::$supportTypeRepository->findById(1));
    }
    
    public function testBadSelectById(): void
    {
        $this->assertNull(self::$supportTypeRepository->findById(3));
    }
    
    public function testGoodSelectByName(): void
    {
        $this->assertNotNull(self::$supportTypeRepository->findByName("CD-ROM"));
    }
    
    public function testBadSelectByName(): void
    {
        $this->assertNull(self::$supportTypeRepository->findByName("WRONG-SUPPORT"));
    }
    
    public function testGoodSelectByKey(): void
    {
        $this->assertNotEmpty(self::$supportTypeRepository->findByKey("rom"));
    }
    
    public function testBadSelectByKey(): void
    {
        $this->assertEmpty(self::$supportTypeRepository->findByKey("WRONG-SUPPORT"));
    }
    
    public function testGoodSelectAll():void{
        $supportType1 = new SupportType('S1');
        $supportType2 = new SupportType('S2');
        $supportType3 = new SupportType('S3');
        self::$supportTypeRepository->save($supportType1);
        self::$supportTypeRepository->save($supportType2);
        self::$supportTypeRepository->save($supportType3);
        
        $supportTypes = self::$supportTypeRepository->findAll();
        
        $this->assertEquals(count($supportTypes),4);
        $this->assertNotNull($supportTypes[1]);       
    }    
    
    //UPDATE TESTS
    public function testGoodUpdate():void{
        $supportType = new SupportType('FLOPPY',1);
        
        self::$supportTypeRepository->update($supportType);
        
        $this->assertEquals("FLOPPY",self::$supportTypeRepository->findById(1)->name);
    }
    
    //DELETE TESTS
    public function testGoodDelete():void{       
        
        self::$supportTypeRepository->delete(1);
        
        $this->assertNull(self::$supportTypeRepository->findById(1));
    }
    
    public static function tearDownAfterClass():void{
        self::$pdo = RepositoryTestUtil::dropTestDB(self::$pdo);        
        self::$pdo = null;
    }    
}