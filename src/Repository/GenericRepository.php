<?php

    declare(strict_types=1);

    namespace Mupin\Repository;
    
    use PDO;

    class GenericRepository{

        public PDO $pdo;

        public function __construct(PDO $pdo){
            $this->pdo = $pdo;
        }

    }
?>