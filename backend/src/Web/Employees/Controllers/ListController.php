<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);

namespace Web\Employees\Controllers;

use Domain\Employees\UseCases\Search\Request as SearchRequest;
use Domain\Employees\UseCases\Search\Response as SearchResponse;
use Web\Employees\Views\SearchView;
use Web\Controller;
use Web\View;


class ListController extends Controller
{
    public function __invoke(array $params): View
    {
		$page     =  !empty($_GET['page']) ? (int)$_GET['page'] : 1;
        $search   = $this->di->get('Domain\Employees\UseCases\Search\Command');
        $request  = new SearchRequest($_GET, null, parent::ITEMS_PER_PAGE, $page);
        $response = $request->isEmpty() ? new SearchResponse() : $search($request);

        return new SearchView($request, $response, parent::ITEMS_PER_PAGE, $page);
    }
}
