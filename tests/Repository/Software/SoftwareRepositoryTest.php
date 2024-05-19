<?php
declare(strict_types=1);

namespace App\Test\Repository\Software;

use App\Models\GenericObject;
use App\Repository\GenericObjectRepository;
use App\Test\Repository\BaseRepositoryTest;
use App\Test\Repository\RepositoryTestUtil;
use PDO;
use PHPUnit\Framework\TestCase;
use App\Models\Software\Software;
use App\Models\Software\SupportType;
use App\Models\Software\SoftwareType;
use App\Models\Computer\Os;
use App\Repository\Software\SoftwareRepository;
use App\Repository\Software\SoftwareTypeRepository;
use App\Repository\Software\SupportTypeRepository;
use App\Repository\Computer\OsRepository;
use AbstractRepo\Exceptions\RepositoryException as AbstractRepositoryException;

final class SoftwareRepositoryTest extends BaseRepositoryTest
{
    public static GenericObject $sampleGenericObject;
    public static Software $sampleSoftware;
    public static SoftwareType $sampleSoftwareType;
    public static SupportType $sampleSupportType;
    public static Os $sampleOs;

    public static GenericObjectRepository $genericObjectRepository;
    public static SoftwareRepository $softwareRepository;
    public static SoftwareTypeRepository $softwareTypeRepository;
    public static SupportTypeRepository $supportTypeRepository;
    public static OsRepository $osRepository;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        // Repository to handle relations
        self::$genericObjectRepository = new GenericObjectRepository();
        self::$osRepository = new OsRepository();
        self::$softwareTypeRepository = new SoftwareTypeRepository();
        self::$supportTypeRepository = new SupportTypeRepository();

        // Repository to handle software
        self::$softwareRepository = new SoftwareRepository();

        self::$sampleGenericObject = new GenericObject(
            id: "objID"
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
            self::$sampleGenericObject,
            'Paint',
            self::$sampleOs,
            self::$sampleSoftwareType,
            self::$sampleSupportType,
        );

        self::$softwareTypeRepository->save(self::$sampleSoftwareType);
        self::$supportTypeRepository->save(self::$sampleSupportType);
        self::$osRepository->save(self::$sampleOs);
    }

    public function setUp(): void
    {
        //Software saved to test duplicated supports errors
        self::$genericObjectRepository->save(self::$sampleGenericObject);
        self::$softwareRepository->save(self::$sampleSoftware);
    }

    public function tearDown(): void
    {
        //Clear the table
        self::$pdo->exec("SET FOREIGN_KEY_CHECKS=0; TRUNCATE TABLE Software; TRUNCATE TABLE GenericObject; SET FOREIGN_KEY_CHECKS=1;");
    }

    //INSERT TESTS
    public function testGoodInsert(): void
    {
        $genericObject = clone self::$sampleGenericObject;
        $software = clone self::$sampleSoftware;

        $genericObject->id = "objID2";
        $software->genericObject = $genericObject;
        $software->title = "Game";

        self::$genericObjectRepository->save($genericObject);
        self::$softwareRepository->save($software);

        $this->assertEquals(self::$softwareRepository->findById("objID2")->title, "Game");
    }

    public function testBadInsert(): void
    {
        $this->expectException(AbstractRepositoryException::class);
        //Software already saved in the setUp() method
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

    public function testGoodSelectAll(): void
    {
        for($i = 1; $i < 4; $i++) {
            $genericObject = clone self::$sampleGenericObject;
            $genericObject->id = "objID" . $i;
            $software = clone self::$sampleSoftware;
            $software->genericObject = $genericObject;

            self::$genericObjectRepository->save($genericObject);
            self::$softwareRepository->save($software);
        }

        $software = self::$softwareRepository->find();

        $this->assertEquals(count($software), 4);
        $this->assertNotNull($software[1]);
    }

    public function testGoodSelectByKey(): void
    {
        $genericObject = clone self::$sampleGenericObject;
        $genericObject->id = "objID2";

        $software = clone self::$sampleSoftware;
        $software->genericObject = $genericObject;
        $software->title = "Visual studio";

        self::$genericObjectRepository->save($genericObject);
        self::$softwareRepository->save($software);

        $this->assertEquals(count(self::$softwareRepository->findByQuery("oFFic")), 2);
    }

    public function testBadSelectByKey(): void
    {
        $this->assertEquals(self::$softwareRepository->findByQuery("wrongkey"), []);
    }

    //UPDATE TESTS
    public function testGoodUpdate(): void
    {
        $software = clone self::$sampleSoftware;
        $software->title = "NEW TITLE";

        self::$softwareRepository->update($software);

        $this->assertEquals("NEW TITLE", self::$softwareRepository->findById("objID")->title);
    }

    //DELETE TESTS
    public function testGoodDelete(): void
    {

        self::$softwareRepository->delete("objID");

        $this->assertNull(self::$softwareRepository->findById("objID"));
    }
}