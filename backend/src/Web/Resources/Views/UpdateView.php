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
            'title' => $this->vars['title'],
            'id'    => $req->id,
            'code'  => parent::escape($req->code),
            'name'  => parent::escape($req->name),
            'type'  => parent::escape($req->type),
            'definition' => $req->definition ? json_encode($req->definition, JSON_PRETTY_PRINT) : '',
            'return_url' => $return_url
        ];

        $this->blocks = [
            new Block('resources/updateForm.inc', $vars)
        ];
    }
}
