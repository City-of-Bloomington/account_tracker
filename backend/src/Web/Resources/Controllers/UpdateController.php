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
use Web\Resources\Views\UpdateView;

class UpdateController extends Controller
{
    public function __invoke(array $params): View
    {
        $resource_id = !empty($_REQUEST['id']) ? (int)$_REQUEST['id'] : null;
        $_SESSION['return_url'] = self::defaultReturnUrl($resource_id);

        if (isset($_POST['code'])) {
            $update = $this->di->get('Domain\Resources\UseCases\Update\Command');
            $req    = new UpdateRequest($_POST);
            $res    = $update($req);

            if (!$res->errors) {
                $return_url = $_SESSION['return_url'];
                unset($_SESSION['return_url']);
                header("Location: $return_url");
                exit();
            }
        }
        elseif ($resource_id) {
            $info   = $this->di->get('Domain\Resources\UseCases\Info\Command');
            $ir     = $info($resource_id);
            if ($ir->errors) {
                $_SESSION['errorMessages'] = $ir->errors;
                return new \Web\Views\NotFoundView();
            }
            $req = new UpdateRequest((array)$ir->resourceEntity);
        }
        else {
            $req = new UpdateRequest();
        }

        return new UpdateView($req, isset($res) ? $res : null, $_SESSION['return_url']);
    }

    private static function defaultReturnUrl(?int $resource_id=null): string
    {
        return !empty  ($_REQUEST['return_url'])
            ? urldecode($_REQUEST['return_url'])
            : ($resource_id
                ? View::generateUrl('resources.view', ['id'=>$resource_id])
                : View::generateUrl('resources.index'));
    }
}
