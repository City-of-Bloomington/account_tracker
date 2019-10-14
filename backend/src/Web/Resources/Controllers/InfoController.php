<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);

namespace Web\Resources\Controllers;

use Web\Controller;
use Web\View;
use Web\Resources\Views\InfoView;

class InfoController extends Controller
{
    public function __invoke(array $params): View
    {
        $info = $this->di->get('Domain\Resources\UseCases\Info\Command');
        $res  = $info($params['code']);
        if ($res->resourceEntity) {
            return new InfoView($res);
        }

        $_SESSION['errorMessages'] = $res->errors;
        return new \Web\Views\NotFoundView();
    }
}
