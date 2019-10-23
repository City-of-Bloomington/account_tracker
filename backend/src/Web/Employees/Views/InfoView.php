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
        $employee = $response->employee;

        $this->vars['title'] = parent::escape("{$employee->firstname} {$employee->lastname}");
		$this->blocks = [
            new Block('employees/info.inc', ['employee'=>$employee, 'resources'=>$response->resources])
        ];
    }
}
