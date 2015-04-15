<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2010, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */
?>
<!doctype html>
<html>
<head>
	<?php echo $this->html->charset();?>
	<title><?=$title;?> - FavStory </title>
	<?php echo $this->html->style(array('lithium', 'debug')); ?>
	<?php echo $this->scripts('<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>'); ?>
	<?php echo $this->html->link('Icon', null, array('type' => 'icon')); ?>
</head>
<body class="app">
	<div id="container">
		<div id="header">
			<!-- BEGIN locales -->
			<?php
			use lithium\core\Environment;
			use lithium\g11n\Message;
			use lithium\g11n\Catalog;
			extract(Message::aliases());
			?>
			<div id="navigation">
				<div id="left">
				<?=$this->html->link('FavStory', 'Pages::home', array('id' => 'logo'))?>
				
				<ul id="locales">
					<?php foreach (Environment::get('locales') as $locale => $name): ?>
						<li><?=$this->html->link($name, compact('locale') + $this->_request->params); ?></li>
					<?php endforeach; ?>
					<!--
					<li><?=$this->html->link('Démo traduction', 'pages/globalization')?></li>
					-->
					
					<!--<li>Current : <b><?=Environment::get('locale')?></b></li>-->
				</ul>
				</div>
				
				<div id="center" style="width:800px">
					<ul>
						<li><?=$this->html->link($t('Histoires'), array('controller' => 'posts', 'action' => 'index')); ?></li>
						<li><?=$this->html->link($t('Favoris'), array('controller' => 'favoris', 'action' => 'index'), array('id' => 'menuFavoris')); ?></li>
						<li id="scan"><?=$this->html->link($t('Scanner un objet'), array('controller' => 'scan', 'action' => 'index')); ?></li>
						<li><?=$this->html->link($t('Club'), array('controller' => 'messages', 'action' => 'index')); ?></li>
						<li><?=$this->html->link($t('Mon compte'), array('controller' => 'users', 'action' => 'profile')); ?></li>
						<li><?=$this->html->link($t('Espace Créateurs'), array('controller' => 'posts', 'action' => 'seller')); ?></li>
						<li><?=$this->html->link($t('Backend Admin'), array('controller' => 'posts', 'action' => 'seller', 'args' => 'admin')); ?></li>
					</ul>
				</div>
				
				<div id="right">
					<?php
					use lithium\security\Auth;
					if($me = Auth::check('default')) {
						echo 'Bonjour '. $me['firstname'] .' '. $me['lastname'] .'';
						echo ' - ';
						echo $this->html->link('Se déconnecter', array('controller' => 'Sessions', 'action' => 'delete'));
					}
					else {
					?>
					<?=$this->html->link($t('Connexion'), array('controller' => 'Sessions', 'action' => 'add', 'args' => serialize($this->_request->params)))?>	
					 - 
					<?=$this->html->link($t('Inscription'), array('controller' => 'Users', 'action' => 'add', 'args' => serialize($this->_request->params)))?>
					<?php } ?>
				</div>
			</div>
			<?php
			if(Environment::get('locale') == 'en') {
				$isocode = 'en';
				$datas = array(
					'Liste des produits' => 'List of products',
					'Ajouter un produit' => 'Add an item',
					'Mon panier' => 'My cart',
					'Identifiant' => 'Email adress',
					'Mot de passe' => 'Password',
					'Connexion' => 'Sign in',
					'S\'inscrire' => 'Register',
					'aucun article dans mon panier' => 'No product in my cart',
					'article dans mon panier' => '1 product',
					'{:count} articles dans mon panier' => '{:count} products',
					'Voir mon panier' => 'View cart',
					'Frais de livraison' => 'Shipping',
					'Commander' => 'Check out',
					'Inscrivez-vous' => 'Register',
					'Email' => 'E-mail',
					'Mot de passe' => 'Password',
					'Prénom' => 'First name',
					'Nom' => 'Last name',
					'M\'inscrire' => 'Register',
					'J\'accepte les conditions générales d\'utilisation' => 'I agree with the Terms of Service and the Privacy Policy.',
				);
			}

			if(Environment::get('locale') == 'de') {
				$isocode = 'de';
				$datas = array(
					'Liste des produits' => 'Liste der Produkte',
					'Ajouter un produit' => 'Ein Produkt hinzufügen',
					'Mon panier' => 'Mein Warenkorb',
					'Identifiant' => 'E-Mail Adresse',
					'Mot de passe' => 'Passwort',
					'Connexion' => 'Anmelden',
					'S\'inscrire' => 'Registrieren',
					'aucun article dans mon panier' => 'Kein Produkt im Warenkorb',
					'article dans mon panier' => '1 Produkt',
					'{:count} articles dans mon panier' => '{:count} Produkte',
					'Voir mon panier' => 'Warenkorb',
					'Frais de livraison' => 'Versandkosten',
					'Commander' => 'Kasse',
				);
			}

			if(isset($datas)) {
				Catalog::write('runtime', 'message', $isocode, $datas);
			}
			?>
			<!-- END locales -->
			<!--
				<ul id="menu">
					<li><?=$this->html->link($t('Liste des produits'), array('controller' => 'posts', 'action' => 'index')); ?></li>
					<li><?=$this->html->link($t('Ajouter un produit'), array('controller' => 'posts', 'action' => 'add')); ?></li>
					<li><?=$this->html->link($t('Mon panier'), array('controller' => 'checkout', 'action' => 'index')); ?></li>
				</ul>
			-->

		</div>
		<div id="content"><?php echo $this->content(); ?></div>
        <div id="footer">
            <p>
                &copy; FavStory 2013
            </p>
        </div>
	</div>
</body>
</html>