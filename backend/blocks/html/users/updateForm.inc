<?php
/**
 * @copyright 2006-2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 *
 * Form field values
 * @param string $this->id
 * @param string $this->firstname
 * @param string $this->lastname
 * @param string $this->email
 * @param string $this->username
 * @param string $this->password
 * @param string $this->role
 * @param string $this->authentication_method
 *
 * Metadata
 * @param string $this->title
 * @param array  $this->roles
 * @param array  $this->authentication_methods
 */
?>
<section>
    <header>
        <h1><?= $this->title; ?></h1>
    </header>

	<form method="post" action="<?= self::generateUri('users.update'); ?>">
		<fieldset><legend><?= $this->_('user_info'); ?></legend>
			<input name="id" type="hidden" value="<?= $this->id; ?>" />
			<?php
                $field = $this->template->getHelper('field');

                $options = [];
                foreach ($this->authentication_methods as $m) { $options[] = ['value'=>$m]; }
                echo $field->field([
                    'name'    => 'authentication_method',
                    'id'      => 'authentication_method',
                    'label'   => $this->_('authentication_method'),
                    'value'   => $this->authentication_method,
                    'type'    => 'select',
                    'options' => $options
                ]);

                echo $field->field(['name'=>'username', 'id'=>'username', 'label'=>$this->_('username'), 'value'=>$this->username, 'required'=>true]);
                echo $field->field(['name'=>'password', 'id'=>'password', 'label'=>$this->_('password'), 'type'=>'password']);

				$options = [];
                foreach ($this->roles as $r) { $options[] = ['value'=>$r]; }
				echo $field->field([
                    'name'    => 'role',
                    'id'      => 'role',
                    'label'   => $this->_('role'),
                    'value'   => $this->role,
                    'type'    => 'select',
                    'options' => $options
				]);
			?>
		</fieldset>
		<fieldset><legend><?= $this->_('person_info'); ?></legend>
            <?php
                echo $field->field(['name'=>'firstname', 'id'=>'firstname', 'label'=>$this->_('firstname'), 'value'=>$this->firstname]);
                echo $field->field(['name'=>'lastname',  'id'=>'lastname',  'label'=>$this->_('lastname' ), 'value'=>$this->lastname ]);
				echo $field->field(['name'=>'email',     'id'=>'email',     'label'=>$this->_('email'    ), 'value'=>$this->email, 'type'=>'email']);

				$helper = $this->template->getHelper('saveAndCancelButtons');
				echo $helper->saveAndCancelButtons(self::generateUri('users.index'));
            ?>
		</fieldset>
	</form>
</section>
