<h2><?=$this->html->link('Liste de mes articles', array('controller' => 'posts', 'action' => 'seller'));?> &raquo; Modification de &ldquo;<?=$post['title']?>&rdquo;</h2>

<?php if (isset($success) && $success): ?>
    <p>Post Successfully Saved</p>
<?php else: ?>

<?=$this->form->create($post, array('method' => 'post')); ?>	
	<?=$this->form->hidden('id'); ?><!--edit-->
	
    <?=$this->form->field(array('title' => 'Nom du produit'), array('disabled' => 'disabled'));?>
    <?=$this->form->field(array('body' => 'Description'), array('type' => 'textarea', 'rows' => 4));?>
	
	<?php /*
	<?=$this->form->label('cat', 'Catégorie du produit');?>
	<?=$this->form->select('cat', array(1 => 'Mobilier', 2 => 'Services', 3 => 'Autres'), array('id' => 'cat'));?>
	*/ ?>
	
	<?=$this->form->field(array('image' => 'Miniature'));?>
	
	<hr/>
	<h3>Informations complémentaires</h3>
	
	<?=$this->form->field(array('price' => 'Prix du produit'));?>
	<?=$this->form->field(array('url_externe' => 'Page de vente du produit *'));?>
	<p>* : Page Produit sur un site commerçant de votre choix (votre site, Amazon, Etsi, etc...)</p>
	
	<h3>État du produit</h3>
		<div style="padding:10px">
		<span class="actifRadio" style="color:#090;margin:20px">
			<?=$this->form->label('availableYes', 'Activé');?>
			<?=$this->form->radio('available', array('checked' => true, 'id' => 'availableYes'));?>
		</span>
			<?=$this->form->label('availableNo', 'Désactivé');?>
			<?=$this->form->radio('available', array('checked' => false, 'id' => 'availableNo'));?>
		</div>

	<?php /*
    <?=$this->form->field(array('bodyfull' => 'Description longue'), array('type' => 'textarea', 'rows' => 10));?>
	<?=$this->form->field(array('price' => 'Prix du produit'));?>
	<?=$this->form->field(array('ship' => 'Frais de livraison'));?>
	<?=$this->form->field(array('stock' => 'Quantité'));?>
	*/ ?>
	
	<?=$this->form->submit('Mettre à jour le produit'); ?>
	
<?=$this->form->end(); ?>
<?php endif; ?>