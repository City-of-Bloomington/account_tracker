<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 * @param string $this->code
 * @param string $this->name
 * @param string $this->type
 * @param array  $this->definition
 */
declare (strict_types=1);
?>
<section>
    <header><h1><?= $this->name; ?></h1></header>
    <table>
        <tr><th><?= $this->_('code'); ?></th><td><?= $this->code; ?></td></tr>
        <tr><th><?= $this->_('type'); ?></th><td><?= $this->type; ?></td></tr>
    </table>
    <code><?= json_encode($this->definition, JSON_PRETTY_PRINT); ?>
    </code>

</section>
