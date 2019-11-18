<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 * @param array $this->employees
 */
declare (strict_types=1);
$fields = ['number', 'firstname', 'lastname', 'username', 'department'];
?>
<table>
    <thead>
        <tr>
        <?php
            foreach ($fields as $f) {
                echo "<th>{$this->_($f)}</th>";
            }
        ?>
        </tr>
    </thead>
    <tbody>
    <?php
        foreach ($this->employees as $e) {
            $uri = parent::generateUri('employees.view', ['id'=>$e->number]);

            echo '<tr>';
            foreach ($fields as $f) {
                $$f = parent::escape($e->$f);
                echo "<td><a href=\"$uri\">{$$f}</a></td>";
            }
            echo '</tr>';
        }
    ?>
    </tbody>
</table>
