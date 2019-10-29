<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);

namespace Domain\Employees\UseCases\Activate;

use Domain\Employees\Entities\Employee;

class Request
{
    public $employee_number;
    public $requester_id;
    public $profile_id;

    public $questions = [];

    public function __construct(?array $data=null)
    {
        if (!empty($data['employee_number'])) { $this->employee_number = (int)$data['employee_number']; }
        if (!empty($data['requester_id'   ])) { $this->requester_id    = (int)$data['requester_id'   ]; }
        if (!empty($data['profile_id'     ])) { $this->profile_id      = (int)$data['profile_id'     ]; }
        if (!empty($data['questions'      ])) { $this->questions       =      $data['questions'      ]; }
    }
}
