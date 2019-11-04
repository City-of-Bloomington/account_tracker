<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);

namespace Domain\AccountRequests\UseCases\Apply;

class Response
{
    public $resources;
    public $errors;

    public function __construct(array $resources, array $errors)
    {
        $this->resources = $resources;
        $this->errors    = $errors;
    }
}
