<?php
declare(strict_types=1);

namespace App\Test\Service\Magazine;

use App\Models\GenericObject;
use App\Repository\Book\PublisherRepository;
use App\Repository\GenericObjectRepository;
use App\Test\Service\BaseServiceTest;
use App\Exception\ServiceException;

use App\Models\Magazine\Magazine;
use App\Models\Book\Publisher;

use App\Repository\Magazine\MagazineRepository;
use App\Service\Magazine\MagazineService;

final class MagazineServiceTest extends BaseServiceTest
{
    public GenericObjectRepository $genericObjectRepository;
    public PublisherRepository     $publisherRepository;
    public MagazineRepository      $magazineRepository;
    public MagazineService         $magazineService;


    public function setUp(): void
    {
        $this->genericObjectRepository = $this->createMock(GenericObjectRepository::class);
        $this->publisherRepository     = $this->createMock(PublisherRepository::class);
        $this->magazineRepository      = $this->createMock(MagazineRepository::class);

        $this->magazineService = new MagazineService(
            genericObjectRepository: $this->genericObjectRepository,
            publisherRepository: $this->publisherRepository,
            magazineRepository: $this->magazineRepository
        );

        $this->sampleGenericObject = new GenericObject("objID");

        $this->sampleObject = new Magazine(
            $this->sampleGenericObject,
            'Magazine title',
            2005,
            205,
            new Publisher('Publisher 1', 1)
        );
    }


    //INSERT TESTS

    public function testBadInsert(): void
    {
        $this->expectException(ServiceException::class);
        $this->magazineRepository->method('findFirst')->willReturn($this->sampleObject);
        $this->magazineService->save($this->sampleObject);
    }

    //SELECT TESTS
    public function testGoodSelectById(): void
    {
        $this->magazineRepository->method('findById')->willReturn($this->sampleObject);
        $this->assertEquals("Magazine title", $this->magazineService->findById("ObjID")->title);
    }

    public function testBadSelectById(): void
    {
        $this->expectException(ServiceException::class);
        $this->magazineRepository->method('findById')->willReturn(null);
        $this->magazineService->findById("ObjID25");
    }

    //UPDATE TESTS
    public function testBadUpdate(): void
    {
        $this->expectException(ServiceException::class);
        $this->magazineRepository->method('findById')->willReturn(null);
        $this->magazineService->update($this->sampleObject);
    }

    //DELETE TESTS
    public function testBadDelete(): void
    {
        $this->expectException(ServiceException::class);

        $this->magazineRepository->method('findById')->willReturn(null);

        $this->magazineService->delete("ObjID99");
    }
}
