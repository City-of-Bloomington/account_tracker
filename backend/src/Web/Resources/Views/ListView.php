<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);

namespace Web\Resources\Views;

use Domain\Resources\UseCases\List\Response;
use Web\Block;
use Web\Template;

class ListView extends Template
{
    public function __construct(Response $response)
    {
        $format = !empty($_REQUEST['format']) ? $_REQUEST['format'] : 'html';
        parent::__construct('default', $format);

        $vars = [
            'resources' => $response->resources
        ];

        $this->blocks = [
            new Block('resources/list.inc', $vars)
        ];
    }
}
