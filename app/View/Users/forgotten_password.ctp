<div class="row">
	<div class="twelvecol">

<?php
echo '<h1>'.__('Unohtunut salasana').'</h1>';
echo '<p>'.__('Anna käyttäjätunnuksesi sähköpostiosoite. Uusi salasana lähetetään kyseiseen sähköpostiin.').'</p>';
echo $this->Form->create('User');
echo $this->Form->input('email', array('label' => __('Sähköposti')));
echo $this->Form->end(__('Lähetä'));
?>
</div>
</div>