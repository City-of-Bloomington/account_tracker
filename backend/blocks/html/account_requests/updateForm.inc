<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 * @param string $this->title
 * @param int    $this->id
 * @param int    $this->requester_id    // The person making the request
 * @param int    $this->employee_number // The employee to create accounts for
 * @param string $this->type
 * @param string $this->status
 * @param array  $this->employee  = []  // JSON array
 * @param array  $this->resources = []  // JSON array
 */
declare (strict_types=1);
?>
<section>
    <header><h1><?= $this->title; ?></h1></header>
    <form method="post">
        <fieldset>
            <input name="id"              type="hidden" value="<?= $this->id ; ?>" />
            <input name="requester_id"    type="hidden" value="<?= $this->requester_id; ?>" />
            <input name="employee_number" type="hidden" value="<?= $this->employee_number; ?>" />
            <input name="type"            type="hidden" value="<?= $this->type; ?>" />
            <input name="status"          type="hidden" value="<?= $this->status; ?>" />
            <?php
                $h = $this->template->getHelper('field');
                echo $h->field([
                    'name'     => 'employee',
                    'id'       => 'employee',
                    'label'    => $this->_('employee'),
                    'type'     => 'textarea',
                    'value'    => json_encode($this->employee, JSON_PRETTY_PRINT),
                    'required' => true
                ]);

                echo $h->field([
                    'name'     => 'resources',
                    'id'       => 'resources',
                    'label'    => $this->_('resources'),
                    'type'     => 'textarea',
                    'value'    => json_encode($this->resources, JSON_PRETTY_PRINT),
                    'required' => true
                ]);

                $h = $this->template->getHelper('saveAndCancelButtons');
                echo $h->saveAndCancelButtons(parent::generateUri('account_requests.view', ['id'=>$this->id]));
            ?>
        </fieldset>
    </form>
</section>
