<div class="users form">
<?php 
    echo $this->Form->create('User'); ?>
    <fieldset>
        <legend><?php echo __('Lisää uusi assistentti'); ?></legend>
    <?php
	echo $this->Form->input('first_name');
	echo $this->Form->input('last_name');
	echo $this->Form->input('basic_user_account');
        echo $this->Form->input('email');
        echo $this->Form->input('password');
	echo $this->Form->hidden('User.is_admin', array('value' => 'false'));
    ?>
    </fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
