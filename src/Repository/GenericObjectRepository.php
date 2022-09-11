<?php

declare(strict_types=1);

namespace App\Repository;

use App\Exception\RepositoryException;
use PDO;
use App\Model\Software\Software;
use App\Model\Book\Book;
use App\Model\Magazine\Magazine;
use App\Model\Peripheral\Peripheral;
use App\Model\Computer\Computer;
use App\Model\Response\GenericObjectResponse;

use App\Repository\Book\BookRepository;
use App\Repository\Computer\ComputerRepository;
use App\Repository\Magazine\MagazineRepository;
use App\Repository\Peripheral\PeripheralRepository;
use App\Repository\Software\SoftwareRepository;
use PDOException;
use App\Util\ORM;

class GenericObjectRepository extends GenericRepository {

    public array $Repositories;
    // public SoftwareRepository $softwareRepository;
    // public ComputerRepository $computerRepository;
    // public BookRepository $bookRepository;
    // public PeripheralRepository $peripheralRepository;
    // public MagazineRepository $magazineRepository;

    public function __construct(
        PDO $pdo,
        SoftwareRepository $softwareRepository,
        ComputerRepository $computerRepository,
        BookRepository $bookRepository,
        PeripheralRepository $peripheralRepository,
        MagazineRepository $magazineRepository
    )
    {
        parent::__construct($pdo);
        $this->Repositories = [
            'software'=>$softwareRepository,
            'computer'=>$computerRepository,
            'book'=>$bookRepository,
            'peripheral'=>$peripheralRepository,
            'magazine'=>$magazineRepository
        ];
        // $this->softwareRepository = $softwareRepository;
        // $this->computerRepository = $computerRepository;
        // $this->bookRepository = $bookRepository;
        // $this->peripheralRepository = $peripheralRepository;
        // $this->magazineRepository = $magazineRepository;
    }

    /**
     * Select an object
     * @param string $ObjectID     The ObjectID to select
     * @return ?GenericObjectResponse            The Object selected, null if not found
     */
    public function selectById(string $ObjectID): ?GenericObjectResponse {

        $obj = null;
        $repoName = null;

        foreach($this->Repositories as $RepoName => $Repo){
            $result = $Repo->selectById($ObjectID);
            
            if($result){                             
                $obj = $result;
                $repoName = $RepoName;
            }
        }   
        
        if($obj){
            return $this->$repoName($obj);
        }
        return null;
    }

    /**
     * Select objects by query
     * @param string $query     The query given
     * @return array            The result array
     */
    public function selectByQuery(string $query): array {

        $result = [];

        foreach($this->Repositories as $RepoName => $Repo){
            $unmappedResult = $Repo->selectByKey($query);
            
            if(count($unmappedResult)>0){                             

                foreach($unmappedResult as $item){
                    $result[] = $this->$RepoName($item);
                }                
            }
        }   
        
        return $result;
    }

    /**
     * Map a book object to a generic object
     * @param Book $obj The book object
     * @return GenericObjectResponse The object mapped
     */
    public function book(Book $obj):GenericObjectResponse{
        $authors = [];
        foreach($obj->Authors as $author){
            $authors[] = $author->lastname;
        }

        return new GenericObjectResponse(
            $obj->ObjectID,
            $obj->Title,
            [
                'Publisher'=>$obj->Publisher->Name,
                'Year'=>$obj->Year,
                'ISBN'=>$obj->ISBN,
                'Pages'=>$obj->Pages,
                'Authors'=>implode(", ",$authors)
            ],
            $obj->Note,
            $obj->Url,
            $obj->Tag
        );
    }

    /**
     * Map a computer object to a generic object
     * @param Computer $obj The computer object
     * @return GenericObjectResponse The object mapped
     */
    public function computer(Computer $obj):GenericObjectResponse{        
        return new GenericObjectResponse(
            $obj->ObjectID,
            $obj->ModelName,
            [
                'Year'=>$obj->Year,
                'Hdd size'=>$obj->HddSize,
                'Os'=>$obj->Os->Name,
                'Cpu'=>$obj->Cpu->ModelName.' '.$obj->Cpu->Speed,
                'Ram'=>$obj->Ram->ModelName.' '.$obj->Ram->Size
            ],
            $obj->Note,
            $obj->Url,
            $obj->Tag
        );
    }

    /**
     * Map a magazine object to a generic object
     * @param Magazine $obj The magazine object
     * @return GenericObjectResponse The object mapped
     */
    public function magazine(Magazine $obj):GenericObjectResponse{        
        return new GenericObjectResponse(
            $obj->ObjectID,
            $obj->Title,
            [
                'Magazine number'=>$obj->MagazineNumber,
                'Publisher'=>$obj->Publisher->Name,
                'Year'=>$obj->Year
            ],
            $obj->Note,
            $obj->Url,
            $obj->Tag
        );
    }

    /**
     * Map a peripheral object to a generic object
     * @param Peripheral $obj The peripheral object
     * @return GenericObjectResponse The object mapped
     */
    public function peripheral(Peripheral $obj):GenericObjectResponse{        
        return new GenericObjectResponse(
            $obj->ObjectID,
            $obj->ModelName,
            [
                'Peripheral type'=>$obj->PeripheralType->Name
            ],
            $obj->Note,
            $obj->Url,
            $obj->Tag
        );
    }

    /**
     * Map a software object to a generic object
     * @param Software $obj The software object
     * @return GenericObjectResponse The object mapped
     */
    public function software(Software $obj):GenericObjectResponse{
        return new GenericObjectResponse(
            $obj->ObjectID,
            $obj->Title,
            [
                'Os'=>$obj->Os->Name,
                'Software Type'=>$obj->SoftwareType->Name,
                'Support Type'=>$obj->SupportType->Name
            ],
            $obj->Note,
            $obj->Url,
            $obj->Tag
        );
    }
    
}
