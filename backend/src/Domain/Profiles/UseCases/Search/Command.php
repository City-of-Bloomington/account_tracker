<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);

namespace Domain\Profiles\UseCases\Search;

use Domain\Profiles\DataStorage\ProfilesRepository;

class Command
{
    private $repo;

    public function __construct(ProfilesRepository $repository)
    {
        $this->repo = $repository;
    }

    public function __invoke(): Response
    {
        try {
            $result = $this->repo->find();
            return new Response($result['rows']);
        }
        catch (\Exception $e) {
            return new Response([], [$e->getMessage()]);
        }
    }
}
