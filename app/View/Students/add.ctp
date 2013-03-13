<div class="row">
    <div class="twelvecol last">
    <h1>Lisää uusi opiskelija</h1>
    <?php

    echo $this->Form->create('Student');
    echo $this->Form->input('first_name', array('label' => __('Etunimi')));
    echo $this->Form->input('last_name', array('label' => __('Sukunimi')));
    echo $this->Form->input('student_number', array('label' => __('Opiskelijanumero')));
    echo $this->Form->input('email', array('label' => __('Sähköposti')));

    echo $this->Form->submit(__('Tallenna'));
//  echo $this->Html->link('Peruuta', array('action' => 'index'));
    echo $this->Form->end();
    ?>
    </div>
</div>