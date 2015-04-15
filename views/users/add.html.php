<div class="center">
	<h2><?=$t('Inscrivez-vous')?></h2>
</div>
<div id="content" style="position:relative">
<?=$this->form->create($user); ?>
	<?=$this->form->field(array('username' => $t('Email'))); ?>
	<?=$this->form->field(array('password' => $t('Mot de passe')), array('type' => 'password')); ?>
	<?=$this->form->field(array('firstname' => $t('Prénom'))); ?>
	<?=$this->form->field(array('lastname' => $t('Nom'))); ?>
	<?=$this->form->field(array('gender' => $t('Nom'))); ?>
	<?=$this->html->link('En cliquant sur m\'inscrire, j\'accepte les conditions générales d\'utilisation', 'pages/cgu')?>
	<?php /*=$this->form->field(array('cgu' => $t('J\'accepte les conditions générales d\'utilisation')), array('type' => 'checkbox')); */ ?>
	<?=$this->form->submit($t('M\'inscrire')); ?>
<?=$this->form->end(); ?>
</div>