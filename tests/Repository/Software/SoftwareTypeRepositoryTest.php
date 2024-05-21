<?php
declare(strict_types=1);

namespace App\Test\Repository\Software;

use App\Test\Repository\BaseRepositoryTest;
use App\Test\Repository\RepositoryTestUtil;
use App\Repository\Software\SoftwareTypeRepository;
use App\Models\Software\SoftwareType;
use AbstractRepo\Exceptions\RepositoryException as AbstractRepositoryException;

final class SoftwareTypeRepositoryTest extends BaseRepositoryTest
{
    public static SoftwareTypeRepository $softwareTypeRepository;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
        self::$softwareTypeRepository = new SoftwareTypeRepository(self::$pdo);
    }

    public function setUp():void{
        //Support saved to test duplicated supports errors
        $softwareType = new SoftwareType('Office');
        self::$softwareTypeRepository->save($softwareType);
    }

    public function tearDown():void{
        //Clear the table
        self::$pdo->exec("SET FOREIGN_KEY_CHECKS=0; TRUNCATE TABLE SoftwareType; SET FOREIGN_KEY_CHECKS=1;");
    }

    //INSERT TESTS
    public function testGoodInsert():void{                
        $softwareType = new SoftwareType('Game');

        self::$softwareTypeRepository->save($softwareType);

        $this->assertEquals(self::$softwareTypeRepository->findById(2)->name,"Game");
    }
    public function testBadInsert():void{        
        $this->expectException(AbstractRepositoryException::class);

        //SoftwareType already saved in the setUp() method
        $softwareType = new SoftwareType('Office');

        self::$softwareTypeRepository->save($softwareType);
    }
    
    //SELECT TESTS
    public function testGoodSelectById(): void
    {
        $this->assertNotNull(self::$softwareTypeRepository->findById(1));
    }
    
    public function testBadSelectById(): void
    {
        $this->assertNull(self::$softwareTypeRepository->findById(3));
    }

    public function testGoodSelectByKey(): void
    {
        $this->assertNotEmpty(self::$softwareTypeRepository->findByQuery("fice"));
    }
    
    public function testBadSelectByKey(): void
    {
        $this->assertEmpty(self::$softwareTypeRepository->findByQuery("WRONG-SOFTWAERE-TYPE"));
    }
    
    
    public function testGoodSelectAll():void{
        $softwareType1 = new SoftwareType('S1');
        $softwareType2 = new SoftwareType('S2');
        $softwareType3 = new SoftwareType('S3');
        self::$softwareTypeRepository->save($softwareType1);
        self::$softwareTypeRepository->save($softwareType2);
        self::$softwareTypeRepository->save($softwareType3);
        
        $softwareTypes = self::$softwareTypeRepository->find();
        
        $this->assertEquals(count($softwareTypes),4);
        $this->assertNotNull($softwareTypes[1]);       
    }    
    
    //UPDATE TESTS
    public function testGoodUpdate():void{
        $softwareType = new SoftwareType('Game',1);
        
        self::$softwareTypeRepository->update($softwareType);
        
        $this->assertEquals("Game",self::$softwareTypeRepository->findById(1)->name);
    }
    
    //DELETE TESTS
    public function testGoodDelete():void{       
        
        self::$softwareTypeRepository->delete(1);
        
        $this->assertNull(self::$softwareTypeRepository->findById(1));
    }
}