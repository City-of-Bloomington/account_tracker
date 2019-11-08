<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);

namespace Domain\AccountRequests\UseCases\Delete;
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
            $this->repo->delete($id);
            return new Response();
        }
        catch (\Exception $e) {
            return new Response([$e->getMessage()]);
        }
    }
}
