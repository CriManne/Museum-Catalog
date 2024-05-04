<?php

declare(strict_types=1);

namespace App\Repository\Computer;

use AbstractRepo\Repository\AbstractRepository;
use App\Exception\RepositoryException;

use App\Model\Computer\Computer;

use App\Model\Computer\Cpu;
use App\Model\Computer\Os;
use App\Model\Computer\Ram;
use App\Model\GenericObject;
use App\Util\ORM;
use PDO;
use PDOException;
use ReflectionException;

class ComputerRepository extends AbstractRepository
{
    public cpuRepository $cpuRepository;
    public ramRepository $ramRepository;
    public osRepository $osRepository;

    public function __construct(
        PDO           $pdo,
        cpuRepository $cpuRepository,
        ramRepository $ramRepository,
        osRepository  $osRepository
    )
    {
        parent::__construct($pdo);
        $this->cpuRepository = $cpuRepository;
        $this->ramRepository = $ramRepository;
        $this->osRepository = $osRepository;
    }

    /**
     * @return string
     */
    public static function getModel(): string
    {
        return Computer::class;
    }

    /**
     * Return a new instance of Computer from an array
     * @param array $rawComputer The raw computer object
     * @return Computer The new instance of computer with the fk filled with the result of selects
     * @throws ReflectionException
     */
    function returnMappedObject(array $rawComputer): Computer
    {
        return new Computer(
            ORM::getNewInstance(GenericObject::class, $rawComputer['genericObject']),
            $rawComputer["modelName"],
            intval($rawComputer["year"]),
            $rawComputer["hddSize"] ?? null,
            ORM::getNewInstance(Cpu::class, $rawComputer['cpu']),
            ORM::getNewInstance(Ram::class, $rawComputer['ram']),
            $rawComputer['os'] !== null
                ? ORM::getNewInstance(Os::class, $rawComputer['os'])
                : null
        );
    }
}
