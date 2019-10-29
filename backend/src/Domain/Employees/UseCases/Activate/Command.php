<?php
/**
 * Creates an Account Request for an employee
 *
 * This will create an Account Request with all resource values
 * generated from the profile and employee.
 *
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);

namespace Domain\Employees\UseCases\Activate;

use Domain\AccountRequests\DataStorage\AccountRequestsRepository;
use Domain\Employees\DataStorage\EmployeesRepository;
use Domain\Profiles\DataStorage\ProfilesRepository;
use Domain\Resources\DataStorage\ResourcesRepository;
use Domain\Users\DataStorage\UsersRepository;

use Domain\AccountRequests\Entities\AccountRequest;
use Domain\Employees\Entities\Employee;
use Domain\Profiles\Entities\Profile;
use Domain\Users\Entities\User;

class Command
{
    private $employees;
    private $profiles;
    private $users;
    private $resources;
    private $accounts;

    public function __construct(EmployeesRepository       $employees,
                                ProfilesRepository        $profiles,
                                ResourcesRepository       $resources,
                                UsersRepository           $users,
                                AccountRequestsRepository $accounts)
    {
        $this->employees = $employees;
        $this->profiles  = $profiles;
        $this->users     = $users;
        $this->resources = $resources;
        $this->accounts  = $accounts;
    }

    public function __invoke(Request $request): Response
    {
        $errors = $this->validate($request);
        if ($errors) { return new Response(null, $errors); }

        try {
            $employee  = $this->employees->load($request->employee_number);
            $profile   = $this->profiles ->load($request->profile_id     );
            $user      = $this->users->loadById($request->requester_id   );
            $resources = $this->resources->find([])['rows'];
        }
        catch (\Exception $e) { return new Response(null, [$e->getMessage()]); }


        try {
            $ar = new AccountRequest([
                'requester_id'    => $request->requester_id,
                'employee_number' => $request->employee_number,
                'type'            => 'activate',
                'status'          => 'pending',
                'employee'        => (array)$employee,
                'resources'       => self::generateResourceValues($employee, $profile, $request->questions, $resources)
            ]);
            $id = $this->accounts->save($ar);
        }
        catch (\Exception $e) { return new Response(null, [$e->getMessage()]); }
        return new Response($id);
    }

    private function validate(Request $r): array
    {
        $errors = [];
        if (!$r->employee_number) { $errors[] = 'activate/missingEmployeeNumber'; }
        if (!$r->requester_id   ) { $errors[] = 'activate/missingRequesterId'; }
        if (!$r->profile_id     ) { $errors[] = 'activate/missingProfile'; }
        return $errors;
    }

    private static function generateResourceValues(Employee $employee,
                                                   Profile  $profile,
                                                   array    $questions,
                                                   array    $resources): array
    {
        $values = [];
        foreach ($resources as $r) {
            $class  = $r->class;
            $values[$r->code] = $class::generateValues($employee, $profile, $questions, $values);
        }
        return $values;
    }
}
