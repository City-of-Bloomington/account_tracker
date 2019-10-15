<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);

namespace Domain\AccountRequests\Entities;

class AccountRequest
{
    public $id;
    public $requester_id;
    public $username;
    public $type;
    public $status;

    public $created;
    public $modified;
    public $completed;

    public function __construct(array $data=null)
    {
        if (!empty($data['id'          ])) { $this->id           = (int)$data['id'          ]; }
        if (!empty($data['requester_id'])) { $this->requester_id = (int)$data['requester_id']; }
        if (!empty($data['username'    ])) { $this->username     =      $data['username'    ]; }
        if (!empty($data['type'        ])) { $this->type         =      $data['type'        ]; }
        if (!empty($data['status'      ])) { $this->status       =      $data['status'      ]; }

        if (!empty($data['created'  ])) { $this->created   = new \DateTime($data['created'  ]); }
        if (!empty($data['modified' ])) { $this->modified  = new \DateTime($data['modified' ]); }
        if (!empty($data['completed'])) { $this->completed = new \DateTime($data['completed']); }
    }
}
