<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);

namespace Web\AccountRequests\Controllers;

use Domain\AccountRequests\UseCases\Apply\Request as ApplyRequest;

use Web\Authentication\Auth;
use Web\Controller;
use Web\View;

class Apply extends Controller
{
    public function __invoke(array $params): View
    {
        $id = !empty($_REQUEST['id']) ? (int)$_REQUEST['id'] : null;

        if ($id) {
            $auth  = $this->di->get('Web\Authentication\AuthenticationService');
            $user  = Auth::getAuthenticatedUser($auth);

            $apply = $this->di->get('Domain\AccountRequests\UseCases\Apply\Command');
            $req   = new ApplyRequest(['id' => $id, 'username' => $user->username]);
            $res   = $apply($req);

            print_r($res);
            exit();
        }
        return new \Web\Views\NotFoundView();
    }
}
