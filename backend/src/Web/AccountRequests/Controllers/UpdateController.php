<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);

namespace Web\AccountRequests\Controllers;

use Domain\AccountRequests\UseCases\Update\Request as UpdateRequest;
use Web\AccountRequests\Views\UpdateView;
use Web\Authentication\Auth;
use Web\Controller;
use Web\View;

class UpdateController extends Controller
{
    public function __invoke(array $params): View
    {
        $auth        = $this->di->get('Web\Authentication\AuthenticationService');
        $currentUser = Auth::getAuthenticatedUser($auth);

        if (isset($_POST['employee_number'])) {
            $update         = $this->di->get('Domain\AccountRequests\UseCases\Update\Command');
            $updateRequest  = new UpdateRequest($_POST);
            if (!$updateRequest->requester_id) {
                 $updateRequest->requester_id = $currentUser->id;
            }

            $response       = $update($updateRequest);
            if (!$response->errors) {
                if (empty($_REQUEST['format']) || $_REQUEST['format']=='html') {
                    header('Location: '.View::generateUrl('account_requests.view', ['id'=>$id]));
                    exit();
                }
            }
            $_SESSION['errorMessages'] = $response->errors;
        }

        if (!isset($updateRequest)) {
            $id   = !empty($_REQUEST['id']) ? (int)$_REQUEST['id'] : null;
            if ($id) {
                $info = $this->di->get('Domain\AccountRequests\UseCases\Info\Command');
                $res  = $info($id);
                if ($res->errors) {
                    return new \Web\Views\NotFoundView();
                }
                $updateRequest = new UpdateRequest((array)$res->account_request);
            }
            else {
                $updateRequest = new UpdateRequest(['requester_id'=>$currentUser->id]);
            }
        }

        return new UpdateView($updateRequest, isset($response) ? $response : null);
    }
}
