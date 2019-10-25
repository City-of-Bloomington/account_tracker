<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);

namespace Domain\Resources\UseCases\EmployeeInfo;

class Command
{
    public function __construct(ResourceEntity $resource)
    {
    }

    public function __invoke(Employee $employee): Response
    {

    }
}
