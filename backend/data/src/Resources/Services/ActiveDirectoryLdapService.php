<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);

namespace Site\Resources\Services;

use Domain\Resources\ResourceService;
use Domain\Employees\Entities\Employee;
use Site\LdapService;

class ActiveDirectoryLdapService extends LdapService implements ResourceService
{
    /**
     * Loads an employee's directory information
     *
     * @param Employee $employee  The employee being looked up
     */
    public function load(Employee $employee): ?array
    {

        $entries = parent::search(['sAMAccountName='.$employee->username]);
        return self::hydrate($entries[0]);
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

    /**
     * Returns the cleaned up entry from Ldap
     *
     * Ldap entries always come back as multi-valued fields.  This function
     * converts all the fields that should be single values into strings,
     * instead of a single element array.
     */
    private static function hydrate(array $entry): array
    {
        $out = [];
        foreach (parent::$fields as $f) {
            if (isset($entry[$f])) {
                if ($entry[$f]['count'] == 1) {
                    $out[$f] = $entry[$f][0];
                }
                else {
                    unset($entry[$f]['count']);
                    $out[$f] = $entry[$f];
                }
            }
        }
        return $out;
    }
}
