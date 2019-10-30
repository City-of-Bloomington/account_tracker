<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);

namespace Web\Employees\Controllers;

use Domain\Employees\UseCases\Activate\Request as ActivateRequest;
use Domain\Profiles\Entities\Profile;
use Web\Employees\Views\ActivateView;
use Web\Authentication\Auth;
use Web\Controller;
use Web\View;

class ActivateController extends Controller
{
    public function __invoke(array $params): View
    {
        $auth        = $this->di->get('Web\Authentication\AuthenticationService');
        $currentUser = Auth::getAuthenticatedUser($auth);

        $employee_number = !empty( $params['employee_number'])
                            ? (int)$params['employee_number']
                            : (!empty( $_REQUEST['employee_number'])
                                ? (int)$_REQUEST['employee_number']
                                : null);
        if (!$employee_number) { return new \Web\Views\NotFoundView(); }

        $profile_id = !empty($_REQUEST['profile_id']) ? (int)$_REQUEST['profile_id'] : null;
        $questions  = !empty($_REQUEST['questions' ]) ?      $_REQUEST['questions' ] : null;


        if (!empty($_POST['profile_id']) && !empty($_POST['employee_number'])) {
            $_POST['requester_id'] = $currentUser->id;
            $activate = $this->di->get('Domain\Employees\UseCases\Activate\Command');
            $req      = new ActivateRequest($_POST);
            $response = $activate($req);
            if (!$response->errors) {
                if (!empty($_REQUEST['format']) && $_REQUEST['fornat']!='html') {
                    $info = $this->di->get('Domain\AccountRequests\UseCases\Info\Command');
                    $ar   = $info($response->id);
                    return new \Web\AccountRequests\Views\InfoView($ar);
                }
                else {
                    header('Location: '.View::generateUrl('account_requests.view', ['id'=>$response->id]));
                    exit();
                }
            }
            else {
                $_SESSION['errorMessages'] = $response->errors;
            }

        }

        $employeeInfo  = $this->di->get('Domain\Employees\UseCases\Info\Command');
        $profileSearch = $this->di->get('Domain\Profiles\UseCases\Search\Command');

        $employee      = $employeeInfo($employee_number, $currentUser);
        $profiles      = $profileSearch();
        $profile       = $profile_id ? self::getProfile($profile_id, $profiles->profiles) : null;

        if ($employee->employee) {
            $request  = new ActivateRequest([
                'employee_number' => $employee_number,
                'profile_id'      => $profile_id,
                'questions'       => $questions,
                'employee'        => (array)$employee->employee
            ]);
            return new ActivateView($request,
                                    $employee->employee,
                                    $profiles->profiles,
                                    $profile);
        }
        else {
            $_SESSION['errorMessages'] = $employee->errors;
        }
    }

    /**
     * Since we have to load all the profiles anyway, we can save a
     * database call by looking for the chosen profile in the already loaded
     * array of profiles.
     */
    private static function getProfile(int $id, array $profiles): Profile
    {
        foreach ($profiles as $p) {
            if ($p->id == $id) { return $p; }
        }
    }
}
