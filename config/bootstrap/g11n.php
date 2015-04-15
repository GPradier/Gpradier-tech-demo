<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2013, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

/**
 * This bootstrap file contains configurations for all globalizing
 * aspects of your application.
 */
use lithium\core\Libraries;
use lithium\core\Environment;
use lithium\g11n\Locale;
use lithium\g11n\Catalog;
use lithium\g11n\Message;
use lithium\g11n\Multibyte;
use lithium\util\Inflector;
use lithium\util\Validator;
use lithium\net\http\Media;
use lithium\action\Dispatcher as ActionDispatcher;
use lithium\console\Dispatcher as ConsoleDispatcher;
use lithium\storage\Session;

/**
 * Dates
 *
 * Sets the default timezone used by all date/time functions.
 */
date_default_timezone_set('UTC');

/**
 * Locales
 *
 * Adds globalization specific settings to the environment. The settings for
 * the current locale, time zone and currency are kept as environment settings.
 * This allows for _centrally_ switching, _transparently_ setting and
 * retrieving globalization related settings.
 *
 * The environment settings are:
 *
 *  - `'locale'` The default effective locale.
 *  - `'locales'` Application locales available mapped to names. The available locales are used
 *               to negotiate he effective locale, the names can be used i.e. when displaying
 *               a menu for choosing the locale to users.
 *
 * @see lithiumm\g11n\Message
 * @see lithiumm\core\Environment
 */
 /*
$locale = 'en';
$locales = array('en' => 'English');
 */

$locale = 'fr';
$locales = array('fr' => 'Français', 'en' => 'English', 'de' => 'Deutsch', 'cn' => '中国的');
//ç	Alt+ 0231	&#231;	&ccedil;

Environment::set('production', compact('locale', 'locales'));
Environment::set('development', compact('locale', 'locales'));
Environment::set('test', array('locale' => 'en', 'locales' => array('en' => 'English')));

/**
 * Effective/Request Locale
 *
 * Intercepts dispatching processes in order to set the effective locale by using
 * the locale of the request or if that is not available retrieving a locale preferred
 * by the client.
 *
 * @see lithiumm\g11n\Message
 * @see lithiumm\core\Environment
 */
$setLocale = function($self, $params, $chain) {
	if ($params['request']->locale()) {
		Session::write('localePreferred', $params['request']->locale());
	}
	
	if (!$params['request']->locale()) {
		if(Session::read('localePreferred')) {
			$params['request']->locale(Session::read('localePreferred'));
		}
		else {
			$params['request']->locale(Locale::preferred($params['request']));
		}
	}

	Environment::set(true, array('locale' => $params['request']->locale()));

	return $chain->next($self, $params, $chain);
};
ActionDispatcher::applyFilter('_callable', $setLocale);
ConsoleDispatcher::applyFilter('_callable', $setLocale);

/**
 * Resources
 *
 * Globalization (g11n) catalog configuration.  The catalog allows for obtaining and
 * writing globalized data. Each configuration can be adjusted through the following settings:
 *
 *   - `'adapter'` _string_: The name of a supported adapter. The builtin adapters are `Memory` (a
 *     simple adapter good for runtime data and testing), `Php`, `Gettext`, `Cldr` (for
 *     interfacing with Unicode's common locale data repository) and `Code` (used mainly for
 *     extracting message templates from source code).
 *
 *   - `'path'` All adapters with the exception of the `Memory` adapter require a directory
 *     which holds the data.
 *
 *   - `'scope'` If you plan on using scoping i.e. for accessing plugin data separately you
 *     need to specify a scope for each configuration, except for those using the `Memory`,
 *     `Php` or `Gettext` adapter which handle this internally.
 *
 * @see lithiumm\g11n\Catalog
 * @link https://github.com/UnionOfRAD/li3_lldr
 * @link https://github.com/UnionOfRAD/li3_cldr
 */
Catalog::config(array(
	'runtime' => array(
		'adapter' => 'Memory'
	),
	// 'app' => array(
	// 	'adapter' => 'Gettext',
	// 	'path' => Libraries::get(true, 'resources') . '/g11n'
	// ),
	'lithium' => array(
		'adapter' => 'Php',
		'path' => LITHIUM_LIBRARY_PATH . '/lithium/g11n/resources/php'
	)
) + Catalog::config());

