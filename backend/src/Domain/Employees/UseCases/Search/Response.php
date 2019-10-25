<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Domain\Employees\UseCases\Search;

class Response
{
    public $employees = [];
    public $errors = [];
    public $total  = 0;

    public function __construct(?array $employees=null, int $total=null, array $errors=null)
    {
        $this->employees = $employees;
        $this->total     = $total;
        $this->errors    = $errors;
    }
}
