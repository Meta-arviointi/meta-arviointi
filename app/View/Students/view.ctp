<div class="row">
    <div class="row">
        <div class="ninecol">
            <h1><?php echo $student['Student']['name'] ?></h1>
            <?php

                // Define fields to be displayed.
                // Notice: key must be key in $student['Student']-array
                $fields = array('first_name' => __('Etunimi'), 'last_name' => __('Sukunimi'), 
                    'email' => __('Sähköposti')
                );
                // Print fields
                echo '<div class="row">' . "\n";
                foreach( $fields as $field => $label ) {
                    echo '<div class="row">';
                    echo '<strong>' . $label . ': </strong>';
                    echo '<span>' . $student['Student'][$field] . '</span>';
                    echo '</div>';
                }
                echo '</div>';
                /*
                echo $this->Html->link(__('Muokkaa'), array(
                        'admin' => false,
                        'controller' => 'students',
                        'action' => 'edit',
                        $student['Student']['id']
                    )
                );*/
                if ( !empty($referer) ) {
                    echo $this->Html->link(__('Takaisin'), $referer);
                } else {
                    echo $this->Html->link(__('Takaisin'), array('controller' => 'students', 'action' => 'index'));
                }
            ?>
        </div>
    </div>
</div>