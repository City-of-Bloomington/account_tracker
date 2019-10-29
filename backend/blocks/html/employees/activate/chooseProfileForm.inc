<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 * @param int      $this->employee_number
 * @param int      $this->profile_id
 * @param Employee $this->employee
 * @param array    $this->profiles  An array of Profile objects
 */
declare (strict_types=1);

?>
<section>
    <header>
        <h1><?= parent::escape($this->employee->fullname()); ?></h1>
        <h2><?= $this->_('activate'); ?></h2>
    </header>
    <form method="get">
        <fieldset><legend><?= $this->_('profile'); ?></legend>
            <input name="employee_number" type="hidden" value="<?= $this->employee_number; ?>" />
            <?php
                $h = $this->template->getHelper('field');

                $options = [['value' => '']];
                foreach ($this->profiles as $p) {
                    $options[] = ['value' => $p->id, 'label'=>$p->name];
                }
                echo $h->field([
                    'name'     => 'profile_id',
                    'id'       => 'profile_id',
                    'label'    => $this->_('department'),
                    'value'    => $this->profile_id,
                    'type'     => 'select',
                    'options'  => $options,
                    'required' => true

                ]);
            ?>
            <button type="submit">Submit</button>
            <a href="<?= parent::generateUri('employees.view', ['id'=>$this->employee_number]); ?>"><?= $this->_('cancel'); ?></a>
        </fieldset>
    </form>
</section>
