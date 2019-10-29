<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);

namespace Web\AccountRequests\Controllers;
use Web\AccountRequests\Views\InfoView;
use Web\Controller;
use Web\View;

class InfoController extends Controller
{
    public function __invoke(array $params): View
    {
        if (!empty($_GET['id'])) {
            $info = $this->di->get('Domain\AccountRequests\UseCases\Info\Command');
            $res  = $info((int)$_GET['id']);
            if ($res->account_request) {
                return new InfoView($res->account_request);
            }

            $_SESSION['errorMessages'] = $res->errors;
        }
        return new \Web\Views\NotFoundView();
    }
}
