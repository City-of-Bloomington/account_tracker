<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);

$rf = new \Aura\Router\RouterFactory(BASE_URI);
$ROUTES = $rf->newInstance();
$ROUTES->setTokens(['id' => '\d+']);

$ROUTES->add('home.index',   '/'        )->setValues(['controller' => 'Web\HomeController']);
$ROUTES->add('login.login',  '/login'   )->setValues(['controller' => 'Web\Authentication\LoginController']);
$ROUTES->add('login.logout', '/logout'  )->setValues(['controller' => 'Web\Authentication\LogoutController']);
$ROUTES->add('login.current', '/profile')->setValues(['controller' => 'Web\Users\Controllers\MyAccountController']);

$ROUTES->attach('account_requests', '/account_requests', function ($r) {
    $r->add('view',   '/{id}')        ->setValues(['controller' => 'Web\AccountRequests\Controllers\InfoController']);
    $r->add('index',  '')             ->setValues(['controller' => 'Web\AccountRequests\Controllers\ListController']);
});

$ROUTES->attach('employees', '/employees', function ($r) {
    $r->add('view',   '/{id}')->setValues(['controller' => 'Web\Employees\Controllers\ViewController']);
    $r->add('index', '')      ->setValues(['controller' => 'Web\Employees\Controllers\ListController']);
});

$ROUTES->attach('people', '/people', function ($r) {
    $r->add('update', '/update{/id}') ->setValues(['controller' => 'Web\People\Controllers\UpdateController']);
    $r->add('view',   '/{id}')        ->setValues(['controller' => 'Web\People\Controllers\ViewController'  ]);
    $r->add('index',  '')             ->setValues(['controller' => 'Web\People\Controllers\ListController'  ]);
});

$ROUTES->attach('users', '/users', function ($r) {
    $r->add('update', '/update{/id}') ->setValues(['controller' => 'Web\Users\Controllers\UpdateController']);
    $r->add('delete', '/delete/{id}') ->setValues(['controller' => 'Web\Users\Controllers\DeleteController']);
    $r->add('view',   '/{id}'       ) ->setValues(['controller' => 'Web\Users\Controllers\InfoController'  ]);
    $r->add('index',  '')             ->setValues(['controller' => 'Web\Users\Controllers\ListController'  ]);
});

$ROUTES->attach('resources', '/resources', function ($r) {
    $r->add('update', '/update{/id}')->setValues(['controller' => 'Web\Resources\Controllers\UpdateController']);
    $r->add('view',   '/{id}'       )->setValues(['controller' => 'Web\Resources\Controllers\InfoController'  ]);
    $r->add('index',  '')            ->setValues(['controller' => 'Web\Resources\Controllers\ListController'  ]);
});
