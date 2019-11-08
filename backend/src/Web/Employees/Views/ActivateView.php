<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);

namespace Web\Employees\Views;

use Domain\Employees\UseCases\Activate\Request as ActivateRequest;
use Domain\Employees\Entities\Employee;
use Domain\Profiles\Entities\Profile;

use Web\Block;
use Web\Template;

class ActivateView extends Template
{
    public function __construct(ActivateRequest $request,
                                Employee        $employee,
                                array           $profiles,
                                ?Profile        $profile)
    {
        $format = !empty($_REQUEST['format']) ? $_REQUEST['format'] : 'html';
        parent::__construct('default', $format);

        $vars = [
            'employee_number' => $request->employee_number,
            'profile_id'      => $request->profile_id,
            'questions'       => $request->questions,
            'employee'        => $employee,
            'profiles'        => $profiles,
            'profile'         => $profile
        ];


        $block = $profile
               ? 'employees/activate/activateForm.inc'
               : 'employees/activate/chooseProfileForm.inc';

        $this->blocks = [ new Block($block, $vars) ];
    }
}
