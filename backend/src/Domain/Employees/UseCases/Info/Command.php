<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Domain\Employees\UseCases\Info;

use Domain\Employees\DataStorage\EmployeesRepository;
use Domain\Resources\DataStorage\ResourcesRepository;
use Domain\Users\Entities\User;

class Command
{
    private $employeeRepo;
    private $resourceRepo;

    public function __construct(EmployeesRepository $repository, ResourcesRepository $resources)
    {
        $this->employeeRepo = $repository;
        $this->resourceRepo = $resources;
    }

    public function __invoke(int $number, User $user): Response
    {
        try {
            $employee = $this->employeeRepo->load($number);
        }
        catch (\Exception $e) {
            return new Response(null, null, [$e->getMessage()]);
        }

        $resources = [];
        $result = $this->resourceRepo->find([]);
        foreach ($result['rows'] as $r) {
            $service = $r->serviceFactory($user->username);
            $resources[$r->code] = $service->load($employee);
        }

        return new Response($employee, $resources);
    }
}
