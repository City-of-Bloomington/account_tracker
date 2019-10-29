<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 * @param int    $this->id
 * @param string $this->code
 * @param string $this->name
 * @param string $this->type
 * @param string $this->class
 * @param string $this->api_key
 * @param string $this->api_secret
 */
declare (strict_types=1);
use Web\Block;
?>
<section>
    <header>
        <h1><?= $this->name; ?></h1>
        <?php
            if (parent::isAllowed('resources', 'update')) {
                $block = new Block('toolbox.inc', ['actions' => [
                    'edit' => [
                        'uri' => parent::generateUri('resources.update', ['id'=>$this->id]),
                        'class' => 'edit',
                        'help' => 'resources.edit'
                    ]
                ]]);
                echo $block->render('html', $this->template);
            }
        ?>
    </header>
    <table>
        <tr><th><?= $this->_('id'        ); ?></th><td><?= $this->id;         ?></td></tr>
        <tr><th><?= $this->_('code'      ); ?></th><td><?= $this->code;       ?></td></tr>
        <tr><th><?= $this->_('type'      ); ?></th><td><?= $this->type;       ?></td></tr>
        <tr><th><?= $this->_('class'     ); ?></th><td><?= $this->class;      ?></td></tr>
        <tr><th><?= $this->_('api_key'   ); ?></th><td><?= $this->api_key;    ?></td></tr>
        <tr><th><?= $this->_('api_secret'); ?></th><td><?= $this->api_secret; ?></td></tr>
    </table>
</section>
