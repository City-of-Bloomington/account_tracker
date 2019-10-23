<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 * @param Employee $this->employee
 * @param array    $this->resources
 */
declare (strict_types=1);
?>
<section>
    <header>
        <h1><?= parent::escape($this->employee->fullname()); ?></h1>
        <h2><?= parent::escape($this->employee->department); ?></h2>
    </header>
    <?php
        foreach ($this->resources as $code=>$fields) {
            echo "
            <table>
                <caption>$code</caption>
            ";
                foreach ($fields as $k=>$v) {
                    $k = parent::escape($k);
                    $v = parent::escape($v);
                    echo "<tr><th>$k</th><td>$v</td></tr>";
                }
            echo "
            </table>
            ";
        }
    ?>
</section>
