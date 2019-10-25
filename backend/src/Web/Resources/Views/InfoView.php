<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);

namespace Web\Resources\Views;

use Domain\Resources\UseCases\Info\Response;
use Web\Block;
use Web\Template;

class InfoView extends Template
{
    public function __construct(Response $res)
    {
        $format = !empty($_REQUEST['format']) ? $_REQUEST['format'] : 'html';
        parent::__construct('default', $format);

        if ($this->outputFormat == 'html') {
            foreach ((array)$res->resourceEntity as $f=>$v) {
                $vars[$f] = $f=='fields' ? $v : parent::escape($v);
            }
        }
        else {
            $vars['resourceEntity'] = $res->resourceEntity;
        }

        $this->blocks = [
            new Block('resources/info.inc', $vars)
        ];
    }
}