/**
 * Multibyte Strings
 *
 * Configuration for the `Multibyte` class which allows to work with UTF-8
 * encoded strings. At least one configuration named `'default'` must be
 * present. Available adapters are `Intl`, `Mbstring` and `Iconv`. Please keep
 * in mind that each adapter may act differently upon input containing bad
 * UTF-8 sequences. These differences aren't currently equalized or abstracted
 * away.
 *
 * @see lithiumm\g11n\Multibyte
 */
Multibyte::config(array(
//	'default' => array('adapter' => 'Intl'),
	'default' => array('adapter' => 'Mbstring'),
//	'default' => array('adapter' => 'Iconv')
));

/**
 * Transliteration
 *
 * Load locale specific transliteration rules through the `Catalog` class or
 * specify them manually to make `Inflector::slug()` work better with
 * characters specific to a locale.
 *
 * @see lithiumm\g11n\Catalog
 * @see lithium\util\Inflector::slug()
 */
// Inflector::rules('transliteration', Catalog::read(true, 'inflection.transliteration', 'en'));
// Inflector::rules('transliteration', array('/É|Ê/' => 'E'));

/**
 * Grammar
 *
 * If your application has custom singular or plural rules you can configure
 * that by uncommenting the lines below.
 *
 * @see lithiumm\g11n\Catalog
 * @see lithium\util\Inflector
 */
// Inflector::rules('singular', array('rules' => array('/rata/' => '\1ratus')));
// Inflector::rules('singular', array('irregular' => array('foo' => 'bar')));
// Inflector::rules('plural', array('rules' => array('/rata/' => '\1ratum')));
// Inflector::rules('plural', array('irregular' => array('bar' => 'foo')));
// Inflector::rules('uninflected', 'bord');
// Inflector::rules('uninflected', array('bord', 'baird'));

/**
 * Validation
 *
 * Adds locale specific rules through the `Catalog` class. You can load more
 * locale dependent rules into the by specifying them manually or retrieving
 * them with the `Catalog` class.
 *
 * Enables support for multibyte strings through the `Multibyte` class by
 * overwriting rules (currently just `lengthBetween`).
 *
 * @see lithiumm\g11n\Catalog
 * @see lithiumm\g11n\Multibyte
 * @see lithium\util\Validator
 */
foreach (array('phone', 'postalCode', 'ssn') as $name) {
	Validator::add($name, Catalog::read(true, "validation.{$name}", 'en_US'));
}
Validator::add('lengthBetween', function($value, $format, $options) {
	$length = Multibyte::strlen($value);
	$options += array('min' => 1, 'max' => 255);
	return ($length >= $options['min'] && $length <= $options['max']);
});

/**
 * In-View Translation
 *
 * Integration with `View`. Embeds message translation aliases into the `View`
 * class (or other content handler, if specified) when content is rendered. This
 * enables translation functions, i.e. `<?=$t("Translated content"); ?>`.
 *
 * @see lithiumm\g11n\Message::aliases()
 * @see lithiumm\net\http\Media
 */
Media::applyFilter('_handle', function($self, $params, $chain) {
	$params['handler'] += array('outputFilters' => array());
	$params['handler']['outputFilters'] += Message::aliases();
	return $chain->next($self, $params, $chain);
});



/**
 * Custom Datas Handling
 *
 * A déplacer ultérieurement
 */
/*
var_dump(Environment::get('production'));

var_dump($setLocale);

var_dump(Locale::preferred($params['request']));

var_dump($this->request);

exit();
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
		'articles dans mon panier' => 'products',
		'Voir mon panier' => 'View cart',
		'Frais de livraison' => 'Shipping',
		'Commander' => 'Check out',
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
		'articles dans mon panier' => 'Produkte',
		'Voir mon panier' => 'Warenkorb',
		'Frais de livraison' => 'Versandkosten',
		'Commander' => 'Kasse',
	);
}

if(isset($datas)) {
	Catalog::write('runtime', 'message', $isocode, $datas);
}
*/

?>