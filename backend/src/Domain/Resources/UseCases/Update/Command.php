<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);

namespace Domain\Resources\UseCases\Update;
use Domain\Resources\Entities\ResourceEntity;

class Command
{
    private $repo;

    public function __construct(ResourcesRepository $repository)
    {
        $this->repo = $repository;
    }

    public function __invoke(Request $request): Response
    {
        $errors = $this->validate($request);
        if ($errors) { return new Response(null, $errors); }

        try {
            $id  = $this->repo->save(new ResourceEntity((array)$request));
            $res = new Response($id);
        }
        catch (\Exception $e) {
            $res = new Response(null, [$e->getMessage()]);
        }
        return $res;
    }

    /**
     * Returns an array of errors for the request
     */
    public static function validate(Request $r): array
    {
        $errors = [];
        if (!$r->code      ) { $errors[] = 'resources/missingCode'      ; }
        if (!$r->name      ) { $errors[] = 'missingName'                ; }
        if (!$r->type      ) { $errors[] = 'missingType'                ; }
        if (!$r->definition) { $errors[] = 'resources/missingDefinition'; }
        return $errors;
    }
}
