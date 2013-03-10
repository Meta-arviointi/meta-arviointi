<div class="users form">
<?php 

    echo '<h1>'.__('Lisää uusi assistentti').'</h1>';
    echo $this->Form->create('User'); ?>
    <fieldset>
    <?php
    echo $this->Form->input('first_name', array('label' => 'Etunimi'));
    echo $this->Form->input('last_name', array('label' => 'Sukunimi'));
    echo $this->Form->input('basic_user_account', array('label' => 'Käyttäjätunnus'));
    echo $this->Form->input('email', array('label' => 'Sähköposti'));
    echo $this->Form->input('password', array('label' => 'Salasana'));
    echo $this->Form->input('password2', array(
            'label' => 'Salasana uudelleen',
            'type' => 'password'
        )
    );
// tähän väliin vielä suora kurssivalinta :-p
    if ( $this->Session->read('Auth.User.is_admin') ) {
        echo $this->Form->input('is_admin', array('label' => 'Admin'));
    } else {
        echo $this->Form->hidden('User.is_admin', array('value' => 'false'));    
    }
    ?>
    </fieldset>
<?php echo $this->Form->end(__('Tallenna')); ?>
</div>
