<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 * @param Employee $this->employee
 */
declare (strict_types=1);

?>
<section>
    <header>
        <h1><?= parent::escape($this->employee->fullname()); ?></h1>
        <h2><?= parent::escape($this->employee->department); ?></h2>
    </header>
</section>
