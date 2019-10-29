<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 * @param int      $this->employee_number
 * @param int      $this->profile_id
 * @param array    $this->questions
 * @param Employee $this->employee
 * @param array    $this->profiles  An array of Profile objects
 * @param Profile  $this->profile
 */
declare (strict_types=1);
?>
<section>
    <header>
        <h1><?= parent::escape($this->employee->fullname()); ?></h1>
        <h2><?= $this->_('activate'); ?></h2>
    </header>
    <form method="post">
        <fieldset><legend><?= $this->profile->name; ?></legend>
            <input name="employee_number" type="hidden" value="<?= $this->employee_number; ?>" />
            <input name="profile_id"      type="hidden" value="<?= $this->profile_id;      ?>" />
            <?php
                $questions = $this->profile->questions ?? null;
                if ($questions) {
                    $h = $this->template->getHelper('field');

                    foreach ($questions as $f => $q) {
                        echo $h->field([
                            'name'     => "questions[$f]",
                            'id'       => $f,
                            'label'    => $q['label'],
                            'type'     => $q['type' ],
                            'value'    => !empty($this->questions[$f]) ? parent::escape($this->questions[$f]) : null,
                            'required' => !empty($q['required']) && $q['required']
                        ]);
                    }
                }
                $h = $this->template->getHelper('saveAndCancelButtons');
                echo $h->saveAndCancelButtons(parent::generateUri('employees.view', ['id'=>$this->employee_number]));
            ?>
        </fieldset>
    </form>
</section>
