<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);

namespace Domain\AccountRequests\UseCases\Apply;

class Request
{
    public $id;       // The account request ID to apply
    public $username; // The user creating the accounts

    public function __construct(?array $data=null)
    {
        if (!empty($data['id'      ])) { $this->id  = (int)$data['id'      ]; }
        if (!empty($data['username'])) { $this->username = $data['username']; }
    }
}
