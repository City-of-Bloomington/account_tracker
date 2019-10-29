<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);

namespace Web\Profiles\Controllers;

use Web\Profiles\Views\ListView;
use Web\Controller;
use Web\View;

class ListController extends Controller
{
    public function __invoke(array $params): View
    {
        $search = $this->di->get('Domain\Profiles\UseCases\Search\Command');
        $res    = $search();
        return new ListView($res);
    }
}
