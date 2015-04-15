<!-- BEGIN entête produit -->
<div class="product">
    <h1><?= $this->html->link($post['title'], 'posts/view/'.$post['_id']); ?></h1>
    <div class="picture"><?=$this->html->image($post['image']);?></div>
    <p><?=$post['body'] ?></p>
</div>

<!-- MENU Product -->
<ul style="float:left;margin-left:35px">
	<h1 style="margin:0">Gérer le produit</h1>
	<li><?=$this->html->link('Voir la page produit', array('controller' => 'posts', 'action' => 'view', 'args' => $post['_id']));?></li>
	<li><?=$this->html->link('Commander', array('controller' => 'checkout', 'action' => 'add', 'args' => $post['_id']));?></li>
	<hr/>
	<li><?=$this->html->link('Modifier le  produit', array('controller' => 'posts', 'action' => 'edit', 'args' => $post['_id']));?></li>
	<li><?=$this->html->link('Supprimer le produit', array('controller' => 'posts', 'action' => 'delete', 'args' => $post['_id']));?></li>

	<li><?=$this->html->link('Ajouter des éléments à l`histoire du produit', array('controller' => 'posts', 'action' => 'story', 'args' => $post['_id']));?></li>
	<li><?=$this->html->link('Gérer l`affichage des éléments de l`histoire', array('controller' => 'posts', 'action' => 'sortable', 'args' => $post['_id']));?></li>
</ul>
<div style="clear:both"></div>
<hr/><hr/>
<!-- END entête produit -->



<?php $this->scripts('<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>');?>
<?php $this->scripts('<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.7/jquery-ui.min.js"></script>');?>

<style>
#add{width: auto;
background: #DDD;
margin: auto;
display: inline-block;
padding:0 15px;
margin:10px}

#add .block{
width: 100px;
height: 100px;
background: #fff;
float: left;
margin: 15px;
border: #31AFD9 3px solid;
text-align: center;
}
#add .block .image{
background: url('/favstory/img/icons_favstory.png');
width: 25px;
height: 25px;
display: block;
margin: 20px auto 10px;
}
#add .block a{color:#666}
#add .block a:hover{color:#00a8e6}

</style>

<script type="text/javascript">
$(function() {
	$('#add a').click(function(event){
		event.preventDefault();
		$('#formAdd form').hide();
		var kk = $(this).attr('href');
		if($(kk).length > 0) {
			$(kk).toggle();
		}
		return false;
	});
});
</script>

<div id="add">
	<h3>Ajouter un élément d'histoire</h3>
	<a href="#title_form"><div class="block">
	<span class="image"></span>
	<b>AJOUTER<br/>UN TITRE</b></div></a>

	<a href="#text_form"><div class="block">
	<span class="image" style="background-position: 27px 0;"></span>
	<b>AJOUTER<br/>DU TEXTE</b></div></a>

	<a href="#list_form"><div class="block">
	<span class="image" style="background-position: 56px 0;"></span>
	<b>AJOUTER<br>UNE LISTE</b></div></a>

	<a href="#image_form"><div class="block"><span class="image" style="background-position: 86px 0;"></span>
	<b>AJOUTER<br/>UNE IMAGE</b></div></a>

	<a href="#video_form"><div class="block"><span class="image" style="background-position: 237px 0;"></span>
	<b>AJOUTER<br/>UNE VIDEO</b></div></a>
</div>

<div id="formAdd">
	<?=$this->form->create(null, array('method' => 'post', 'style' => 'display:none', 'id' => 'video_form'))?>
		<h3>Insérer une vidéo</h3>
		<?=$this->form->hidden('type', array('value' => 'video'))?>
		<?=$this->form->hidden('idProduct', array('value' => $post['_id']))?>
		<?=$this->form->field(array('video' => 'Lien vers la vidéo'), array('value' => 'http://'))?>
		<?=$this->form->field(array('video_width' => 'Largeur'), array('style' => 'width:120px'))?>
		<?=$this->form->field(array('video_height' => 'Hauteur'), array('style' => 'width:120px'))?>
		<!-- <?=$this->html->link('Annuler', '#', array('class'=>'cancel'))?>  -->
		<?=$this->form->submit('Ajouter')?>
	<?=$this->form->end()?>

	<?=$this->form->create(null, array('method' => 'post', 'style' => 'display:none', 'id' => 'image_form'))?>
		<h3>Insérer une image</h3>
		<?=$this->form->hidden('type', array('value' => 'image'))?>
		<?=$this->form->field(array('image' => 'Lien vers l\'image'), array('value' => 'http://'))?>
		<hr/>
		<h3>Miniature</h3>
		<?=$this->form->field(array('bolean_miniature' => ' Afficher une miniature de l\'image'), array('type' => 'checkbox'))?>
		<?=$this->form->field(array('image_width' => 'Largeur'), array('style' => 'width:120px'))?>
		<?=$this->form->field(array('image_height' => 'Hauteur'), array('style' => 'width:120px'))?>
		<?=$this->form->submit('Ajouter')?>
	<?=$this->form->end()?>

	<?=$this->form->create(null, array('method' => 'post', 'style' => 'display:none', 'id' => 'text_form'))?>
		<h3>Insérer du texte</h3>
		<?=$this->form->hidden('type', array('value' => 'text'))?>
		<?=$this->form->field(array('paragraphe' => 'Contenu'), array('type' => 'textarea', 'rows' => 10))?>
		<?=$this->form->submit('Ajouter')?>
	<?=$this->form->end()?>
	
	<?=$this->form->create(null, array('method' => 'post', 'style' => 'display:none', 'id' => 'list_form'))?>
		<h3>Insérer une liste</h3>
		<?=$this->form->hidden('type', array('value' => 'list'))?>
		<?=$this->form->field(array('liste' => '2 retours à la ligne = nouvel élément dans la liste / (ou utiliser le symbole [*])'), array('type' => 'textarea', 'rows' => 10))?>
		<?=$this->form->submit('Ajouter')?>
	<?=$this->form->end()?>
	
	
	<?=$this->form->create(null, array('method' => 'post', 'style' => 'display:none', 'id' => 'title_form'))?>
		<h3>Insérer un titre</h3>
		<?=$this->form->hidden('type', array('value' => 'title'))?>
		<?=$this->form->field(array('title' => 'Titre'))?>
		<?=$this->form->submit('Ajouter')?>
	<?=$this->form->end()?>

</div>