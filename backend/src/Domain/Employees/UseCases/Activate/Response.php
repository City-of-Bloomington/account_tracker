<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);

namespace Domain\Employees\UseCases\Activate;

class Response
{
    public $id;
    public $errors;

    public function __construct(?int $id=null, ?array $errors=null)
    {
        $this->id     = $id;
        $this->errors = $errors;
    }
}
