<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
?>
<nav id="nav1">
    <div class="container">
    <?php
        $routes = [
            'account_requests' => 'account_request',
            'employees'        => 'employee'
        ];
        foreach ($routes as $plural=>$singular) {
            if (parent::isAllowed($plural, 'index')) {
                echo sprintf("<a href=\"%s\">%s</a>",
                             parent::generateUri("$plural.index"),
                             $this->_([$singular, $plural, 10]));
            }
        }
    ?>
    </div>
</nav>
