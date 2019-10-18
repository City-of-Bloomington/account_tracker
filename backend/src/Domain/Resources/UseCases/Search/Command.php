<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);

namespace Domain\Resources\UseCases\Search;

use Domain\Resources\DataStorage\ResourcesRepository;

class Command
{
    private $repo;

    public function __construct(ResourcesRepository $repository)
    {
        $this->repo = $repository;
    }

    public function __invoke(): Response
    {
        try {
            $result = $this->repo->find([]);
            return new Response($result['rows'], $result['total']);
        }
        catch (\Exception $e) {
            return new Response(null, null, [$e->getMessage]);
        }
    }
}
