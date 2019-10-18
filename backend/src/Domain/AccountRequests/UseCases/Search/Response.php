<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);

namespace Domain\AccountRequests\UseCases\Search;

class Response
{
    public $account_requests = [];
    public $total            = 0;
    public $errors           = [];

    public function __construct(?array $account_requests=null, ?int $total=0, ?array $errors=[])
    {
        $this->account_requests = $account_requests;
        $this->total            = $total;
        $this->errors           = $errors;
    }
}
