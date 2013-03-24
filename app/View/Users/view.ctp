<div class="row">
    <div class="row">
        <div class="ninecol">
            <h1><?php echo $user['User']['name'] ?></h1>
            <?php
                $user['User']['last_login'] = date('j.n.Y G:i', 
                    strtotime($user['User']['last_login']));

                // Define fields to be displayed.
                // Notice: key must be key in $user['User]-array
                $fields = array('first_name' => __('Etunimi'), 'last_name' => __('Sukunimi'), 
                    'basic_user_account' => __('Tunnus'), 
                    'email' => __('Sähköposti'), 'last_login' => __('Viimeksi kirjautunut')
                );
                // Print fields
                echo '<div class="row">' . "\n";
                foreach( $fields as $field => $label ) {
                    echo '<div class="row">';
                    echo '<strong>' . $label . ': </strong>';
                    echo '<span>' . $user['User'][$field] . '</span>';
                    echo '</div>';
                }
                echo '</div>';
                echo $this->Html->link(__('Muokkaa'), array(
                        'admin' => false,
                        'controller' => 'users',
                        'action' => 'edit',
                        $user['User']['id']
                    ), array(
                        'class' => 'button modal-link'
                    )
                );
                echo $this->Html->link(__('Takaisin'), $referer);
            ?>
        </div>
    </div>
    <div class="row">
        <div class="ninecol">
            <h2><?php echo __('Kursseilla') ?></h2>
            <?php
                foreach($user['Course'] as $course) {
                    echo '<h3>' . $this->Html->link($course['name'],
                        array(
                            'admin' => false,
                            'controller' => 'courses',
                            'action' => 'view',
                            $course['id']
                        )
                    ) . '</h3>' . "\n";
                }
            ?>

        </div>
    </div>
</div>