<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);

namespace Domain\Profiles\UseCases\Info;
use Domain\Profiles\DataStorage\ProfilesRepository;

class Command
{
    private $repo;

    public function __construct(ProfilesRepository $repository)
    {
        $this->repo = $repository;
    }

    public function __invoke(int $id): Response
    {
        try {
            return new Response($this->repo->load($id));
        }
        catch (\Exception $e) {
            return new Response(null, [$e->getMessage()]);
        }
    }
}
