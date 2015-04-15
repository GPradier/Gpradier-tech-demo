<?php
use lithium\security\Auth;
?>
<!doctype html>
<html>
<head>
	<?php echo $this->html->charset();?>
	<title><?=$title;?> - FavStory </title>
	<?php echo $this->html->style(array('lithium_s', 'debug', 'favstory', 'dropzone')); ?>
	<?php echo $this->scripts('<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>'); ?>
	<?php echo $this->html->script('favstory.js'); ?>
	<?php echo $this->html->link('Icon', null, array('type' => 'icon')); ?>
	
<link href='http://fonts.googleapis.com/css?family=Kavoon' rel='stylesheet' type='text/css'>
<link href='http://fonts.googleapis.com/css?family=Roboto+Condensed' rel='stylesheet' type='text/css'>

</head>
<body>
	<header>
		<div id="header">
			<?=$this->html->link(' ', 'Pages::home', array('id' => 'logo'))?>
			<div id="headerMenu">
				<ul>					
					<li><?=$this->html->link($t('Histoires'), array('controller' => 'posts', 'action' => 'index')); ?></li>
					<li><?=$this->html->link($t('Mes favs'), array('controller' => 'favoris', 'action' => 'index'), array('id' => 'menuFavoris')); ?></li>
					<li id="scan"><?=$this->html->link($t(' '), array('controller' => 'scan', 'action' => 'index')); ?></li>
					<li><?=$this->html->link($t('Fav Club'), array('controller' => 'messages', 'action' => 'index')); ?></li>
					<li><?=$this->html->link($t('Mon compte'), array('controller' => 'users', 'action' => 'profile')); ?></li>
				</ul>
			</div>
			<div id="login">
				<?php if($me = Auth::check('default')) { ?>
					<div style="color:#fff;text-align:right;width:200px;">
					<?=$me['firstname'] .' '. $me['lastname'] .'';?>
					<br/>
					<?=$this->html->link($t('Se déconnecter'), array('controller' => 'Sessions', 'action' => 'delete'), array('class'=>'backoffice'));?>
					</div>
				<?php
				}
				else {
				?>
				<?=$this->html->link($t('Inscription'), array('controller' => 'Users', 'action' => 'add'), array('id'=>'btnInscription'))?><?=$this->html->link($t('Connexion'), array('controller' => 'Sessions', 'action' => 'add'), array('id'=>'btnConnexion'))?>
				<?php } ?>

				<?=$this->html->link($t('Espace créateurs &raquo;'), array('controller' => 'posts', 'action' => 'seller'), array('class'=>'backoffice', 'escape' => false)); ?>
			</div>
		</div>
	</header>
	<?php echo $this->content();?>
	<footer style="background:#fff;padding:25px 100px 40px;color:#777;border-top:1px solid #cecdcb;margin:0;margin-top:25px;font-family:'Roboto Condensed', sans-serif;">
		<div class="left">&copy; FavStory 2013, Tous droits résérvés</div>
	</footer>
</body>
</html>