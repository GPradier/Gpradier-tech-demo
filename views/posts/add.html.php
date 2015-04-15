<div id="top" style=";border-bottom:1px solid #c4c2bc">
	<h2 style="margin:0 auto;padding:15px;width:980px"><?=$this->html->link('Liste de mes articles', array('controller' => 'posts', 'action' => 'seller'));?> &raquo; Ajouter un nouveau produit</h2>
</div>

<div id="content">
<style>
.hor-etapes {width:864px;margin:12px auto}
.hor-etapes .horL{float:left;background:url('/favstory/new/img/etape-left.png') center center no-repeat;width:12px;height:59px}
.hor-etapes .horRepeat{float:left;background:url('/favstory/new/img/etape-repeat.png') 0 0 repeat-x;}
.hor-etapes .horR{float:left;background:url('/favstory/new/img/etape-right.png') center center no-repeat;width:12px;height:59px}
.hor-etapes .horNode{float:left;background:url('/favstory/new/img/etape-node.png') center 0 no-repeat;width:160px;line-height:59px;text-align:center;font-size:24px;margin:0 25px;color:#ff930c;text-transform:uppercase}
.hor-etapes .horNode div {color:#fff;}
h3{text-transform:uppercase;text-align:center;margin:15px 0}
</style>
<div class="hor-etapes">
	<div class="horL">&nbsp;</div>
	<div class="horRepeat">
		<div class="horNode"><div>1</div>l'objet</div>
		<div class="horNode"><div>2</div>code barre</div>
		<div class="horNode"><div>3</div>son histoire</div>
		<div class="horNode"><div>4</div>bouton Fav It</div>
	</div>
	<div class="horR">&nbsp;</div>
</div>

<div class="clearfix"></div>
<?php
$this->form->config(array('templates' => array('error' => '<div class="error"{:options}>{:content}</div>')));
?>


<?=$this->form->create($post, array('url' => array('Posts::add'), 'type' => 'file')); ?>
	<h3>L'objet</h3>
    <?=$this->form->field(array('title' => 'Nom de l\'objet'));?>
    <?=$this->form->field(array('body' => 'Description de votre produit'), array('type' => 'textarea', 'rows' => 4));?>
	
	<div style="color:#777;font-size:24px;">Photos de l'objet</div>
	<ul class="pro clearfix"><li><label for="PostImage" onclick="$('#uploadImage').slideDown();$('#PostUpload').click();">+<br/>Ajouter une image</label></li></ul>
	
	<div id="uploadImage" style="display:none">
		<?=$this->form->field(array('upload' => ''), array('type' => 'file', 'style' => 'display:block'));?>
		
		<script>
		$('#PostUpload').on("change", function(){ $('form').submit(); });
		</script>
	</div>
	
	<?=$this->form->submit('Continuer', array('class' => 'btn2 right')); ?>
	<div style="clear:both"></div>
<?=$this->form->end();?>
	<hr/>
<?=$this->form->create($post); ?>
	<h3>Code barre de mon objet</h3>

	
	<div style="width:840px;margin:0 auto">
		<div class="hor-etapes clearfix">
			<div class="horL">&nbsp;</div>
			<div class="horRepeat">
				<div style="float:left;padding-top:40px;width:315px">Passez le code barre devant votre camera</div>
				<div class="horNode"><div>OU</div></div>
				<div style="float:left;padding-top:40px;width:315px">Saisissez directement le numéro du code barre</div>
			</div>
			<div class="horR">&nbsp;</div>
		</div>
		
		<div>
			<div style="float:left;width:340px;">
				<div style="border:1px solid #CCC;background:#EEE;width:100%;height:240px"></div>
			</div>
			<div style="float:right;width:340px;">
				<?=$this->form->field(array('codebarre' => ''));?>
				
				<?=$this->form->submit('Continuer', array('class' => 'btn2 right')); ?>
			</div>
		</div>
		
		<div style="clear:both"></div>
	</div>
<?=$this->form->end();?>

</div>