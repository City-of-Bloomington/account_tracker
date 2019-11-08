<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);

namespace Web\Resources\Views;

use Domain\Resources\UseCases\Update\Request;
use Domain\Resources\UseCases\Update\Response;
use Web\Block;
use Web\Template;

class UpdateView extends Template
{
    public function __construct(Request $req, ?Response $res=null, string $return_url)
    {
        parent::__construct('default', 'html');

        $this->vars['title'] = $req->id ? $this->_('resources.edit', 'messages') : $this->_('resources.add', 'messages');

        $vars = [
            'title'      => $this->vars['title'],
            'return_url' => $return_url
        ];
        foreach ((array)$req as $k=>$v) {
            $vars[$k] = parent::escape($v);
        }

        $this->blocks = [
            new Block('resources/updateForm.inc', $vars)
        ];
    }
}
