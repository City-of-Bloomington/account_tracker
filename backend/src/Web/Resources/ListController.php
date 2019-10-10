<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);

namespace Web\Resources;

use Web\Controller;
use Web\View;
use Web\Resources\Views\ListView;

class ListController extends Controller
{
    public function __invoke(array $params): View
    {
        $list = $this->di->get('Domain\Resources\UseCases\List\Command');
        $res  = $list();

        return new ListView($res);
    }
}
