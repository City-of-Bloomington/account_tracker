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
use Site\LdapService;

class ActiveDirectoryLdapService extends LdapService implements ResourceService
{
    /**
     * Loads an employee's directory information
     *
     * @param Employee $employee  The employee being looked up
     */
    public function load(Employee $employee): array
    {
        $entries = parent::search(['employeeNumber='.$employee->number]);
        if ($entries) {
            return self::hydrate($entries[0]);
        }
        return [];
    }

    /**
     * Creates a new user account
     *
     * @return array   Contains new LDAP entry and/or errors
     */
    public function create(array $account): array
    {
        $connection = parent::getConnection();

        $dn = $account['distinguishedname'];
        unset($account['distinguishedname']);

        if (!empty($account['memberof'])) {
            $groups = $account['memberof'];
            unset($account['memberof']);
        }

        // Filter out empty fields
        $entry = [];
        foreach ($account as $k=>$v) {
            if ($v) { $entry[$k] = $v; }
        }

        $success = @ldap_add($connection, $dn, $entry);
        if (!$success) {
            return ['errors'=>[__CLASS__.': '.ldap_error($connection)]];
        }

        $errors = [];
        if (!empty($groups)) {
            foreach ($groups as $g) {
                $success = ldap_mod_add($connection, $g, ['member' => $dn]);
                if (!$success) {
                     $errors[] = [__CLASS__.': '.ldap_error($connection)];
                }
            }
        }

        $entry = $this->load(new Employee(['username'=>$account['samaccountname']]));
        return [ 'entry' => $entry, 'errors' => $errors ];
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
     * Create values for an Active Directory account
     *
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
        $fullname = "{$employee->firstname} {$employee->lastname}";
        $username = strtolower("{$employee->firstname}.{$employee->lastname}");
        $email    = "$username@bloomington.in.gov";

        $request  = [
            'objectClass'       => ['user'],
            "distinguishedname" => "CN=$fullname,".$profile->resources['active_directory']['ou'],
            "samaccountname"    => $username,
            "cn"                => $fullname,
            "mail"              => $email,
            "userprincipalname" => $email,
            "employeenumber"    => $employee->number,
            "employeeid"        => '',
            "uid"               => '',
            "title"             => '',
            "givenname"         => $employee->firstname,
            "sn"                => $employee->lastname,
            "name"              => $fullname,
            "displayname"       => $fullname,
            "telephonenumber"            => $questions['office_phone'],
            "pager"                      => $questions['public_phone'],
            "l"                          => 'Bloomington',
            "st"                         => 'Indiana',
            "street"                     => $profile->resources['active_directory']['street'],
            "postalcode"                 => $profile->resources['active_directory']['postalcode'],
            "physicaldeliveryofficename" => $profile->resources['active_directory']['physicaldeliveryofficename'],
            "businesscategory"           => $profile->resources['active_directory']['businesscategory'],
            "department"                 => $profile->resources['active_directory']['department'],
            "facsimiletelephonenumber"   => $profile->resources['active_directory']['facsimiletelephonenumber'],
            "memberof"                   => $profile->resources['active_directory']['memberof'],
        ];
        return $request;
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
