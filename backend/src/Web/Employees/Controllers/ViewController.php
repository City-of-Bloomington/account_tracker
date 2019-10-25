<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);

namespace Web\Employees\Controllers;

use Domain\Employees\Entities\Employee;
use Domain\Employees\UseCases\Info\Request as InfoRequest;
use Web\Authentication\Auth;
use Web\Employees\Views\InfoView;
use Web\Controller;
use Web\View;

class ViewController extends Controller
{
    public function __invoke(array $parms): View
    {
        if (!empty($_REQUEST['id'])) {
            $auth = $this->di->get('Web\Authentication\AuthenticationService');
            $info = $this->di->get('Domain\Employees\UseCases\Info\Command');
            $user = Auth::getAuthenticatedUser($auth);
            $res  = $info((int)$_REQUEST['id'], $user);
            if ($res->employee) {
                return new InfoView($res);
            }
            else {
                $_SESSION['errorMessages'] = $res->errors;
            }
        }
        return new \Web\Views\NotFoundView();
    }
}
