<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);

namespace Domain\AccountRequests\UseCases\Info;
use Domain\AccountRequests\Entities\AccountRequest;

class Response
{
    public $account_request;
    public $errors;

    public function __construct(?AccountRequest $request=null, ?array $errors=null)
    {
        $this->account_request = $request;
        $this->errors          = $errors;
    }
}
