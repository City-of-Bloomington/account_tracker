<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);

namespace Domain\AccountRequests\UseCases\Update;

class Request
{
    public $id;
    public $requester_id;    // The person making the request
    public $employee_number; // The employee to create accounts for
    public $type;
    public $status;

    public $employee  = [];  // JSON array
    public $resources = [];  // JSON array

    public function __construct(array $data=null)
    {
        if (!empty($data['id'             ])) { $this->id              = (int)$data['id'             ]; }
        if (!empty($data['requester_id'   ])) { $this->requester_id    = (int)$data['requester_id'   ]; }
        if (!empty($data['employee_number'])) { $this->employee_number = (int)$data['employee_number']; }

        if (!empty($data['type'  ])) { $this->type   = $data['type'  ]; }
        if (!empty($data['status'])) { $this->status = $data['status']; }

        if (!empty($data['employee'])) {
            $this->employee =    is_array($data['employee'])
                            ?             $data['employee']
                            : json_decode($data['employee'], true);
        }

        if (!empty($data['resources'])) {
            $this->resources =    is_array($data['resources'])
                             ?             $data['resources']
                             : json_decode($data['resources'], true);
        }
    }
}
