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
 * @param string $this->fields  JSON string
 * @param string $this->return_url
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
                    'name'     => 'code',
                    'id'       => 'code',
                    'label'    => $this->_('code'),
                    'value'    => $this->code,
                    'required' => true
                ]);

                echo $h->field([
                    'name'     => 'name',
                    'id'       => 'name',
                    'label'    => $this->_('name'),
                    'value'    => $this->name,
                    'required' => true
                ]);

                echo $h->field([
                    'name'     => 'class',
                    'id'       => 'class',
                    'label'    => $this->_('class'),
                    'value'    => $this->class,
                    'required' => true
                ]);

                echo $h->field([
                    'name'     => 'type',
                    'id'       => 'type',
                    'label'    => $this->_('type'),
                    'value'    => $this->type,
                    'required' => true
                ]);

                echo $h->field([
                    'name'     => 'api_key',
                    'id'       => 'api_key',
                    'label'    => $this->_('api_key'),
                    'value'    => $this->api_key
                ]);

                echo $h->field([
                    'name'     => 'api_secret',
                    'id'       => 'api_secret',
                    'label'    => $this->_('api_secret'),
                    'value'    => $this->api_secret
                ]);

                echo $h->field([
                    'name'     => 'fields',
                    'id'       => 'fields',
                    'label'    => $this->_('fields'),
                    'value'    => $this->fields,
                    'type'     => 'textarea',
                    'required' => true
                ]);

                $h = $this->template->getHelper('saveAndCancelButtons');
                echo $h->saveAndCancelButtons($this->return_url);
            ?>
        </fieldset>
    </form>
</section>
