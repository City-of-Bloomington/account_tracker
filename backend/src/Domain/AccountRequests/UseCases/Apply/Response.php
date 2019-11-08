<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);

namespace Domain\AccountRequests\UseCases\Apply;
use Domain\Employees\Entities\Employee;

class Response
{
    public $employee;
    public $resource_code;
    public $resource_values;
    public $errors;

    public function __construct(?Employee $employee        = null,
                                ?string   $resource_code   = null,
                                ?array    $resource_values = null,
                                ?array    $errors          = null)
    {
        $this->employee        = $employee;
        $this->resource_code   = $resource_code;
        $this->resource_values = $resource_values;
        $this->errors          = $errors;
    }
}
