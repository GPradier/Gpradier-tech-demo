<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2010, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

use \lithium\data\Connections;

?>
<h3><?php echo $this->title('FavStory Homepage'); ?></h3>

<h1>Des objets qui ont une histoire à raconter</h1>

<ol>
	<li>Je scanne le code barre d'un objet</li>
	<li>Je découvre son histoire et le message qu'un proche m'a laissé</li>
	<li>J'accède à du contenu privilégié</li>
	<li>Je laisse un message à mes proches</li>
</ol>

<?=$this->html->link('Télécharger l\'application Iphone', '')?>
ou
<?=$this->html->link('Découvrez les histoires', array('controller' => 'posts', 'action' => 'index'))?>