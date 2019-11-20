<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 * @param int    $this->id
 * @param string $this->name
 * @param string $this->questions  JSON string
 * @param string $this->resources  JSON string
 */
declare (strict_types=1);
?>
<section>
    <header><h1><?= $this->title; ?></h1></header>
    <form method="post">
        <fieldset>
            <input name="id" type="hidden" value="<?= $this->id; ?>" />
            <?php
                $h = $this->template->getHelper('field');

                echo $h->field([
                    'name'     => 'name',
                    'id'       => 'name',
                    'label'    => $this->_('name'),
                    'value'    => $this->name,
                    'required' => true
                ]);

                echo $h->field([
                    'name'     => 'questions',
                    'id'       => 'questions',
                    'label'    => $this->_('questions'),
                    'value'    => $this->questions,
                    'type'     => 'textarea',
                    'required' => true
                ]);

                echo $h->field([
                    'name'     => 'resources',
                    'id'       => 'resources',
                    'label'    => $this->_('resources'),
                    'value'    => $this->resources,
                    'type'     => 'textarea',
                    'required' => true
                ]);
                $h = $this->template->getHelper('saveAndCancelButtons');
                echo $h->saveAndCancelButtons($this->return_url);
            ?>
        </fieldset>
    </form>
</section>
