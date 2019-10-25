<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 * @param array $this->resources
 */
declare (strict_types=1);
use Web\Block;
?>
<section>
    <header>
        <h1><?= $this->_(['resource', 'resources', count($this->resources)]); ?></h1>
        <?php
            if (parent::isAllowed('resources', 'update')) {
                $block = new Block('toolbox.inc', ['actions' => [
                    'add' => [
                        'uri'   => parent::generateUri('resources.update'),
                        'class' => 'add',
                        'help'  => 'resources.add'
                    ]
                ]]);
                echo $block->render('html', $this->template);
            }
        ?>
    </header>
    <table>
        <theader>
            <tr><th><?= $this->_('id'   ); ?></th>
                <th><?= $this->_('code' ); ?></th>
                <th><?= $this->_('name' ); ?></th>
                <th><?= $this->_('type' ); ?></th>
                <th><?= $this->_('class'); ?></th>
            </tr>
        </theader>
    <?php
        foreach ($this->resources as $r) {
            $id    = parent::escape($r->id   );
            $code  = parent::escape($r->code );
            $name  = parent::escape($r->name );
            $type  = parent::escape($r->type );
            $class = parent::escape($r->class);

            $uri  = parent::generateUri('resources.view', ['id'=>$r->id]);
            echo "
            <tr><td><a href=\"$uri\">$id</a></td>
                <td><a href=\"$uri\">$code</a></td>
                <td><a href=\"$uri\">$name</a></td>
                <td><a href=\"$uri\">$type</a></td>
                <td><a href=\"$uri\">$class</a></td>
            </tr>
            ";
        }
    ?>
    </table>
</section>
