<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);

namespace Site\Resources\Services;

use Domain\Resources\ResourceService;
use Domain\Employees\Entities\Employee;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Web\Authentication\HMACService;

class ActiveDirectoryService extends HMACService implements ResourceService
{
    /**
     * Loads an employee's account information
     *
     * @param Employee $employee  The employee being looked up
     */
    public function load(Employee $employee): ?array
    {
        $url = 'https://aoi.bloomington.in.gov/directory/people/view?format=json&username='.$employee->username;

        $client   = new Client();
        $request  = new Request('GET', $url, parent::HMACHeaders());
        $signed   = parent::signRequest($request);
        $response = $client->send($signed);

        return json_decode((string)$response->getBody(), true);
    }

    /**
     * Creates a new user account
     */
    public function create(array $account)
    {
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
}
