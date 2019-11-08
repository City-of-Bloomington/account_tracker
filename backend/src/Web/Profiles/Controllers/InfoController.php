<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);

namespace Web\Profiles\Controllers;

use Web\Controller;
use Web\View;
use Web\Profiles\Views\InfoView;

class InfoController extends Controller
{
    public function __invoke(array $params): View
    {
        if (!empty($_REQUEST['id'])) {
            $info = $this->di->get('Domain\Profiles\UseCases\Info\Command');
            $res  = $info((int)$_REQUEST['id']);
            if ($res->profile) {
                return new InfoView($res);
            }

            $_SESSION['errorMessages'] = $res->errors;
        }
        return new \Web\Views\NotFoundView();
    }
}
