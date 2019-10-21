<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Web;

class Database
{
    public static function getConnection(string $name='default', array $config): \PDO
    {
        switch ($name) {
            case 'hr':
                return self::connectHR($config);
            break;

            default:
                return self::connectDefault($config);
        }
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $platform = ucfirst($pdo->getAttribute(PDO::ATTR_DRIVER_NAME));
        return $pdo;
    }

    private static function connectDefault(array $conf): \PDO
    {
        try {
            $pdo = new \PDO("$conf[driver]:dbname=$conf[dbname];host=$conf[host]", $conf['username'], $conf['password'], $conf['options']);
        }
        catch (\Exception $e) {
            die("Could not connect to default database server\n");
        }
        return $pdo;
    }

    private static function connectHR(array $conf): \PDO
    {
        try {
            $pdo = new \PDO($conf['dsn'], $conf['username'], $conf['password']);
        }
        catch (\Exception $e) {
            die("Could not connect to HR database server\n");
        }

        return $pdo;
    }
}
