<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);

namespace Web\AccountRequests\Views;

use Domain\AccountRequests\UseCases\Update\Request  as UpdateRequest;
use Domain\AccountRequests\UseCases\Update\Response as UpdateResponse;

use Web\Block;
use Web\Template;

class UpdateView extends Template
{
    public function __construct( UpdateRequest  $request,
                                ?UpdateResponse $response=null)
    {
        $format = !empty($_REQUEST['format']) ? $_REQUEST['format'] : 'html';
        parent::__construct('default', $format);

        if ($response && $response->errors) {
            $_SESSION['errorMessages'] = $response->errors;
        }

        $vars['title'] = $request->id ? $this->_('account_request_edit') : $this->_('account_request_add');

        $vars = [];
        if ($this->outputFormat == 'html') {
            foreach ((array)$request as $k=>$v) {
                $vars[$k] = is_string($v) ? parent::escape($v) : $v;
            }
        }
        else {
            $vars = ['request' => $request, 'response' => $response];
        }

        $this->blocks = [
            new Block('account_requests/updateForm.inc', $vars)
        ];
    }
}
