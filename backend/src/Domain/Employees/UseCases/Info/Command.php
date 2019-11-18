<?php
/**
 * Look up all the resources associated with an employee
 *
 * This command will go through all the resources known to the system,
 * and query each one for the employee's information.  It will return
 * the employee's information from each resource.
 *
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Domain\Employees\UseCases\Info;

use Domain\AccountRequests\DataStorage\AccountRequestsRepository;
use Domain\AccountRequests\UseCases\Search\Request as SearchRequest;
use Domain\Employees\DataStorage\EmployeesRepository;
use Domain\Resources\DataStorage\ResourcesRepository;
use Domain\Users\Entities\User;

class Command
{
    private $employeeRepo;
    private $accountsRepo;
    private $resourceRepo;

    public function __construct(EmployeesRepository       $repository,
                                ResourcesRepository       $resources,
                                AccountRequestsRepository $accounts)
    {
        $this->employeeRepo = $repository;
        $this->accountsRepo = $accounts;
        $this->resourceRepo = $resources;
    }

    /**
     * @param int  $employee_number  The employee to look up in the system
     * @param User $user             The authenticated person performing the request
     */
    public function __invoke(int $employee_number, User $user): Response
    {
        try {
            $employee = $this->employeeRepo->load($employee_number);
            $accounts = $this->accountRequestsForEmployee($employee_number);
        }
        catch (\Exception $e) {
            return new Response(null, null, null, [$e->getMessage()]);
        }

        // Collect all the resources assigned to the employee
        $resources = [];
        $result = $this->resourceRepo->find([]);
        foreach ($result['rows'] as $r) {
            // Query each resource service for the employee
            $service = $r->serviceFactory($user->username);
            $resources[] = [
                'definition' => $r,
                'values'     => $service->load($employee)
            ];
        }

        return new Response($employee, $resources, $accounts);
    }

    private function accountRequestsForEmployee(int $number): array
    {
        $result = $this->accountsRepo->find(new SearchRequest(['employee_number'=>$number]));
        return $result['rows'];
    }
}
