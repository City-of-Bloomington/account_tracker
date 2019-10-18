<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);

namespace Web\AccountRequests\Views;

use Domain\AccountRequests\UseCases\Search\Request;
use Domain\AccountRequests\UseCases\Search\Response;
use Web\Block;
use Web\Template;

class SearchView extends Template
{
    public function __construct(Request  $request,
                                Response $response,
                                int      $itemsPerPage,
                                int      $currentPage)
    {
        $format = !empty($_REQUEST['format']) ? $_REQUEST['format'] : 'html';
        parent::__construct('default', $format);

        if ($response->errors) {
            $_SESSION['errorMessages'] = $response->errors;
        }

        $vars = [
            'account_requests' => $response->account_requests,
            'total'            => $response->total,
            'itemsPerPage'     => $itemsPerPage,
            'currentPage'      => $currentPage,
        ];
        $block = $this->outputFormat == 'html'
               ? 'account_requests/findForm.inc'
               : 'account_requests/list.inc';
        $this->blocks = [ new Block($block, $vars) ];
    }

}
