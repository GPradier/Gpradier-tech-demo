<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2010, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

use \lithium\data\Connections;
use lithium\g11n\Catalog;


use lithium\g11n\Message;
?>
<h3><?php echo $this->title('Globalization'); ?></h3>

<?php

// Configures the runtime source.
//Catalog::config(array('runtime' => array('adapter' => 'Memory')));

/*
// Already in G11N bootstrap
Catalog::config(array(
    'runtime' => array('adapter' => 'Memory'),
    'app' => array('adapter' => 'Gettext', 'path' => LITHIUM_APP_PATH . '/resources/g11n'),
    'lithium' => array(
        'adapter' => 'Php',
        'path' => LITHIUM_LIBRARY_PATH . '/lithium/g11n/resources/php'
    )
) + Catalog::config());
*/


$data_en = array(
	'Liste des produits' => 'List of products',
	'Ajouter un produit' => 'Add an item',
	'Mon panier' => 'My cart',
	'Identifiant' => 'Email adress',
	'Mot de passe' => 'Password',
	'Connexion' => 'Sign in',
	'S\'inscrire' => 'Register',
	'aucun article dans mon panier' => 'No product in my cart',
	'article dans mon panier' => '1 product',
	'articles dans mon panier' => 'products',
	'Voir mon panier' => 'View cart',
	'Frais de livraison' => 'Shipping',
	'Commander' => 'Check out',
);

$data_de = array(
	'Liste des produits' => 'Liste der Produkte',
	'Ajouter un produit' => 'Ein Produkt hinzufügen',
	'Mon panier' => 'Mein Warenkorb',
	'Identifiant' => 'E-Mail Adresse',
	'Mot de passe' => 'Passwort',
	'Connexion' => 'Anmelden',
	'S\'inscrire' => 'Registrieren',
	'aucun article dans mon panier' => 'Kein Produkt im Warenkorb',
	'article dans mon panier' => '1 Produkt',
	'articles dans mon panier' => 'Produkte',
	'Voir mon panier' => 'Warenkorb',
	'Frais de livraison' => 'Versandkosten',
	'Commander' => 'Kasse',
);

Catalog::write('runtime', 'message', 'en', $data_en);
Catalog::write('runtime', 'message', 'de', $data_de);

extract(Message::aliases());

foreach($data_en as $key => $value) {
	echo $t($key) .'<br/>';
}

?>