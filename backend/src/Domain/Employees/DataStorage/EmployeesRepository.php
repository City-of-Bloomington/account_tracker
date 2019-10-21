<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Domain\Employees\DataStorage;

use Domain\Employees\Entities\Employee;
use Domain\Employees\UseCases\Search\Request as SearchRequest;

interface EmployeesRepository
{
    public function search(SearchRequest $req): array;
}
