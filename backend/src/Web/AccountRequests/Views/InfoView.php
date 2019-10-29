<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);

namespace Web\AccountRequests\Views;
use Domain\AccountRequests\Entities\AccountRequest;

use Web\Block;
use Web\Template;

class InfoView extends Template
{
    public function __construct(AccountRequest $request)
    {
        $format = !empty($_REQUEST['format']) ? $_REQUEST['format'] : 'html';
        parent::__construct('default', $format);

        $vars = [];
        if ($this->outputFormat == 'html') {
            foreach ((array)$request as $k=>$v) {
                $vars[$k] = is_string($v) ? parent::escape($v) : $v;
            }
        }
        else {
            $vars['account_request'] = $request;
        }

        $this->blocks = [
            new Block('account_requests/info.inc', $vars)
        ];
    }
}
