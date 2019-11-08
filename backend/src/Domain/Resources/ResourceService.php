<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);

namespace Domain\Resources;
use Domain\Employees\Entities\Employee;

interface ResourceService
{
    public function load(Employee $employee): array;

    /**
     * @return array   An array of error messages
     */
    public function create(array $account): array;
    public function modify(Employee $employee, array $account);
    public function delete(Employee $employee);
}
