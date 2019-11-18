<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);

namespace Web\Profiles\Views;

use Domain\Profiles\UseCases\Update\Request;
use Domain\Profiles\UseCases\Update\Response;
use Web\Block;
use Web\Template;

class UpdateView extends Template
{
    public function __construct(Request $req, ?Response $res=null, string $return_url)
    {
        parent::__construct('default', 'html');

        $this->vars['title'] = $req->id ? $this->_('profiles.edit', 'messages') : $this->_('profiles.add', 'messages');
        if (!empty($res->errors)) { $_SESSION['errorMessages'] = $res->errors; }

        $vars = [
            'title'      => $this->vars['title'],
            'return_url' => $return_url
        ];
        foreach ((array)$req as $k=>$v) {
            $vars[$k] = is_array($v)
                      ? json_encode($v, JSON_PRETTY_PRINT)
                      : parent::escape($v);
        }

        $this->blocks = [
            new Block('profiles/updateForm.inc', $vars)
        ];
    }
}
