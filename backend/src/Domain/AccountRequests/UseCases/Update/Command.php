<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);

namespace Domain\AccountRequests\UseCases\Update;

class Command
{
    private $repo;

    public function __construct(AccountRequestsRepository $repository)
    {
        $this->repo = $repository;
    }

    public function __invoke(Request $request): Response
    {
        $errors = $this->validate($request);
        if ($errors) { return new Response(null, $errors); }

        $ar = new AcccountRequest((array)$request);
        try {
            $id = $this->repo->save($ar);
            return new Response($id);
        }
        catch (\Exception $e) {
            return new Response(null, [$e->getMessage()]);
        }
    }

    private function validate(Request $req): array
    {
        $errors = [];
        if (!$req->requester_id   ) { $errors[] = 'account_requests/missingRequester';      }
        if (!$req->employee_number) { $errors[] = 'account_requests/missingEmployeeNumber'; }
        if (!$req->employee       ) { $errors[] = 'account_requests/missingEmployee';       }
        if (!$req->resources      ) { $errors[] = 'account_requests/missingResources';      }
        return $errors;
    }
}
