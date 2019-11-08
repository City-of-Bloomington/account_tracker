<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);

namespace Domain\AccountRequests\UseCases\Apply;

use Domain\AccountRequests\Metadata;
use Domain\AccountRequests\DataStorage\AccountRequestsRepository;
use Domain\AccountRequests\Entities\AccountRequest;
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

    public function __invoke(Request $applyRequest): Response
    {
        try {
            $accountRequest = $this->accounts->load($applyRequest->request_id);
            $employee       = new Employee($accountRequest->employee);
            $resource       = $this->resources->loadByCode($applyRequest->resource_code);
        }
        catch (\Exception $e) {
            return new Response($employee ?? null,
                                $applyRequest->resource_code,
                                null,
                                [$e->getMessage()]);
        }

        $errors = $this->validate($applyRequest, $accountRequest);
        if ($errors) {
            return new Response($employee,
                                $applyRequest->resource_code,
                                null,
                                $errors);
        }

        $service         = $resource->serviceFactory($applyRequest->username);
        $requestedValues = $accountRequest->resources[$resource->code];
        $currentValues   = $service->load($employee);
        $response        = $currentValues ? $service->modify($currentValues, $requestedValues)
                                          : $service->create($requestedValues);
        $newValues       = $service->load($employee);

        return new Response($employee,
                            $resource->code,
                            $newValues ?? [],
                            $response['errors'] ?? null);
    }

    private function validate(Request $applyRequest, AccountRequest $accountRequest): array
    {
        $errors = [];
        if (!array_key_exists($applyRequest->resource_code, $accountRequest->resources)) {
            $errors[] = 'account_requests/invalidResource';
        }

        if ($accountRequest->status == Metadata::STATUS_COMPLETED) {
            $errors[] = 'account_requests/invalidStatus';
        }
        return $errors;
    }
}
