<?php
/**
 * Apply an account request to an external system
 *
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);

namespace Web\AccountRequests\Controllers;

use Domain\AccountRequests\UseCases\Apply\Request as ApplyRequest;
use Web\ACcountRequests\Views\ApplyView;

use Web\Authentication\Auth;
use Web\Controller;
use Web\View;

class Apply extends Controller
{
    public function __invoke(array $params): View
    {
        $auth    = $this->di->get('Web\Authentication\AuthenticationService');
        $user    = Auth::getAuthenticatedUser($auth);

        $apply   = $this->di->get('Domain\AccountRequests\UseCases\Apply\Command');
        $request = new ApplyRequest(['request_id'    => $params['request_id'   ],
                                     'resource_code' => $params['resource_code'],
                                     'username'      => $user->username]);
        $response = $apply($request);

        if (!empty($_REQUEST['format']) && $_REQUEST['format']!='html') {
            return new ApplyView($response);
        }

        if ($response->errors) {
            $_SESSION['errorMessages'] = $response->errors;
        }
        header('Location: '.View::generateUrl('account_requests.view', ['id'=>$params['request_id']]));
        exit();
    }
}
