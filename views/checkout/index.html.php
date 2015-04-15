<?php if(!$posts) { ?>
<h1>Panier vide</h1>
<?php } else {?>

<div style="display: block;clear: both;background: #fafafa;padding: 1em 2em 2em 2em;border: 1px solid #e6e6e6;position:fixed;top:180px;margin:auto;right:20px">
<div style="clear:both"><div style="float:left">Total articles :</div><div style="float:right"><?=$total_articles?>€</div></div>
<div style="clear:both"><div style="float:left">Livraison :</div><div style="float:right"><?=$total_ship?>€</div></div>
<hr style="clear:both"/>
<div style="clear:both"><div style="float:left">Total</div><div style="float:right"><b style="font-size:1.5em"><?=$total_both?>€</b></div></div>
<br/>
<h3 style="text-align:center"><?=$this->html->link('Passer ma commande');?></h3>
</div>

<?php foreach($posts as $post): ?>

<article>
	<div style="float:left"><p><?=$this->html->image($post['image'], array('style'=>'max-width:200px;max-height:150px;'));?></p></div>
	<div style="margin-left:230px">
    <h1><?= $this->html->link($post['title'], 'posts/view/'.$post['id_product']); ?></h1>
	<p>
		<?=$post['price']?>€ + <?=$post['ship']?>€ de livraison
	</p>
	<!--<?=$this->html->link('Modifier', array('controller' => 'posts', 'action' => 'edit', 'args' => array($post['_id']))); ?>-->
    <p>
		<?=$this->html->link('Retirer du Panier', array('controller' => 'checkout', 'action' => 'delete', 'args' => array($post['_id'])), array('onclick' => 'return confirm("Supprimer ce produit ?")')); ?>
	</p>
	</div>
	<div style="clear:both"></div>
</article>
<?php endforeach; ?>
<?php }?>