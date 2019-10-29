<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);

namespace Domain\AccountRequests\UseCases\Info;
use Domain\AccountRequests\DataStorage\AccountRequestsRepository;

class Command
{
    private $repo;

    public function __construct(AccountRequestsRepository $repository)
    {
        $this->repo = $repository;
    }

    public function __invoke(int $id): Response
    {
        try {
            $ar = $this->repo->load($id);
            if ($ar) {
                return new Response($ar);
            }
            throw new \Exception('account_requests/unknown');
        }
        catch (\Exception $e) {
            return new Response(null, [$e->getMessage()]);
        }
    }
}
