<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);

namespace Web\AccountRequests\Controllers;

use Web\Controller;
use Web\View;

class DeleteController extends Controller
{
    public function __invoke(array $params): View
    {
        $id = !empty($_GET['id']) ? (int)$_GET['id'] : null;

        if ($id) {
            $delete   = $this->di->get('Domain\AccountRequests\UseCases\Delete\Command');
            $response = $delete($id);
            if ($response->errors) {
                $_SESSION['errorMessages'] = $response->errors;
                header('Location: '.View::generateUrl('account_requests.view', ['id'=>$id]));
                exit();
            }
            header('Location: '.View::generateUrl('account_requests.index'));
            exit();
        }
        return new \Web\Views\NotFoundView();
    }
}
