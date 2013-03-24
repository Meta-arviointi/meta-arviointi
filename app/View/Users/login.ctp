<div class="row">
    <div class="twelvecol">
    <?php echo $this->Session->flash('auth'); ?>
    <?php echo $this->Form->create('User'); ?>
        <fieldset>
            <legend><?php echo __('Meta-arviointityökalu'); ?></legend>
        <?php
            echo $this->Form->input('basic_user_account', array('label' => 'Tunnus:'));
            echo $this->Form->input('password', array('label' => 'Salasana:'));
        ?>
        </fieldset>
    <?php    echo $this->Html->link(__('Salasana unohtunut?'),
                array('action' => 'forgotten_password'),
                array('class' => 'modal-link')
            );
     echo $this->Form->end(__('Login')); ?>
    </div>
</div>