<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 * @param array $this->actions
 *
 * Actions is an array of link definitions.  The link label is the index of the
 * actions array.
 * [
 *     label => [uri => , class => , help => ],
 * ]
 *  label   The i8n msgid in "labels" to use as the link text
 *  uri     The uri for the link
 *  class   CSS class to add
 *  help    The i8n msgid in "messages" that is the description of the action
 */
declare (strict_types=1);
?>
<details>
    <summary><?= $this->_('actions'); ?></summary>
    <table>
    <?php
        $h = $this->template->getHelper('buttonLink');

        foreach ($this->actions as $label => $action) {
            $link = $h->buttonLink($action['uri'], $this->_($label), $action['class']);
            $help = !empty($action['help']) ? $this->_($action['help'], 'messages') : '';
            echo "
            <tr><th>$link</th>
                <td>$help</td>
            </tr>
            ";
        }
    ?>
    </table>
</details>