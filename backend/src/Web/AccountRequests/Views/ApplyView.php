<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);

namespace Web\AccountRequests\Views;

use Domain\AccountRequests\UseCases\Apply\Response;

use Web\Block;
use Web\Template;

class ApplyView extends Template
{
    public function __construct(Response $response)
    {
        $format = !empty($_REQUEST['format']) ? $_REQUEST['format'] : 'html';
        parent::__construct('default', $format);

        $this->blocks = [
            new Block('account_requests/apply.inc', ['response' => $response])
        ];
    }
}
