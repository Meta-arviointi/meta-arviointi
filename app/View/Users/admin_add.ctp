<div class="users form">
<?php 
    echo $this->Form->create('User'); ?>
    <fieldset>
        <legend><?php echo __('Lisää uusi assistentti'); ?></legend>
    <?php
	echo $this->Form->input('first_name', array('label' => 'Etunimi'));
	echo $this->Form->input('last_name', array('label' => 'Sukunimi'));
	echo $this->Form->input('basic_user_account', array('label' => 'Käyttäjätunnus (ppt)'));
        echo $this->Form->input('email', array('label' => 'Sähköposti'));
        echo $this->Form->input('password', array('label' => 'Salasana'));
// tähän väliin vielä suora kurssivalinta :-p
	echo $this->Form->hidden('User.is_admin', array('value' => 'false'));
    ?>
    </fieldset>
<?php echo $this->Form->end(__('OK')); ?>
</div>
