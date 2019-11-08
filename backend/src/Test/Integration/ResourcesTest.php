<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);

use PHPUnit\Framework\TestCase;

class ResourcesTest extends TestCase
{
    protected static $di;

    public static function setUpBeforeClass(): void
    {
        global $DI;
        self::$di = $DI;
    }

    public function testProfileResources()
    {
        $profile_search  = self::$di->get('Domain\Profiles\UseCases\Search\Command');
        $response        = $profile_search();
        $profiles        = $response->profiles;

        $resource_search = self::$di->get('Domain\Resources\UseCases\Search\Command');
        $response        = $resource_search();
        $resources       = $response->resources;

        $codes = [];
        foreach ($resources as $r) {
            $codes[] = $r->code;
        }

        foreach ($profiles as $p) {
            foreach ($p->resources as $code=>$r) {
                $this->assertTrue(in_array($code, $codes), "{$p->code} declares invalid resource: $code");
            }
        }
    }
}
