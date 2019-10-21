<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Web\Employees\Views;

use Web\Block;
use Web\Template;
use Web\Paginator;

use Domain\Employees\UseCases\Search\Request;
use Domain\Employees\UseCases\Search\Response;

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
            'employees'    => $response->employees,
            'total'        => $response->total,
            'itemsPerPage' => $itemsPerPage,
            'currentPage'  => $currentPage,
            'firstname'    => parent::escape($request->firstname),
            'lastname'     => parent::escape($request->lastname)
        ];

        $block = $format == 'html' ? 'employees/findForm.inc' : 'employees/list.inc';
        $this->blocks = [new Block($block, $vars)];
    }
}
