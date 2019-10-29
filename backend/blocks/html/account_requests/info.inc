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
