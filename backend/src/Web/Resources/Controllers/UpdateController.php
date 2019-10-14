<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);

namespace Web\Resources\Controllers;

use Domain\Resources\UseCases\Update\Request as UpdateRequest;
use Web\Controller;
use Web\View;

class UpdateController extends Controller
{
    public function __invoke(array $params): View
    {
        if (isset($_POST['name'])) {
            $update = $this->di->get('Domain\Resources\UseCases\Update\Command');
            $req    = new UpdateRequest($_POST);
            $res    = $update($req);

            if (!$res->errors) {
                header('Location: '.View::generateUrl('resources.index'));
                exit();
            }
        }
        elseif (!empty($_REQUEST['id'])) {
            $info   = $this->id->get('Domain\Resources\UseCases\Info\Command');
            $ir     = $info((int)$_REQUEST['id']);
            if ($ir->errors) {
                $_SESSION['errorMessages'] = $ir->errors;
                return new \Web\Views\NotFoundView();
            }
            $req = new UpdateRequest($ir->resourceEntity)
        }
        else {
            $req = new UpdateRequest();
        }

        return new UpdateView($req, isset($res) ? $res : null);
    }
}
