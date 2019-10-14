<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);

namespace Domain\Resources\UseCases\Info;
use Domain\Resources\Entities\ResourceEntity;

class Response
{
    public $resourceEntity;
    public $errors;

    public function __construct(?ResourceEntity $entity=null, ?array $errors=null)
    {
        $this->resourceEntity = $entity;
        $this->errors         = $errors;
    }
}
