<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);

namespace Domain\Profiles\UseCases\Update;
use Domain\Profiles\DataStorage\ProfilesRepository;
use Domain\Profiles\Entities\Profile;

class Command
{
    private $repo;

    public function __construct(ProfilesRepository $repository)
    {
        $this->repo = $repository;
    }

    public function __invoke(Request $request): Response
    {
        $errors = $this->validate($request);
        if ($errors) { return new Response(null, $errors); }

        try {
            $id  = $this->repo->save($request);
            $res = new Response($id);
        }
        catch (\Exception $e) {
            $res = new Response(null, [$e->getMessage()]);
        }
        return $res;
    }

    private function validate($request): array
    {
        $errors = [];
        if (!$request->name     ) { $errors[] = 'missingName'; }
        if (!$request->resources) { $errors[] = 'missingResources'; }

        if ($request->questions && !json_decode($request->questions)) {
            $errors[] = 'invalidJSON/questions';
        }

        if (!json_decode($request->resources)) {
            $errors[] = 'invalidJSON/resources';
        }
        return $errors;
    }
}
