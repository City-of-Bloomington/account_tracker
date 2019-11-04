<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);

namespace Domain\AccountRequests\UseCases\Apply;

use Domain\AccountRequests\Metadata;
use Domain\AccountRequests\DataStorage\AccountRequestsRepository;
use Domain\Employees\Entities\Employee;
use Domain\Resources\DataStorage\ResourcesRepository;
use Domain\ResourceRequests\DataStorage\ResourceRequestsRepository;

class Command
{
    private $accounts;
    private $resources;

    public function __construct(AccountRequestsRepository  $accounts,
                                ResourcesRepository        $resources)
    {
        $this->accounts  = $accounts;
        $this->resources = $resources;
    }

    public function __invoke(Request $request): Response
    {
        try {
            $account   = $this->accounts->load($request->id);
            $employee  = new Employee($account->employee);
            $resources = $this->resources->find([])['rows'];
        }
        catch (\Exception $e) { return new Response(null, null, [$e->getMessage()]); }

        $errors = $this->validate($request);
        if ($errors) {
            return new Response($employee, $values, $errors);
        }

        $values = [];
        $errors = [];
        foreach ($resources as $r) {
            if (isset($account->resources[$r->code])) {
                try {
                    $service = $r->serviceFactory($request->username);
                    $service->create($account->resources[$r->code]);
                    $values[$r->code] = $service->load($employee);
                }
                catch (\Exception $e) {
                    $errors[] = $e->getMessage();
                }
            }
        }

        $this->accounts->saveStatus($account->id, Metadata::STATUS_COMPLETED);

        return new Response($employee, $values, $errors);
    }

    private function validate(Request $r): array
    {
        $errors = [];
        if ($request->status == Metadata::STATUS_COMPLETED) {
            $errors[] = 'account_requests/invalidStatus';
        }
        return $errors;
    }
}
