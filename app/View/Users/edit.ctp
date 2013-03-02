<div class="row">
    <div class="row">
        <div class="ninecol">
            <h1><?php echo $user['User']['name'] ?></h1>
            <?php
                echo $this->Form->create('User');
                echo $this->Form->input('id', array('hidden' => true));
                echo $this->Form->input('basic_user_account', array(
                    'label' => __('Peruspalvelutunnus'),
                    'readonly' => 'readonly'
                    )
                );
                echo $this->Form->input('first_name', array('label' => __('Etunimi')));
                echo $this->Form->input('last_name', array('label' => __('Sukunimi')));
                echo $this->Form->input('password', array('label' => __('Uusi salasana')));
                echo $this->Form->end(__('Tallenna'));
                if ( !empty($referer) ) {
                    echo $this->Html->link(__('Takaisin'), $referer);
                } else {
                    echo $this->Html->link(__('Takaisin'), array('action' => 'view', $user['User']['id']));
                }

            ?>
        </div>
    </div>

</div> 