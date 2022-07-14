<?php

    declare(strict_types=1);

    namespace Mupin\Repository;
    
    chdir(dirname(__DIR__));

    use PDO;
    use DI\ContainerBuilder;

    class MupinRepository{

        public PDO $pdo;

        public function __construct(PDO $pdo){
            $this->pdo = $pdo;
        }

    }
?>