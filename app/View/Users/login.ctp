<div class="users form">
<?php echo $this->Session->flash('auth'); ?>
<?php echo $this->Form->create('User'); ?>
    <fieldset>
        <legend><?php echo __('Meta-arviointityökalu'); ?></legend>
    <?php
        echo $this->Form->input('basic_user_account', array('label' => 'Peruspalvelutunnus:'));
        echo $this->Form->input('password', array('label' => 'Salasana:'));
    ?>
    </fieldset>
<?php echo $this->Form->end(__('Login')); ?>
</div>
