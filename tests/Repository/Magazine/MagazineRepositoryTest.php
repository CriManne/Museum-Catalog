<?php
declare(strict_types=1);

namespace App\Test\Repository\Magazine;

use App\Test\Repository\RepositoryTestUtil;
use PDO;
use PHPUnit\Framework\TestCase;

use App\Exception\RepositoryException;
use App\Model\Magazine\Magazine;
use App\Model\Book\Publisher;
use App\Repository\Book\PublisherRepository;
use App\Repository\Magazine\MagazineRepository;

final class MagazineRepositoryTest extends TestCase
{
    public static ?PDO $pdo;
    public static Publisher $samplePublisher;
    public static Magazine $sampleMagazine;

    public static PublisherRepository $publisherRepository;
    public static MagazineRepository $magazineRepository;

    public static function setUpBeforeClass(): void
    {
        self::$pdo = RepositoryTestUtil::getTestPdo();
        self::$pdo = RepositoryTestUtil::dropTestDB(self::$pdo);
        self::$pdo = RepositoryTestUtil::createTestDB(self::$pdo);

        // Repository to handle relations
        self::$publisherRepository = new PublisherRepository(self::$pdo);

        // Repository to handle magazine
        self::$magazineRepository = new MagazineRepository(
            self::$pdo,
            self::$publisherRepository
        );        
        
        self::$samplePublisher = new Publisher(            
            "Einaudi",
            1       
        );

        self::$sampleMagazine = new Magazine(
            "objID",
            "Magazine 1.0",
            2005,
            10492,
            self::$samplePublisher,
            null,
            null,
            null,
        );

        self::$publisherRepository->save(self::$samplePublisher);
    }

    public function setUp():void{
        //Magazine saveed to test duplicated supports errors
        self::$magazineRepository->save(self::$sampleMagazine);
    }

    public function tearDown():void{
        //Clear the table
        self::$pdo->exec("SET FOREIGN_KEY_CHECKS=0; TRUNCATE TABLE Magazine; TRUNCATE TABLE GenericObject; SET FOREIGN_KEY_CHECKS=1;");
    }

    //INSERT TESTS
    public function testGoodInsert():void{                
        $magazine = clone self::$sampleMagazine;
        $magazine->objectId = "objID2";
        $magazine->title = "Magazine 2";
        
        self::$magazineRepository->save($magazine);

        $this->assertEquals(self::$magazineRepository->findById("objID2")->title,"Magazine 2");
    }

    public function testBadInsert():void{        
        $this->expectException(RepositoryException::class);
        //Magazine already saveed in the setUp() method  
        self::$magazineRepository->save(self::$sampleMagazine);
    }
    
    //SELECT TESTS
    public function testGoodSelectById(): void
    {
        $this->assertNotNull(self::$magazineRepository->findById("objID"));
    }
    
    public function testBadSelectById(): void
    {
        $this->assertNull(self::$magazineRepository->findById("WRONGID"));
    }       
    
    public function testGoodSelectAll():void{
        $magazine1 = clone self::$sampleMagazine;
        $magazine1->objectId = "objID1";
        
        $magazine2 = clone self::$sampleMagazine;
        $magazine2->objectId = "objID2";
        
        $magazine3 = clone self::$sampleMagazine;
        $magazine3->objectId = "objID3";
                
        self::$magazineRepository->save($magazine1);
        self::$magazineRepository->save($magazine2);
        self::$magazineRepository->save($magazine3);
        
        $magazines = self::$magazineRepository->find();
        
        $this->assertEquals(count($magazines),4);
        $this->assertNotNull($magazines[1]);       
    } 
    
    public function testGoodSelectBytitle():void{

        $magazine = clone self::$sampleMagazine;
        $magazine->objectId = "objID2";
        $magazine->title = "Magazine Test";
        
        self::$magazineRepository->save($magazine);

        $this->assertEquals(self::$magazineRepository->findByTitle("Magazine Test")->title,"Magazine Test");
    }

    public function testGoodSelectByKey():void{

        $magazine = clone self::$sampleMagazine;
        $magazine->objectId = "objID2";
        $magazine->title = "Magazine Test";
        
        self::$magazineRepository->save($magazine);

        $this->assertEquals(count(self::$magazineRepository->findByQuery("maGazIn")),2);
    }

    public function testBadSelectByKey():void{       
        $this->assertEquals(self::$magazineRepository->findByQuery("wrongkey"),[]);
    }

    //UPDATE TESTS
    public function testGoodUpdate():void{
        $magazine = clone self::$sampleMagazine;
        $magazine->title = "NEW TITLE";
        
        self::$magazineRepository->update($magazine);
        
        $this->assertEquals("NEW TITLE",self::$magazineRepository->findById("objID")->title);
    }
    
    //DELETE TESTS
    public function testGoodDelete():void{       
        
        self::$magazineRepository->delete("objID");
        
        $this->assertNull(self::$magazineRepository->findById("objID"));
    }

    public static function tearDownAfterClass():void{
        self::$pdo = RepositoryTestUtil::dropTestDB(self::$pdo);        
        self::$pdo = null;
    }    
}