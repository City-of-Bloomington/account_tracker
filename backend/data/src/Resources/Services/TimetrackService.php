<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);

namespace Site\Resources\Services;

use Domain\Resources\ResourceService;
use Domain\Employees\Entities\Employee;
use Domain\Profiles\Entities\Profile;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Site\HMACService;

class TimetrackService extends HMACService implements ResourceService
{
    /**
     * Loads an employee's account information
     *
     * @param Employee $employee  The employee being looked up
     */
    public function load(Employee $employee): array
    {

        $url = 'https://tomcat2.bloomington.in.gov/timetrack/NewEmployeeService?employee_number='.$employee->number;

        $client   = new Client();
        $request  = new Request('GET', $url);
        $signed   = parent::signRequest($request);
        $response = $client->send($signed);
        $body     = $response->getBody()->__toString();

        $list = json_decode($body, true);
        if (!$list) {
            throw new \Exception('Server did not return json '.__CLASS__);
        }

        return $list;
    }

    /**
     * Creates a new user account
     *
     * @return array  An array of error messages
     */
    public function create(array $account): array
    {
//         $url  = BASE_URL.'/users/update';
//         $post = (array)$account;
//         $post['format'] = 'json';
//
//         $client   = new Client();
//         $headers  = array_merge(parent::HMACHeaders(), ['form_params' => $post]);
//         $request  = new Request('POST', $url, $headers);
//         $signed   = parent::signRequest($request);
//         $response = $client->send($signed);
//
//         $user = json_decode($response->getBody(), true);
//         if (empty($user->id)) {
//             throw new \Exception('unknown error in '.__CLASS__);
//         }
    }

    /**
     * Modifies an existing user account
     */
    public function modify(array $current, array $modified)
    {
    }

    /**
     * Deletes an employee's user account
     */
    public function delete(Employee $employee)
    {
    }

    /**
     * @param Employee $employee
     * @param Profile  $profile
     * @param array    $questions  User input for declared questions
     * @param array    $values     Values already generated from previous resources
     */
    public static function generateValues(Employee $employee,
                                          Profile  $profile,
                                          array    $questions,
                                          array    $values): array
    {
        $values = [
            'username'  => $values['active_directory']['samaccountname'],
            'firstname' => $employee->firstname,
            'lastname'  => $employee->lastname,
            'email'     => $values['active_directory']['mail'],
            'role'      => $profile->resources['account_tracker']['role'],
            'authentication_method' => 'Ldap'
        ];
        return $values;
    }
}
