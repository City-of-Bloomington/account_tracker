<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 * @param array $this->profiles
 */
declare (strict_types=1);
use Web\Block;
?>
<section>
    <header>
        <h1><?= $this->_(['profile', 'profiles', count($this->profiles)]); ?></h1>
        <?php
            if (parent::isAllowed('profiles', 'update')) {
                $block = new Block('toolbox.inc', ['actions' => [
                    'add' => [
                        'uri'   => parent::generateUri('profiles.update'),
                        'class' => 'add',
                        'help'  => 'profiles.add'
                    ]
                ]]);
                echo $block->render('html', $this->template);
            }
        ?>
    </header>
    <table>
        <thead>
            <tr><th><?= $this->_('name'); ?></th>
                <th><?= $this->_('code'); ?></th>
            </tr>
        </thead>
        <tbody>
        <?php
            foreach ($this->profiles as $p) {
                $code = parent::escape($p->code);
                $name = parent::escape($p->name);

                $uri  = parent::generateUri('profiles.view', ['id'=>$p->id]);

                echo "
                <tr><td><a href=\"$uri\">$name</a></td>
                    <td><a href=\"$uri\">$code</a></td>
                </tr>";
            }
        ?>
        </tbody>
    </table>
</section>
