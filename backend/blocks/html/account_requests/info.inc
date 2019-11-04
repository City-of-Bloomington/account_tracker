<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 * @param int      $this->id
 * @param int      $this->requester_id       // The person making the request
 * @param int      $this->employee_number    // The employee to create accounts for
 * @param string   $this->requester_username
 * @param string   $this->type
 * @param string   $this->status
 * @param DateTime $this->created
 * @param DateTime $this->modified
 * @param DateTime $this->completed
 * @param array    $this->employee           // JSON array
 * @param array    $this->resources          // JSON array
 */
declare (strict_types=1);
?>
<section>
    <header>
        <h1><?= $this->type; ?></h1>
        <?php
            $actions = [];
            if (parent::isAllowed('account_requests', 'update')) {
                $actions['account_request_edit'] = [
                    'uri'   => parent::generateUri('account_requests.update', ['id'=>$this->id]),
                    'class' => 'edit'
                ];
            }
            if (parent::isAllowed('account_requests', 'delete')) {
                $actions['account_requests_delete'] = [
                    'uri'   => parent::generateUri('account_requests.delete', ['id'=>$this->id]),
                    'class' => 'delete'
                ];
            }
            if (parent::isAllowed('account_requests', 'apply')) {
                $actions['account_requests_apply'] = [
                    'uri'   => parent::generateUri('account_requests.apply', ['id'=>$this->id]),
                    'class' => 'apply'
                ];
            }

            if ($actions) {
                $block = new \Web\Block('toolbox.inc', ['actions' => $actions]);
                echo $block->render('html', $this->template);
            }
        ?>
    </header>
    <table>
    <?php
        $fields = ['id', 'requester_id', 'requester_username', 'employee_number', 'type', 'status'];
        foreach ($fields as $f) {
            echo "<tr><th>{$this->_($f)}</th><td>{$this->$f}</td></tr>";
        }

        foreach (['created', 'modified', 'completed'] as $f) {
            $v = $this->$f ? $this->$f->format(DATETIME_FORMAT) : '';
            echo "<tr><th>{$this->_($f)}</th><td>$v</td></tr>";
        }
    ?>
    </table>

    <h2><?= $this->_('employee'); ?></h2>
    <code><?= json_encode($this->employee, JSON_PRETTY_PRINT); ?></code>

    <h2><?= $this->_('resources'); ?></h2>
    <code><?= json_encode($this->resources, JSON_PRETTY_PRINT); ?></code>
</scetion>
