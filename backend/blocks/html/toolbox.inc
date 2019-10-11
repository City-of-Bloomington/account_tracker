<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
?>
<details>
    <summary><?= $this->_('actions'); ?></summary>
    <table>
    <?php
        $h = $this->template->getHelper('buttonLink');

        foreach ($this->actions as $name => $action) {
            $help = !empty($action['help']) ? $this->_($action['help']) : '';
            echo "
            <tr><th>{$h->buttonLink($action['uri'], $this->_($name), $action['class'])}</th>
                <td>$help</td>
            </tr>
            ";
        }
    ?>
    </table>
</details>
