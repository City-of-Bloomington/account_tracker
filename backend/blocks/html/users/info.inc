<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 * @param User $this->user
 */
declare (strict_types=1);

?>
<section>
    <header><h1><?= parent::escape($this->user->getFullname()); ?></h1></header>
    <table>
    <?php
        $fields = ['username', 'email'];
        foreach ($fields as $f) {
            $v = parent::escape($this->user->$f);
            echo "<tr><th>{$this->_($f)}</th><td>$v</td></tr>";
        }
    ?>
    </table>
</section>
