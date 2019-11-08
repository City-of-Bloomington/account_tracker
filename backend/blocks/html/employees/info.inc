<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 * @param InfoResponse $this->infoResponse
 * @param array        $this->actions
 */
declare (strict_types=1);
use Web\Block;

$employee  = $this->infoResponse->employee;
$resources = $this->infoResponse->resources;
?>
<section>
    <header>
        <h1><?= parent::escape($employee->fullname()); ?></h1>
        <h2><?= parent::escape($employee->department); ?></h2>
    </header>
    <?php
        if ($this->actions) {
            foreach ($this->actions as $i=>$a) {
                $actions[$i] = ['uri' => $a['href'], 'help' => "employees.$i"];
            }
            $block = new Block('toolbox.inc', ['actions'=>$actions]);
            echo $block->render('html', $this->template);
        }

        echo "<table>";
        foreach ($employee as $k=>$v) {
            if (!is_array($v)) {
                $v = parent::escape($v);
                echo "
                <tr><th>{$this->_($k)}</th><td>$v</td></tr>
                ";
            }
        }
        echo "</table>";

        foreach ($resources as $r) {
            $code = $r['definition']->code;
            echo "
            <details>
                <summary>$code</summary>
                <table>
            ";
                foreach ($r['values'] as $k=>$v) {
                    $k = $this->_($k);
                    $v = is_array($v)
                            ? parent::escape(implode(',', $v))
                            : parent::escape($v);
                    echo "<tr><th>$k</th><td>$v</td></tr>";
                }
            echo "
                </table>
            </details>
            ";
        }
    ?>
</section>
