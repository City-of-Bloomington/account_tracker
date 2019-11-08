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
    public function load(Employee $employee): array
    {
        if ($employee->username) {
            $url = BASE_URL.'/users?format=json&username='.$employee->username;

            $client   = new Client();
            $request  = new Psr7\Request('GET', $url, parent::HMACHeaders());
            $signed   = parent::signRequest($request);
            $response = $client->send($signed);

            $list = json_decode((string)$response->getBody(), true);
            if (!$list) {
                throw new \Exception('Server did not return json');
            }

            if (!empty($list['users'][0])) {
                return $list['users'][0];
            }
        }
        return [];
    }

    /**
     * Creates a new user account
     *
     * @return array  An array of error messages
     */
    public function create(array $account): array
    {
        $account['format'] = 'json';

        $client   = new Client();
        $request  = parent::signedPostRequest(BASE_URL.'/users/update', $account);
        $response = $client->send($request, ['allow_redirects'=>false]);
        $body     = $response->getBody()->__toString();

        $json     = json_decode($body, true);
        return $json ?? ['Server did not return json'];
    }

    /**
     * Modifies an existing user account
     */
    public function modify(array $current, array $modified)
    {
        $modified['format'] = 'json';
        $modified['id'    ] = $current['id'];

        $client   = new Client();
        $request  = parent::signedPostRequest(BASE_URL.'/users/update', $modified);
        $response = $client->send($request, ['allow_redirects'=>false]);
        $body     = $response->getBody()->__toString();

        $json     = json_decode($body, true);
        return $json ?? ['Server did not return json'];
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
