<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 * @param array $this->resources
 */
declare (strict_types=1);
?>
<section>
    <header><h1><?= $this->_(['resource', 'resources', count($this->resources)]); ?></h1></header>
    <table>
        <theader>
            <tr><th><?= $this->_('code'); ?></th>
                <th><?= $this->_('name'); ?></th>
                <th><?= $this->_('type'); ?></th>
            </tr>
        </theader>
    <?php
        foreach ($this->resources as $r) {
            $code = parent::escape($r->code);
            $name = parent::escape($r->name);
            $type = parent::escape($r->type);

            $uri  = parent::generateUri('resources.view', ['code'=>$r->code]);
            echo "
            <tr><td><a href=\"$uri\">$code</a></td>
                <td><a href=\"$uri\">$name</a></td>
                <td><a href=\"$uri\">$type</a></td>
            </tr>
            ";
        }
    ?>
    </table>
</section>
