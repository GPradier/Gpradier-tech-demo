<script type="text/javascript">
function youtube_parser(url){
	var regExp = /.*(?:youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=)([^#\&\?]*).*/;
	var match = url.match(regExp);
	if (match&&match[1].length==11){
		return match[1];
		//alert( match[1]);
	}else{
		alert("Url incorrecta");
	}
}
</script>

<style>
.delete{
background: white;
padding: 5px;
position: absolute;
margin-left: 500px;
border: 1px solid #CCC;
}
</style>
		
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

<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.0/themes/base/jquery-ui.css" />

<?php $this->scripts('<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>');?>
<?php /*$this->scripts('<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.7/jquery-ui.min.js"></script>');*/?>

<?php $this->scripts('<script src="http://code.jquery.com/ui/1.10.0/jquery-ui.js"></script>');?>


  <style>
  #sortable { list-style-type: none; margin: 0; padding: 0; width: 590px; }
  #sortable li { margin: 0 5px 5px 5px; padding: 15px;max-height:300px;overflow:hidden}
  #sortable li img{max-width:560px;max-height:180px}
  #sortable li.title{font-weight:bold;font-size:1.8em}
  </style>
  <script>
  $(function() {
    $( "#sortable" ).sortable({

	  update: function() {
		var serial = $("#sortable").sortable('toArray');
		$("#log").html(serial.toString());
		
		for(var i= 0; i < serial.length; i++)
		{	
			id = serial[i].split("sort_");
			//alert(id[1] + ' => ' + i);
			//
			// UPDATE =>
			//		_id = id[1]
			//		order = i
		}
	  }
    });
    $( "#sortable" ).disableSelection();
  });
  </script>
  
<h1>Gestion de l'affichage des éléments d'histoire</h1>

<?php if(!$storys) {
	echo '<h2>Aucun élément d`histoire disponible. Créer des éléments d`histoire pour pouvoir gérer l`affichage.</h2>';
}?>
<ul id="sortable">
<?php foreach($storys as $story): ?>
	<li class="ui-state-default <?=$story['type']?>" id="sort_<?=$story['_id']?>">
	<?=$this->html->link('Supprimer l\'élément', array('controller' => 'posts', 'action' => 'deleteStory', 'args' => $story['_id']), array('class' => 'delete'));?>
		
	<?php if($story['type'] == 'text') { ?>
		<p style="max-height:200px;overflow:hidden"><?=nl2br($story['paragraphe'])?></p>
	<?php }?>
	
	<?php if($story['type'] == 'image') {
		if($story['bolean_miniature']) {?>
			<a href="<?=$story['image']?>" target="_blank">
				<?=$this->html->image($story['image'], array('width' => $story['image_width'], 'height' => $story['image_height']));?>
			</a>
		<?php } else { ?>
			<?=$this->html->image($story['image']);?>
		<?php } ?>
	<?php }?>
	
	<?php if($story['type'] == 'title') { ?>
		<h3 style="margin:0"><?=$story['title']?></h3>
	<?php }?>
	
	<?php if($story['type'] == 'list') {
		$lists = explode("\n", $story['liste']);
		echo '<ul style="list-style-type: circle !important">';
		foreach($lists as $list) {
			$list = trim($list);
			if(!empty($list)) {
				echo '<li style="margin:0;padding:0;">* '. $list .'</li>';
			}
		}
		echo '</ul>';
	}?>
	
	<?php if($story['type'] == 'video') {
		// REGEX VERSION PHP
		$pattern = '/.*(?:youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=)([^#\&\?]*).*/';
		$subject = $story['video'];
		preg_match($pattern, $subject, $matches);
		
		if($matches && strlen($matches[1]) == 11) {
			$videoyoutube = $matches[1];
			echo '<img src="http://img.youtube.com/vi/'. $videoyoutube .'/0.jpg" />';
		}

		/*
		// VERSION JAVASCRIPT
		<script>document.write('<img src="http://img.youtube.com/vi/' + youtube_parser('<?=$story['video'];?>') + '/0.jpg" />');</script>
		*/

		/*
		// AFFICHAGE DE LA VIDEO
		<iframe 
			width="<?=($story['video_width']) ? $story['video_width'] : 560;?>"
			height="<?=($story['video_height']) ? $story['video_height'] : 315;?>" src="<?=$story['video']?>" frameborder="0" allowfullscreen
		></iframe>
		*/
		?>
		
	<?php }?>
	</li>
<?php endforeach; ?>
</ul>

<div id="log" style="width:200px;display:none"></div>