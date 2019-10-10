<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);

use PHPUnit\Framework\TestCase;

class ContainerTest extends TestCase
{
    protected static $pdo;

    public static function setUpBeforeClass(): void
    {
        global $DI;
        self::$pdo = $DI->get('PDO');
    }

    public function testDatabaseConnection()
    {
        $sql    = 'select count(*) from people';
        $result = self::$pdo->query($sql);
        $count  = $result->fetchColumn();
        $this->assertGreaterThan(1, $count);
    }
}
