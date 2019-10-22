<?php
/**
 * @copyright 2015-2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
if (isset($_SESSION['USER'])) {

    $name = self::escape("{$_SESSION['USER']->firstname} {$_SESSION['USER']->lastname}");
    $links = [
        ['url'=>parent::generateUri('login.logout' ), 'label'=>$this->_('logout'    )],
        ['url'=>parent::generateUri('login.current'), 'label'=>$this->_('my_account')]
    ];

    $helper = $this->getHelper('dropdown');
    echo $helper->dropdown($links, $name, 'User_menu');
}
else {
    $return_url = self::escape($_SERVER['REQUEST_URI']);
    $url = parent::generateUrl('login.login');
    echo "<nav><a href=\"$url?return_url=$return_url\">{$this->_('login')}</a></nav>";
}