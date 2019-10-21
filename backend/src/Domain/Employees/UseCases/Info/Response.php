<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);

namespace Domain\Employees\UseCases\Info;
use Domain\Employees\Entities\Employee;

class Response
{
    public $employee;
    public $errors = [];

    public function __construct(?Employee $employee=null, ?array $errors=null)
    {
        $this->employee = $employee;
        $this->errors   = $errors;
    }
}
