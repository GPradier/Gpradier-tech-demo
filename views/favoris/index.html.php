<?php error_reporting(E_ALL ^ E_NOTICE);?>

<h2 class="sectionTitle" id="histoire">Mes objets préférés</h2>

<div class="clearfix">
<?php
foreach($products as $product) {
?>
<div class="product">
    <h1><?= $this->html->link($product['title'], 'posts/view/'.$product['_id']); ?></h1>
	<div class="picture"><?=$this->html->image($product['image']);?></div>
    <p><?=$product['body'] ?></p>
</div>
<?php
}
?>
</div>


<h2 class="sectionTitle" id="histoire">Mes créateurs préférés</h2>

<div class="clearfix">
<?php
if($creators) {
foreach($creators as $user) {
?>
<div class="product">
<h3><?=$user['firstname']?> <?=$user['lastname']?><h3>
<h4><?=($user['location'] ? $user['location'] : 'N/A - France')?></h4>
	<div class="picture"><?=$this->html->image($user['avatar']);?></div>
	<ul>
		<li><?=$this->html->link('Contacter le vendeur', 'messages/add/'.$user['_id']); ?></li>
		<li><?=$this->html->link('Visiter sa boutique', 'users/shop/'.$user['_id']); ?></li>
		<li><?=$this->html->link('Retirer de mes favoris', 'favoris/delete/'.$user['_id']); ?></li>
	</ul>
</div>
<?php
}
}
else {
	echo '<h3>Aucun créateur dans mes favoris.</h3>';
}
?>
</div>