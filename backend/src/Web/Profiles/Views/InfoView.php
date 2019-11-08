<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);

namespace Web\Profiles\Views;

use Domain\Profiles\UseCases\Info\Response;
use Web\Block;
use Web\Template;

class InfoView extends Template
{
    public function __construct(Response $res)
    {
        $format = !empty($_REQUEST['format']) ? $_REQUEST['format'] : 'html';
        parent::__construct('default', $format);

        if ($this->outputFormat == 'html') {
            foreach ((array)$res->profile as $f=>$v) {
                $vars[$f] = is_array($v) ? $v : parent::escape($v);
            }
        }
        else {
            $vars['profile'] = $res->profile;
        }

        $this->blocks = [
            new Block('profiles/info.inc', $vars)
        ];
    }
}
