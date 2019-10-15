<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);

namespace Domain\AccountRequests\UseCases\Search;

class Request
{
    public $id;
    public $requester_id;
    public $username;
    public $type;
    public $status;

    public $itemsPerPage;
    public $currentPage;

    public function __construct(array $data=null, ?int $itemsPerPage=null, ?int $currentPage=null)
    {
        if (!empty($data['id'            ])) { $this->id           = (int)$data['id'          ]; }
        if (!empty($data['requesterer_id'])) { $this->requester_id = (int)$data['requester_id']; }
        if (!empty($data['username'      ])) { $this->username     =      $data['username'    ]; }
        if (!empty($data['type'          ])) { $this->type         =      $data['type'        ]; }
        if (!empty($data['status'        ])) { $this->status       =      $data['status'      ]; }

        if ($itemsPerPage) { $this->itemsPerPage = $itemsPerPage; }
        if ($currentPage ) { $this->currentPage  = $currentPage;  }
    }
}
