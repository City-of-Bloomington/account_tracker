<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 * @param array $this->account_requests
 */
declare (strict_types=1);

?>
<table>
    <thead>
        <tr><th><?= $this->_('employee' ); ?></th>
            <th><?= $this->_('requester'); ?></th>
            <th><?= $this->_('type'     ); ?></th>
            <th><?= $this->_('status'   ); ?></th>
            <th><?= $this->_('created'  ); ?></th>
        </tr>
    </thead>
    <tbody>
    <?php
        foreach ($this->account_requests as $r) {
            $employee  = parent::escape("{$r->employee['firstname']} {$r->employee['lastname']}");
            $requester = parent::escape($r->requester_username);
            $type      = $r->type;
            $status    = $r->status;
            $created   = $r->created->format(DATETIME_FORMAT);

            $uri = parent::generateUri('account_requests.view', ['id'=>$r->id]);
            echo "
            <tr><td><a href=\"$uri\">$employee</a></td>
                <td><a href=\"$uri\">$requester</a></td>
                <td><a href=\"$uri\">$type</a></td>
                <td><a href=\"$uri\">$status</a></td>
                <td><a href=\"$uri\">$created</a></td>
            </tr>
            ";
        }
    ?>
    </tbody>
</table>
