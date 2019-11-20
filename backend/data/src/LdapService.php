<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);

namespace Site;

// Uncomment this line to have more debug information go
// into the apache error log.
#ldap_set_option(null, LDAP_OPT_DEBUG_LEVEL, 7);

class LdapService
{
    const NETWORK_TIMEOUT = 3;

	protected static $connection;
	protected $config;
	public static $fields = [
        'cn',
        'businesscategory',
        'department',
        'displayname',
        'distinguishedname',
        'employeeid',
        'employeenumber',
        'facsimiletelephonenumber',
        'givenname',
        'l',
        'mail',
        'memberof',
        'name',
        'pager',
        'physicaldeliveryofficename',
        'postalcode',
        'samaccountname',
        'sn',
        'st',
        'streetaddress',
        'telephonenumber',
        'title',
        'uid',
        'userprincipalname'
	];

	public function __construct(array $config)
	{
        $this->config = $config;
	}

	/**
	 * Creates the connection to the LDAP server
	 */
	protected function getConnection()
	{
		if (!self::$connection) {
			if (self::$connection = ldap_connect($this->config['server'])) {
				ldap_set_option(self::$connection, LDAP_OPT_PROTOCOL_VERSION,3);
				ldap_set_option(self::$connection, LDAP_OPT_REFERRALS, 0);
                ldap_set_option(self::$connection, LDAP_OPT_TIMELIMIT,       self::NETWORK_TIMEOUT);
                ldap_set_option(self::$connection, LDAP_OPT_NETWORK_TIMEOUT, self::NETWORK_TIMEOUT);
				if (!empty($this->config['admin_binding'])) {
					if (!ldap_bind(
							self::$connection,
							$this->config['admin_binding'],
							$this->config['admin_pass']
						)) {
						throw new \Exception(ldap_error(self::$connection));
					}
				}
				else {
					if (!ldap_bind(self::$connection)) {
						throw new \Exception(ldap_error(self::$connection));
					}
				}
			}
			else {
				throw new \Exception(ldap_error(self::$connection));
			}
		}
		return self::$connection;
	}

	protected function search(array $filters): array
	{
        $connection = $this->getConnection();
        $filter     = (count($filters) > 1) ? '(&'.implode('', $filters).')' : $filters[0];

        $result = ldap_search($connection, $this->config['base_dn'], $filter, self::$fields);
		if (ldap_count_entries($connection, $result)) {
			return ldap_get_entries($connection, $result);
        }
        return [];
	}
}
