<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);

use Aura\Di\ContainerBuilder;

$builder = new ContainerBuilder();
$DI = $builder->newInstance();

$DI->set('db.default', \Web\Database::getConnection('default', $DATABASES['default']));
$DI->set('db.hr',      \Web\Database::getConnection('hr',      $DATABASES['hr'     ]));

//---------------------------------------------------------
// Declare database repositories
//---------------------------------------------------------
$repos = [
    'People', 'Users',
    'AccountRequests', 'Resources'
];
foreach ($repos as $t) {
    $DI->params[ "Web\\$t\\Pdo{$t}Repository"]["pdo"] = $DI->lazyGet('db.default');
    $DI->set("Domain\\$t\\DataStorage\\{$t}Repository",
    $DI->lazyNew("Web\\$t\\Pdo{$t}Repository"));
}

$DI->params[ 'Web\Employees\PdoEmployeesRepository']['pdo'] = $DI->lazyGet('db.hr');
$DI->set( 'Domain\Employees\DataStorage\EmployeesRepository',
$DI->lazyNew('Web\Employees\PdoEmployeesRepository'));

//---------------------------------------------------------
// Services
//---------------------------------------------------------
$DI->params[ 'Domain\Auth\AuthenticationService']['repository'] = $DI->lazyGet('Domain\Users\DataStorage\UsersRepository');
$DI->params[ 'Domain\Auth\AuthenticationService']['config'    ] = $DIRECTORY_CONFIG;
$DI->set(    'Domain\Auth\AuthenticationService',
$DI->lazyNew('Domain\Auth\AuthenticationService'));

//---------------------------------------------------------
// Use Cases
//---------------------------------------------------------
// Account Requests
foreach (['Info', 'Search'] as $a) {
    $DI->params[ "Domain\\AccountRequests\\UseCases\\$a\\Command"]["repository"] = $DI->lazyGet('Domain\AccountRequests\DataStorage\AccountRequestsRepository');
    $DI->set(    "Domain\\AccountRequests\\UseCases\\$a\\Command",
    $DI->lazyNew("Domain\\AccountRequests\\UseCases\\$a\\Command"));
}

// Employees
foreach (['Search'] as $a) {
    $DI->params[ "Domain\\Employees\\UseCases\\$a\\Command"]['repository'] = $DI->lazyGet('Domain\Employees\DataStorage\EmployeesRepository');
    $DI->set(    "Domain\\Employees\\UseCases\\$a\\Command",
    $DI->lazyNew("Domain\\Employees\\UseCases\\$a\\Command"));
}

// People
foreach(['Info', 'Load', 'Search', 'Update'] as $a) {
    $DI->params[ "Domain\\People\\UseCases\\$a\\Command"]['repository'] = $DI->lazyGet('Domain\People\DataStorage\PeopleRepository');
    $DI->set(    "Domain\\People\\UseCases\\$a\\Command",
    $DI->lazyNew("Domain\\People\\UseCases\\$a\\Command"));
}

// Resources
foreach (['Info', 'Search', 'Update'] as $a) {
    $DI->params[ "Domain\\Resources\\UseCases\\$a\\Command"]["repository"] = $DI->lazyGet('Domain\Resources\DataStorage\ResourcesRepository');
    $DI->set(    "Domain\\Resources\\UseCases\\$a\\Command",
    $DI->lazyNew("Domain\\Resources\\UseCases\\$a\\Command"));
}

// Users
foreach (['Delete', 'Info', 'Search', 'Update'] as $a) {
    $DI->params[ "Domain\\Users\\UseCases\\$a\\Command"]["repository"] = $DI->lazyGet('Domain\Users\DataStorage\UsersRepository');
    $DI->set(    "Domain\\Users\\UseCases\\$a\\Command",
    $DI->lazyNew("Domain\\Users\\UseCases\\$a\\Command"));
}
$DI->params['Domain\Users\UseCases\Update\Command']['auth'] = $DI->lazyGet('Domain\Auth\AuthenticationService');
