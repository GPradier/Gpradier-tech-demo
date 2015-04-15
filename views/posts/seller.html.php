<link rel="stylesheet" href="http://fortawesome.github.com/Font-Awesome/assets/css/font-awesome.css">

<div id="backend" style="padding:5px 10px">

<!--

<h2>Liste de mes articles</h2>
<h4><?=$this->html->link('Créer un nouveau produit', array('controller' => 'posts', 'action' => 'add'))?></h4>

-->

<style>
.headeractions {float:left;margin:10px}

.headeractions a {
float: left;
margin-left: 15px;
padding: 10px 30px;
border: 1px solid #bbbdc7;
border-bottom-color: #8F8F8F;
border-radius: 4px;
box-shadow: 0 1px 1px rgba(0,0,0,0.21);
background: url('/favstory/new/img/bg.png') repeat-x #e7e8ee 0 -10px ;
text-decoration: none;
text-shadow: 0 1px 0 #fff;
}

.headeractions a:hover {background:#FFFFFF !important}

.ico-center{display: block !important;width: 32px;margin: 8px auto;}
.headeractions a.active {background: url(/favstory/new/img/bg_h.gif) repeat-x 0 bottom #fc981d;color: #FFF;text-shadow: 0 1px 0 rgb(185, 155, 84);}
.headeractions a.active:hover{background: #fc981d !important}
</style>

<div style="background:url('/favstory/new/img/bg.png') repeat-x #e7e8ee 0 -10px;margin:-5px -10px;border:1px solid #bbbdc7;padding:10px">

<div class="headeractions" style="float:none;width:1080px;margin:0 auto;">
<?=$this->html->link('<i class="icon-globe icon-2x ico-center"></i>Histoire de ma marque', array('controller' => 'posts', 'action' => 'mainstory'), array('escape' => false))?>
<?=$this->html->link('<i class="icon-edit icon-2x ico-center"></i>Liste de mes produits', array('controller' => 'posts', 'action' => 'seller'), array('class' => 'active', 'escape' => false))?>
<?=$this->html->link('<i class="icon-barcode icon-2x ico-center"></i>Cartes FavStory', array('controller' => 'posts', 'action' => 'seller'), array('escape' => false))?>
<?=$this->html->link('<i class="icon-comments-alt icon-2x ico-center"></i>Envoi de messages ciblés', array('controller' => 'posts', 'action' => 'seller'), array('escape' => false))?>
<?=$this->html->link('<i class="icon-bar-chart icon-2x ico-center"></i>Accès aux statistiques', array('controller' => 'pages', 'action' => 'stats'), array('escape' => false))?>
<div style="clear:both"></div>
</div>

</div>

<div class="headeractions">
<!--<?=$this->html->link('Liste de mes articles', array('controller' => 'posts', 'action' => 'seller'), array('escape' => false))?>-->
<?=$this->html->link('Créer un nouveau produit', array('controller' => 'posts', 'action' => 'add'))?>
<!--<?=$this->html->link('Statistiques', array('controller' => 'favclub', 'action' => 'index'))?>-->
<div style="clear:both"></div>
</div>
<div style="clear:both"></div>

<script type="text/javascript">
$(function() {
var refreshDashboard = function(data) {
	$('#listProducts').fadeOut(600, function() {
		$('#listProducts').empty().append($(data).find('#listProducts .datacontent')).fadeIn(600);
	});
	$('#setformat').empty().append($(data).find('#setformat .datacontent'));
	$('#pagination').empty().append($(data).find('#pagination .datacontent'));
}

$(document).on('submit', '#setformat', function(event){
	event.preventDefault();
	$.ajax({
		url		: $(this).attr('action'),
		type	: $(this).attr('method'),
		data	: $(this).serialize(),
		success	: function(data) {
			refreshDashboard(data);
		}
	});
});

$(document).on('click', '.dynjs a', function(event){
	event.preventDefault();
	$.get($(this).attr('href'), function(data) {
		refreshDashboard(data);
	});
});

});
</script>
<style>
.menuseller{display:none}
ul.products li:hover > .menuseller{display:block}

ul.products {text-align:center;margin-top:15px;background:#E0E0E0}
ul.products li{text-align:left;float:none;display:inline-block;*display:inline; /*IE7*/*zoom:1; /*IE7*/}

.menuseller a {padding:5px 0 5px 20px;margin:12px 5px;text-align:center;background:#FFF;display:block;border-radius:10px;border:1px solid transparent;position:relative}
.menuseller a:hover {background:#fc981d;color:#FFF;border-color:#FFF}

.stepValid, .stepError{
position: absolute;
background: url('/favstory/new/img/spritemap.png') 159px 213px;
width: 40px;
height: 40px;
right: 0;
margin-top: -15px;
margin-right: -5px;
background-color: rgb(255, 255, 255);
border-radius: 100px;}

.stepError{
background-position:159px 253px;}

/* ADMIN INTERFACE */
select {border-radius: 5px;border: 1px solid #999;padding: 3px;}
tr:hover td{background:#d8dce6;border-top:1px solid gray}
td {border-left:1px dotted #bebebe}
th {background: url(http://envato.stammtec.de/themeforest/grape/img/tables/table-head-bg.png) repeat-x;
border-bottom: 1px solid #bcbcbc;border-top: 0;padding: 8px 13px;border-left: 0;border-right: 1px solid #c3c3c3;font-weight: bold;cursor: pointer;}
::selection {background: #8eacb7;color: #fff;}

.actionstab a {display:inline-block;background:#fff;padding:4px;border:1px solid #AAA;border-radius:4px;margin:1px 4px 0 0}
.actionstab a:hover {background:#fc981d;color:#fff;border-color:#fff}

tr:nth-child(odd) {background:#f7f8fa}

.mr4 {margin-right:4px;}
.mrabs {position: absolute;left: 5px;top: 8px;}
</style>

<div style="border:1px solid gray">
	<form method="GET" id="setformat" action="" style="margin:0;padding:8px 10px 0 10px;border-bottom:1px solid rgb(196,195,188)">
	<div class="datacontent">
	<?=$this->form->hidden('format', array('value' => $disptype))?>
	<span style="float:left;margin:.5em 0 1em 0"><!--// Margin équivalent au select-->
		<span style="margin:0 10px"><b><?=$total?></b> produits</span>
		<span style="margin:0 10px">Page <?=$page?> / <?=ceil($total/$limit)?></span>
	</span>
	
	<div class="dynjs">
	<span style="float:left;margin:.5em 0 1em 3em"><!--// Margin équivalent au select-->
		<span style="margin:0 5px">Affichage :</span>
		<span style="margin:0 5px"><?=$this->html->link('', array('action' => 'seller', 'args' => '?format=image&limit='.$limit.'&sort='.$sort.'&orderby='.$orderby.'&page='.$page), array('class' => 'icon-th-large'))?></span>		
		<span style="margin:0 5px"><?=$this->html->link('', array('action' => 'seller', 'args' => '?format=table'), array('class' => 'icon-table'))?></span>
	</span>
	</div>
	
	<span style="float:right">
		<span style="margin:0 10px">Produits par Page : <div style="display:inline-block"><?=$this->form->select('limit', array(10 => 10, 20=>20, 50=>50, 100=>100, 200=>200, 2=>'2 (tests only)'), array('value' => $limit))?></div></span>
		<span style="margin:0 10px">Trier par : 
			<div style="display:inline-block"><?=$this->form->select('sort', array('_id'=>'Date de création', 'title'=>'Nom', 'valid'=>'Etat de publication'), array('value'=>$sort))?></div>
			<div style="display:inline-block"><?=$this->form->select('orderby', array('ASC'=>'Croissant', 'DESC'=>'Décroissant'), array('value'=>$orderby))?></div>
		</span>
		
		<span style="margin:0"><div style="display:inline-block"><?=$this->form->submit('OK', array('style' => 'margin:0;padding:0 10px'))?></div></span>
	</span>
	
	<div style="clear:both"></div>
	</div>
	</form>

<div id="listProducts"><div class="datacontent">
<?php if($disptype == 'table') {?>
<table style="margin:0">
	<tr>
		<th><?=$this->form->checkbox('all')?></th>
		<th>Image</th>
		<th>Nom</th>
		<th>Code Barre</th>
		<th>État</th>
		<th>Actions</th>
	</tr>
	<?php foreach($posts as $post): ?>
	<tr>
		<td><?=$this->form->checkbox('checkbox_'.$post['_id'])?></td>
		<td style="text-align:center;padding:0;width:240px">
			<div style="width:240px;height:60px;overflow:hidden"><?php /* full td image */ ?>
			<?php /*
			$image = ($post['firstimage'] ? $this->html->image(array('controller' => 'Upload', 'action' => 'view', 'args' => $post['firstimage']['_id']), array('style' => 'max-width:75px;max-height:75px')) : $this->html->image($post['image'], array('style' => 'max-width:75px;max-height:75px')));
			*/
			?>
			<?php $image = ($post['firstimage'] ? $this->html->image(array('controller' => 'Upload', 'action' => 'view', 'args' => $post['firstimage']['_id']), array('style' => 'width:100%;margin:-50%')) : $this->html->image($post['image'], array('style' => 'width:100%;margin:-50%')));?>
						
			<?php echo $image;?>
			</div>
			<?php /* Old format :
			<?=$this->html->image($post['image'], array('style' => 'max-width:50px;max-height:50px'));?>
			*/?>
		</td>
		<td><?= $this->html->link($post['title'], 'posts/view/'.$post['_id'], array('title' => substr($post['body'], 0, 100))); ?></td>
		<td><?=$post['codebarre']?></td>
		<td><?php
			echo ($post['valid'] ? '<span style="color:#0A0">Activé</span>' : '<span style="color:#A00">Désactivé</span>');
		?></td>
		<td style="width:525px">
			<div class="actionstab">
			<?=$this->html->link('<i class="icon-cog mr4"></i>Configurer', array('controller' => 'posts', 'action' => 'edit', 'args' => array($post['_id'])), array('escape' => false)); ?>
			<?=$this->html->link('<i class="icon-copy mr4"></i>Duppliquer', array('controller' => 'posts', 'action' => 'copy', 'args' => array($post['_id'])), array('escape' => false)); ?>
			<?=$this->html->link('<i class="icon-info-sign mr4"></i>Ecrire l\'histoire', array('controller' => 'posts', 'action' => 'edit', 'args' => array($post['_id'] .'?step=etape3')), array('escape' => false)); ?>
			<?=$this->html->link('<i class="icon-qrcode mr4"></i>Modifier le code barre', array('controller' => 'posts', 'action' => 'edit', 'args' => array($post['_id'] .'?step=etape2')), array('escape' => false)); ?>
			<?=$this->html->link('<i class="icon-trash mr4"></i>Supprimer', array('controller' => 'posts', 'action' => 'delete', 'args' => array($post['_id'])), array('escape' => false, 'onclick' => 'return confirm("Supprimer ce produit ?\n\nNom : '. $post['title'] .'")')); ?>
			<!--<?=$this->html->link('Gestion des fans', array('controller' => 'favclub', 'action' => 'product', 'args' => array($post['_id']))); ?>-->
			</div>
		</td>
	</tr>
	<?php endforeach; ?>
</table>
<?php }
if($disptype == 'image') {?>
<ul class="products">
	<?php foreach($posts as $post): ?>
	<?php $avatar = ($post['user']['avatar'] ? $post['user']['avatar'] : 'i_19.png'); ?>
	<a href="posts/view/<?=$post['_id']?>">
	<?php $image = ($post['firstimage'] ? $this->html->image(array('controller' => 'Upload', 'action' => 'view', 'args' => $post['firstimage']['_id'])) : $this->html->image($post['image']));?>
	</a>
	
	<li style="position:relative">
		<div class="<?=($post['valid'] ? 'stepValid' : 'stepError')?>"></div>
		<div class="imgcont"><?php echo $image?></div>
		<span class="product"><?=$post['title']?></span>
		<div class="author">
			<?=$this->html->image($avatar, array('class' => 'avatar avatarSmall'))?>
			<p>
				<b style="display:block"><?=$post['user']['firstname']?> <?=$post['user']['lastname']?></b>
				<?=$post['user']['location']?>
			</p>
		</div>
		<div class="menuseller" style="position: absolute;width: 200px;height: 297px;background: rgba(115,115,115,0.65);top:10px;">
		
		<?=$this->html->link('<i class="icon-cog mrabs"></i>Configurer', array('controller' => 'posts', 'action' => 'edit', 'args' => array($post['_id'])), array('escape' => false)); ?>
		<?=$this->html->link('<i class="icon-copy mrabs"></i>Duppliquer', array('controller' => 'posts', 'action' => 'copy', 'args' => array($post['_id'])), array('escape' => false)); ?>
		<?=$this->html->link('<i class="icon-info-sign mrabs"></i>Ecrire l\'histoire', array('controller' => 'posts', 'action' => 'edit', 'args' => array($post['_id'] .'?step=etape3')), array('escape' => false)); ?>
		<?=$this->html->link('<i class="icon-barcode mrabs"></i>Modifier le code barre', array('controller' => 'posts', 'action' => 'edit', 'args' => array($post['_id'] .'?step=etape2')), array('escape' => false)); ?>
		<?=$this->html->link('<i class="icon-bar-chart mrabs"></i>Visualiser l\'Analytique', array('controller' => 'posts', 'action' => 'analytic', 'args' => array($post['_id'] .'?step=etape2')), array('escape' => false)); ?>
		<?=$this->html->link('<i class="icon-trash mrabs"></i>Supprimer', array('controller' => 'posts', 'action' => 'delete', 'args' => array($post['_id'])), array('escape' => false, 'onclick' => 'return confirm("Supprimer ce produit ?\n\nNom : '. $post['title'] .'")')); ?>
		
		<?php /*
			<?=$this->html->link('Configurer le produit', array('controller' => 'posts', 'action' => 'edit', 'args' => array($post['_id']))); ?>
			<?=$this->html->link('Duppliquer', array('controller' => 'posts', 'action' => 'copy', 'args' => array($post['_id']))); ?>
			<?=$this->html->link('Raconter l\'histoire', array('controller' => 'posts', 'action' => 'edit', 'args' => array($post['_id'] .'?step=etape3'))); ?>
			<!--<?=$this->html->link('Modifier le code barre', array('controller' => 'posts', 'action' => 'edit', 'args' => array($post['_id'] .'?step=etape2'))); ?>-->
			<?=$this->html->link('Communiquer avec les fans', array('controller' => 'favclub', 'action' => 'product', 'args' => array($post['_id']))); ?>
			<?=$this->html->link('Analytique & Statistiques', array('controller' => 'stats', 'action' => 'product', 'args' => array($post['_id']))); ?>
			<?=$this->html->link('Supprimer ou Dépublier', array('controller' => 'posts', 'action' => 'delete', 'args' => array($post['_id'])), array('onclick' => 'return confirm("Supprimer ce produit ?\n\nNom : '. $post['title'] .'")')); ?>
		*/ ?>
		</div>
	</li>
	<?php endforeach; ?>
</ul>
<?php } ?>

</div></div>



<div id="pagination" class="clearfix" style="display:block;background:#fff;padding:4px 12px">
<div class="datacontent dynjs">
    <div class="left"><?php
        if ($total > $limit && $page != 1) {
		/*
		echo $this->html->link('Produits suivants &rarr;', array(
			'controller' => 'posts', 'action' => 'index',
			'page' => $page - 1, 'limit' => $limit
		), array('escape' => false));
		*/
		//echo $this->html->link('Produits suivants &rarr;', array('controller' => 'posts', 'action' => 'index','args'=>array('page' => $page - 1, 'limit' => $limit)), array('escape' => false));
		
			echo $this->html->link('&larr; Produits précédents', 'posts/seller/'.($admin ? 'admin' : '').'?format='.$disptype.'&limit='.$limit.'&page='.($page-1).'&sort='.$sort.'&order='.$orderby, array('escape' => false));
	} ?>
    </div>
    <div class="right"><?php
        if ($total > $limit && $page != ceil($total/$limit)) {
		/*
		echo $this->html->link('&larr; Produits précédents', array(
			'controller' => 'posts', 'action' => 'index',
			'page' => $page + 1, 'limit' => $limit
		), array('escape' => false));
		*/
		//echo $this->html->link('Produits suivants &rarr;', array('controller' => 'posts', 'action' => 'seller','args'=>array('page' => $page + 1, 'limit' => $limit)), array('escape' => false));
		
			echo $this->html->link('Produits suivants &rarr;', 'posts/seller/'.($admin ? 'admin' : '').'?format='.$disptype.'&limit='.$limit.'&page='.($page+1).'&sort='.$sort.'&order='.$orderby, array('escape' => false));
	}
	?>
	</div>
</div>
</div>

</div>

<h4><?=$this->html->link('Supprimer les produits séléctionnés', '#');?></h4>

</div>