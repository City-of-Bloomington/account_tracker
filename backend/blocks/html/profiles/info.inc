<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 * @param int    $this->id
 * @param string $this->name
 * @param array  $this->questions
 * @param array  $this->resources
 */
declare (strict_types=1);
use Web\Block;
?>
<section>
    <header>
        <h1><?= $this->name; ?></h1>
        <?php
            if (parent::isAllowed('profiles', 'update')) {
                $block = new Block('toolbox.inc', ['actions' => [
                    'edit' => [
                        'uri' => parent::generateUri('profiles.update', ['id'=>$this->id]),
                        'class' => 'edit',
                        'help' => 'profiles.edit'
                    ]
                ]]);
                echo $block->render('html', $this->template);
            }
        ?>
    </header>

    <h2><?= $this->_('questions'); ?></h2>
    <code><?= json_encode($this->questions, JSON_PRETTY_PRINT); ?></code>

    <h2><?= $this->_(['resource', 'resources', 10]); ?></h2>
    <code><?= json_encode($this->resources, JSON_PRETTY_PRINT); ?></code>
</section>
