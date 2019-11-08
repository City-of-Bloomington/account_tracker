<?php
/**
 * @copyright 2016-2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Web\Employees\Views;

use Web\Block;
use Web\Template;

use Domain\Employees\UseCases\Info\Response as InfoResponse;

class InfoView extends Template
{
    public function __construct(InfoResponse $response)
    {
        $format = !empty($_REQUEST['format']) ? $_REQUEST['format'] : 'html';
        parent::__construct('default', $format);

        if ($response->errors) {
            $_SESSION['errorMessages'] = $response->errors;
        }

        $this->vars['title'] = parent::escape("{$response->employee->firstname} {$response->employee->lastname}");

        $vars = [
            'actions'      => self::generateActionLinks($response),
            'infoResponse' => $response
        ];
        if ($this->outputFormat == 'html') {
            if (isset($vars['actions']['self'])) {
                unset($vars['actions']['self']);
            }
        }

		$this->blocks = [
            new Block('employees/info.inc', $vars)
        ];
    }

    private static function isNewHire(InfoResponse $response): bool
    {
        foreach ($response->resources as $r) {
            if ($r['values']) { return false; }
        }
        return true;
    }

    private static function generateActionLinks(InfoResponse $response): array
    {
        $actions = [];
        if (parent::isAllowed('employees', 'view')) {
            $actions['self'] = [
                'href' => parent::generateUri('employees.view', ['id'=>$response->employee->number])
            ];
        }
        if (self::isNewHire($response) && parent::isAllowed('employees', 'activate')) {
            $actions['activate'] = [
                'href' => parent::generateUri('employees.activate', ['employee_number'=>$response->employee->number])
            ];
        }
        return $actions;
    }
}
