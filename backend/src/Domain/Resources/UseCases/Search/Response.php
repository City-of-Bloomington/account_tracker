<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);

namespace Domain\Resources\UseCases\Search;

class Response
{
    public $resources = [];
    public $errors    = [];

    public function __construct(?array $resources=null, ?array $errors=null)
    {
        $this->resources = $resources;
        $this->errors    = $errors;
    }
}
