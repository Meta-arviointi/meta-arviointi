<div class="row">
    <div class="twelveol">
<?php 
$links = array(
        array('text' => __('Kurssit'), 'url' => array('controller' => 'courses')),
        array('text' => __('Assistentit'), 'url' => array('controller' => 'users')),
        array('text' => __('Opiskelijat'), 'url' => array('controller' => 'students')),
        array('text' => __('Viestipohjat'), 'url' => array('controller' => 'action_email_templates'), 'options' => array('class' => 'selected'))
);
echo $this->element('tab-menu', array('links' => $links)); 
?>
    </div>
</div>
<div class="row">
    <div class="ninecol">
    	<?php
    		foreach($action_types as $type) {
    			echo '<h2>' . $type['ActionType']['name'] . '</h2>';
    			echo $this->Form->create('ActionType', array('id' => false, 'class' => 'action-email-template-form'));
    			echo $this->Form->input('ActionType.id', array('value' => $type['ActionType']['id']));
    			echo $this->Form->input('ActionEmailTemplate.id', array('value' => $type['ActionEmailTemplate']['id']));
    			echo $this->Form->input('ActionEmailTemplate.subject', array('label' => __('Otsikko'), 'value' => $type['ActionEmailTemplate']['subject']));
    			echo $this->Form->input('ActionEmailTemplate.content', array('label' => __('Viesti'), 'value' => $type['ActionEmailTemplate']['content']));
    			echo $this->Form->end(__('Tallenna'));
    			echo '<hr class="row">';
    		}
    	?>
    </div>
	<div class="threecol right last">
		<h2>Tagit</h2>
		<dl id="action-email-template-tags">
			<dt>{#opiskelija}</dt>
			<dd>Opiskelijan, jolle tämä viesti lähetetään, nimi muodossa "Etunimi Sukunimi"</dd>

			<dt>{#harjoitus}</dt>
			<dd>Toimenpidettä koskevan harjoituksen nimi (tai nimet, mikäli toimenpide koskee useampia harjoituksia) muodossa "#: Harjoituksen nimi", esim: "3: Esimerkkiharjoitus"</dd>

			<dt>{#selite}</dt>
			<dd>Toimenpiteeseen kirjoitettu seliteteksti</dd>
		</dl>
	</div>
</div>