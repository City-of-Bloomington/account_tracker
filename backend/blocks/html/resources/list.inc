<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 * @param array $this->resources
 */
declare (strict_types=1);
?>
<section>
    <header><h1><?= $this->_(['resource', 'resources', count($this->resources)]); ?></h1></header>
    <table>
    <?php
        foreach ($this->resources as $r) {
            $name = parent::escape($r['name']);
            echo "<tr><td>$name</td></tr>";
        }
    ?>
    </table>
</section>
