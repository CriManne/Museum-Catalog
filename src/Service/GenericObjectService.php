<?php

declare(strict_types=1);

namespace App\Service;

use App\Exception\ServiceException;
use App\Repository\GenericObjectRepository;
use App\Model\Response\GenericObjectResponse;

class GenericObjectService {

    public GenericObjectRepository $genericObjectRepository;

    public function __construct(GenericObjectRepository $genericObjectRepository) {
        $this->genericObjectRepository = $genericObjectRepository;
    }

    /**
     * Select by id
     * @param string $ObjectID The id to select
     * @return GenericObjectResponse     The object selected
     * @throws ServiceException     If no object is found
     */
    public function selectById(string $ObjectID): GenericObjectResponse {
        $genericObject = $this->genericObjectRepository->selectById($ObjectID);
        if ($genericObject == null) throw new ServiceException("Object not found");

        return $genericObject;
    }

    /**
     * Select all
     * @param ?string $category The category to search in
     * @return array The result, empty if no result
     */
    public function selectAll(?string $category): array{
        return $this->genericObjectRepository->selectAll($category);
    }

    /**
     * Select by query
     * @param string $query The query given
     * @param ?string $category The category to search in
     * @return array The result, empty if no result
     */
    public function selectByQuery(string $query,?string $category): array{
        return $this->genericObjectRepository->selectByQuery($query,$category);
    }
}
