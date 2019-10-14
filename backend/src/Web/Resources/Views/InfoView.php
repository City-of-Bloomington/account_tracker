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

        $vars = [
            'code' => parent::escape($res->resourceEntity->code),
            'name' => parent::escape($res->resourceEntity->name),
            'type' => parent::escape($res->resourceEntity->type),
            'definition' => $res->resourceEntity->definition
        ];

        $this->blocks = [
            new Block('resources/info.inc', $vars)
        ];
    }
}
