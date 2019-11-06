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
use GuzzleHttp\Psr7;
use Web\Authentication\HMACService;

class AccountTrackerService extends HMACService implements ResourceService
{
    /**
     * Loads an employee's account information
     *
     * @param Employee $employee  The employee being looked up
     */
    public function load(Employee $employee): ?array
    {
        $url = BASE_URL.'/users?format=json&username='.$employee->username;

        $client   = new Client();
        $request  = new Psr7\Request('GET', $url, parent::HMACHeaders());
        $signed   = parent::signRequest($request);
        $response = $client->send($signed);

        $list = json_decode((string)$response->getBody(), true);
        if ($list[0]) {
            return $list[0];
        }
        else {
            throw new \Exception('no response from '. __CLASS__);
        }
    }

    /**
     * Creates a new user account
     */
    public function create(array $account)
    {
        $account['format'] = 'json';

        $client   = new Client();
        $request  = parent::signedPostRequest(BASE_URL.'/users/update', $account);
        $response = $client->send($request, ['allow_redirects'=>false]);

        $user     = json_decode((string)$response->getBody(), true);
        if (empty($user['id'])) {
            throw new \Exception('failed to create user '. __CLASS__);
        }
    }

    /**
     * Modifies an existing user account
     */
    public function modify(Employee $employee, array $account)
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
