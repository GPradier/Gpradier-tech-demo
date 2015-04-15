<h2><?=$t('Modifier mes Informations')?></h2>
<?=$this->form->create($user); ?>
	<?=$this->form->field(array('username' => $t('Email'))); ?>
	<?=$this->form->field(array('password' => $t('Mot de passe')), array('type' => 'password', 'disabled' => 'disabled')); ?>
	<?=$this->form->field(array('firstname' => $t('Prénom'))); ?>
	<?=$this->form->field(array('lastname' => $t('Nom'))); ?>
	
	<?=$this->form->field(array('location' => $t('Location'))); ?>
	<?=$this->form->field(array('avatar' => $t('Avatar'))); ?>
	<?=$this->form->submit($t('Mettre à jour')); ?>
<?=$this->form->end(); ?>