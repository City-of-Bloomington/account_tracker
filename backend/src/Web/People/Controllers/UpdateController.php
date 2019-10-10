<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);

namespace Web\People\Controllers;

use Domain\People\UseCases\Update\Request as UpdateRequest;
use Web\People\Views\UpdateView;
use Web\Controller;
use Web\View;


class UpdateController extends Controller
{
    public function __invoke(array $params): View
    {
        if (empty($_SESSION['return_url'])) {
            $_SESSION['return_url'] = !empty($_REQUEST['return_url'])
                ? urldecode($_REQUEST['return_url'])
                : View::generateUrl('people.view', ['id'=>$response->id]);
        }

        if (isset($_POST['firstname'])) {
            $update  = $this->di->get('Domain\People\UseCases\Update\Command');
            $req = new UpdateRequest($_POST);
            $res = $update($req);

            if (!$res->errors) {
                $return_url = $_SESSION['return_url'];
                unset($_SESSION['return_url']);
                header("Location: $return_url");
                exit();
            }
        }
        elseif (!empty($_REQUEST['id'])) {
            $info = $this->di->get('Domain\People\UseCases\Info\Command');
            $ir   = $info((int)$_REQUEST['id']);
            if ($ir->errors) {
                $_SESSION['errorMessages'] = $ir->errors;
                return new \Web\Views\NotFoundView();
            }
            $req = new UpdateRequest((array)$ir->person);
        }
        else {
            $req = new UpdateRequest();
        }

        return new UpdateView($req, isset($res) ? $res : null, $_SESSION['return_url']);
    }
}
