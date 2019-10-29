<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);

namespace Web\Profiles\Views;

use Domain\Profiles\UseCases\Search\Response;
use Web\Block;
use Web\Template;

class ListView extends Template
{
    public function __construct(Response $response)
    {
        $format = !empty($_REQUEST['format']) ? $_REQUEST['format'] : 'html';
        parent::__construct('default', $format);

        if ($response->errors) {
            $_SESSION['errorMessages'] = $response->errors;
        }

        $vars = $this->outputFormat == 'html'
              ? ['profiles' => $response->profiles]
              : ['response' => $response          ];

        $this->blocks = [ new Block('profiles/list.inc', $vars) ];
    }
}
