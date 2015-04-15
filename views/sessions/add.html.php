<div id="content">

<h2><?=$t('Identifiez-vous')?></h2>
<?=$this->form->create(null); ?>
	<?=$this->form->field(array('username' => $t('Email'))); ?>
	<?=$this->form->field(array('password' => $t('Mot de passe')), array('type' => 'password')); ?>
	
	<?=$this->html->link($t('Mot de passe oublié ?'), array('controller' => 'users', 'action' => 'forgot'))?><br/>
	<?=$this->html->link($t('Pas encore inscrit ?'), array('controller' => 'users', 'action' => 'add'))?>
	
	<?=$this->form->submit($t('Me connecter'), array('id' = 'submit'); ?>
<?=$this->form->end(); ?>

</div>