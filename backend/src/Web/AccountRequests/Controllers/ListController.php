<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);

namespace Web\AccountRequests\Controllers;

use Domain\AccountRequests\UseCases\Search\Request as SearchRequest;
use Web\Controller;
use Web\View;
use Web\AccountRequests\Views\SearchView;

class ListController extends Controller
{
    public function __invoke(array $params): View
    {
		$page     =  !empty($_GET['page']) ? (int)$_GET['page'] : 1;
        $search   = $this->di->get('Domain\AccountRequests\UseCases\Search\Command');
        $request  = new SearchRequest($_GET, null, parent::ITEMS_PER_PAGE, $page);
        $response = $search($request);

        return new SearchView($request, $response, parent::ITEMS_PER_PAGE, $page);
    }
}
