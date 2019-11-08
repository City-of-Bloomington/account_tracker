<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 * @param AccountRequest $this->account_request
 * @param array          $this->actions            // Array of valid action links
 * @param array          $this->current_resources
 */
declare (strict_types=1);
use Web\Block;
?>
<section>
    <header>
        <h1><?= $this->_('account_request').' #'.$this->account_request->id; ?></h1>
    </header>
    <?php
        if ($this->actions) {
            $actions = [];
            foreach ($this->actions as $i=>$a) {
                $actions[$i] = ['uri' => $a['href'], 'help'=>"account_requests.$i"];
            }
            $block = new Block('toolbox.inc', ['actions' => $actions]);
            echo $block->render('html', $this->template);
        }
    ?>
    <table>
    <?php
        $fields = ['id', 'requester_id', 'requester_username', 'employee_number', 'type', 'status'];
        foreach ($fields as $f) {
            $v = parent::escape($this->account_request->$f);
            echo "<tr><th>{$this->_($f)}</th><td>$v</td></tr>";
        }

        foreach (['created', 'modified', 'completed'] as $f) {
            $v = $this->account_request->$f ? $this->account_request->$f->format(DATETIME_FORMAT) : '';
            echo "<tr><th>{$this->_($f)}</th><td>$v</td></tr>";
        }
    ?>
    </table>

    <h2><?= $this->_('employee'); ?></h2>
    <table>
    <?php
        foreach ($this->account_request->employee as $k=>$v) {
            $v = parent::escape($v);
            echo "<tr><th>{$this->_($k)}</th><td>$v</td></tr>";
        }
    ?>
    </table>

    <h2><?= $this->_(['resource', 'resources', count($this->account_request->resources)]); ?></h2>
    <?php
        $userCanApply = parent::isAllowed('account_requests', 'apply');
        foreach ($this->account_request->resources as $code=>$values) {
            if ($userCanApply) {
                $apply = parent::generateUri('account_requests.apply', [
                            'request_id'    => $this->account_request->id,
                            'resource_code' => $code
                         ]);
            }

            echo "
            <details>
                <summary>$code</summary>
                <table>
                    <tr><th><a href=\"$apply\">{$this->_('apply')}</a></th>
                        <td>{$this->_('account_requests.apply', 'messages')}</td>
                    </tr>
                </table>
                <table>
                    <thead>
                        <tr><th></th>
                            <th>{$this->_('requested')}</th>
                            <th>{$this->_('current'  )}</th>
                        </tr>
                    </thead>
                    <tbody>
            ";
            foreach ($values as $k=>$v) {
                $requested = is_string($v) ? parent::escape($v) : json_encode($v, JSON_PRETTY_PRINT);
                $current   = !empty(             $this->current_resources[$code][$k])
                           ? (is_string(         $this->current_resources[$code][$k])
                                ? parent::escape($this->current_resources[$code][$k])
                                :                $this->current_resources[$code][$k])
                           : '';
                $class     = $requested == $current ? '' : ' class="modified"';
                echo "
                    <tr$class>
                        <th>{$this->_($k)}</th>
                        <td>$requested</td>
                        <td>$current</td>
                    </tr>
                ";
            }
            echo "
                    </tbody>
                </table>

            </details>
            ";
        }
    ?>
</section>
