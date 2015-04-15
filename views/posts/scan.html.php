<h2><?=$this->html->link('Liste de mes articles', array('controller' => 'posts', 'action' => 'seller'));?> &raquo; Scanner &ldquo;<?=$post['title']?>&rdquo;</h2>

<?=$this->form->create($post, array('method' => 'post')); ?>	
	<?=$this->form->hidden('id'); ?><!--edit-->
	<?=$this->form->field(array('barcode' => 'Code Barre du produit'));?>
	<?=$this->form->submit('Mettre à jour le produit'); ?>
<?=$this->form->end();?>