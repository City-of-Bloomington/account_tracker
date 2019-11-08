<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);

namespace Domain\AccountRequests\UseCases\Apply;

class Request
{
    public $request_id;    // The account request ID to apply
    public $resource_code; // The resource to grant access to
    public $username;      // The user creating the accounts

    public function __construct(?array $data=null)
    {
        if (!empty($data['request_id'   ])) { $this->request_id = (int)$data['request_id'   ]; }
        if (!empty($data['resource_code'])) { $this->resource_code =   $data['resource_code']; }
        if (!empty($data['username'     ])) { $this->username      =   $data['username'     ]; }
    }
}
