<link rel="stylesheet" href="http://fortawesome.github.com/Font-Awesome/assets/css/font-awesome.css">

<script src="http://code.jquery.com/ui/1.10.1/jquery-ui.js"></script>
<?=$this->html->script('dropzone');?>
<?=$this->html->style('dropzone');?>

<div id="top" style=";border-bottom:1px solid #c4c2bc">
	<h2 style="margin:0 auto;padding:15px;width:980px"><?=$this->html->link('Liste de mes articles', array('controller' => 'posts', 'action' => 'seller'));?> &raquo; Modification de &ldquo;<?=$post['title']?>&rdquo;</h2>
</div>

<div id="content">

<?php if($post['valid']) { ?>
<div style="text-align:center;width:940px;margin: auto;border: 3px dashed rgb(255, 170, 170);background: rgb(216, 255, 230);padding: 10px;text-align: center;border: 3px rgb(117, 221, 138) dashed;">Votre objet a été publié sur Favstory!</div>
<?php } else { ?>
<div style="text-align:center;width:940px;margin: auto;border: 3px dashed #FAA;background: #FFD8EE;padding: 10px;">Votre objet est en cours de création et a été sauvegardé. Remplissez tous les champs pour publier l'objet.</div>
<?php } ?>


<style>
.hor-etapes {width:864px;margin:12px auto}
.hor-etapes .horL{float:left;background:url('/favstory/new/img/etape-left.png') center center no-repeat;width:12px;height:59px}
.hor-etapes .horRepeat{float:left;background:url('/favstory/new/img/etape-repeat.png') 0 0 repeat-x;}
.hor-etapes .horR{float:left;background:url('/favstory/new/img/etape-right.png') center center no-repeat;width:12px;height:59px}
.hor-etapes .horNode{float:left;background:url('/favstory/new/img/etape-node.png') center 0 no-repeat;width:160px;line-height:59px;text-align:center;font-size:24px;margin:0 25px;color:#ff930c;text-transform:uppercase}
.hor-etapes .horNode div {color:#fff;}

.navigNode:hover span{color:#FFF;background:#ff930c;padding:0 5px}
h3{text-transform:uppercase;text-align:center;margin:15px 0}

.etape1 {margin-top:100px}

.formlike{
display: block;
clear: both;
background: #fafafa;
padding: 1em 2em 2em 2em;
border: 1px solid #e6e6e6;
margin-top: 0px;
margin-bottom: 12px;
-moz-box-shadow: 2px 2px 12px rgba(0,0,0,.15);
-webkit-box-shadow: 2px 2px 12px rgba(0,0,0,0.15);
box-shadow: 2px 2px 12px rgba(0,0,0,.15);
}

input, textarea{border-color:#AAA}
/*
input[type=submit] {background:#ffad48;color:#FFF;border:1px solid #d97f0f;border-radius:100px}
input[type=submit]:hover {color:#ffad48;background:#FFF}
*/

.imageProd{border:3px solid transparent;}
.imageProd:hover{border-color:#fc991f}

.imagesForProduct .active{border-color:#00a8e6;background:#00a8e6}
.active{border-color:#00a8e6;background:#00a8e6}

/* only on Editing */
#sortable {margin:0;padding:0}
#sortable li {list-style:none}
.placeholder {border: 5px dotted rgb(211,199,181); visibility: visible !important; height: 300px !important;}
.placeholder * { visibility: hidden; }

article{position:relative}
article.canmove:hover{background:rgba(211,199,181,0.5)}
.canmove{cursor:move}
article:hover > .storyActions{display:block}
.storyActions{display:none;position:absolute !important;top:0;}
.modify, .delete, .tip{float:left;background:#FFF;padding:10px 15px;margin-right:8px;border:1px solid rgb(211,199,181);border-radius:8px}

.storyActions a:hover {background:#524b3e;color:#fff}

article:nth-child (odd) .storyActions {left:0;}
article:nth-child (even) .storyActions {right:0}

.stepValid {position:absolute;background: url('/favstory/new/img/spritemap.png') 169px 213px;width: 50px;height: 40px;margin-left: 51px;margin-top: 9px}
.stepError {position:absolute;background: url('/favstory/new/img/spritemap.png') 169px 253px;width: 50px;height: 40px;margin-left: 51px;margin-top: 9px}


/**/
.pro a {border:0;font-size:16px}
.pro a:hover {background:#ff930c;text-shadow:1px 1px #d07300}
.pro a i {margin-bottom:10px}
</style>
<div class="hor-etapes">
	<div class="horL">&nbsp;</div>
	<div class="horRepeat">
		<a href="?step=etape1" class="navigNode"><div class="horNode"><div class="stepValid"></div><div>1</div><span>l'objet</span></div></a>
		<a href="?step=etape2" class="navigNode"><div class="horNode"><div>2</div><span>code barre</span></div></a>
		<a href="?step=etape3" class="navigNode"><div class="horNode"><div>3</div><span>son histoire</span></div></a>
		<a href="?step=etape4" class="navigNode"><div class="horNode"><div class="stepError"></div><div>4</div><span>bouton Fav It</span></div></a>
	</div>
	<div class="horR">&nbsp;</div>
</div>

<div class="clearfix"></div>
<?php
$this->form->config(array('templates' => array('error' => '<div class="error"{:options}>{:content}</div>')));
?>

<script>
$(function() {	
	$('.moveLeft').click(function(){
		$('.contenairListImgs').hide();
		$('.contenairImg').fadeIn();

		$(this).css('visibility', 'hidden');
		$('.moveRight').css('visibility', 'visible');
	});
	$('.moveRight').click(function(){
		$('.contenairListImgs').fadeIn();
		$('.contenairImg').hide();

		$(this).css('visibility', 'hidden');
		$('.moveLeft').css('visibility', 'visible');
	});
	
	
});
</script>

<style>
.etape1, .etape2, .etape3, .dev{display:none}
.<?=$currentStep?> {display:block}
<?php if(!$currentStep) { echo '.etape1{display:block}';} ?>
</style>

<div class="popup" style="position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(32,32,32,0.25);z-index:99;display:none">
<style>.preview{margin:2px !important}</style>
<div id="carousel" style="width:720px;margin:15px auto">
	<div style="background:url(/favstory/new/img/i_56.png);width:713px;height:470px;float:left">
		<div class="contenairImg" style="width:640px;height:402px;overflow:hidden;margin-top:34px;margin-left:35px;overflow:auto">
			<?=$this->form->create(null, array('url' => array('controller' => 'Posts', 'action' => 'edit', 'args' => $post->_id), 'class' => 'dropzone', 'type' => 'file', 'id' => 'my-awesome-dropzone', 'style' => 'min-height:150px;padding:5px;border-color:#acacac'))?>
			<?=$this->form->end()?>
			<script>
			Dropzone.options.myAwesomeDropzone = {
			maxFilesize: 5,
			thumbnailWidth: 150,
			thumbnailHeight: 150,
			paramName: 'upload',
			accept: function(file, done) {done();},
			error: function(file, message){alert(message)}, 
			complete: function(file){},
			//sending : function(file, obj) {JSON.stringify(obj)}
			};
			</script>
			
			<div class="contenairListImgs">
				<?php foreach($docs as $file) { ?>
				<div class="preview" style="margin:2px;padding:2px">  <div class="details">   <div class="filename" style="line-height:20px"><span><?=$file->realname?></span><div class="size" style="bottom:0"><strong><?php echo (int) ($file->size/1000)?></strong> KB</div>
				<div class="delete" style="top:0;right:-10px;padding:1px 5px;position:absolute"><strong><?=$this->html->link('X', array('controller' => 'Upload', 'action' => 'delete', 'args' => $file->_id),array('class' => 'deleteImage', 'style'=>'color:#F00'))?></strong></div>
				</div>
					<?=$this->html->image(array('Upload::view', 'args' => $file->_id), array('style' => 'padding:5px;background:#ebebeb'))?>
				</div></div>
				<?php } ?>
			</div>
			
		</div>
	</div>
	<div style="clear:both"></div>
</div>


</div>


<div class="etape1" style="position:relative;padding-bottom:60px">
<?=$this->form->create($post, array('url' => array('controller' => 'Posts', 'action' => 'edit', 'args' => array($post->_id, '?step=etape2')), 'id' => 'withUpload')); ?>
<?=$this->form->hidden('id');?>
	<h3>L'objet</h3>
    <?=$this->form->field(array('title' => 'Nom de l\'objet'), array('disabled' => ($post['valid'] ? 'disabled' : false)));?>
    <?=$this->form->field(array('body' => 'Description de votre produit'), array('type' => 'textarea', 'rows' => 4, 'disabled' => ($post['valid'] ? 'disabled' : false)));?>
	
	<?=$this->form->field(array('urlshop' => 'URL du point de vente du produit'));?>
	
	<?php /*
	<div style="color:#777;font-size:24px;">Photos de l'objet</div>
	<ul class="pro clearfix">
		<li><label for="PostImage" onclick="$('#uploadImage').slideDown();$('#PostUpload').click();">+<br/>Ajouter une image</label></li>
		<?php
		foreach($docs as $file) {
			echo '<li>'. $this->html->image(array('Upload::view', 'args' => $file->_id), array('class' => 'imageProd', 'style' => 'float:left')) .'</li>';
		}
		?>
	</ul>
	*/ ?>
	<?=$this->form->submit('Continuer', array('class' => 'btn2 right', 'style' => 'position:absolute;right:30px;bottom:0')); ?>
	<div style="clear:both"></div>
<?=$this->form->end();?>
</div>

<div class="etape2">
<?=$this->form->create($post, array('url' => array('controller' => 'Posts', 'action' => 'edit', 'args' => array($post->_id, '?step=etape3')))); ?>
<?=$this->form->hidden('id');?>
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

<div class="etape3">
<script type="text/javascript">
//$(function() {
$(document).ready(function() {
	/*
	 * Utilisable pour vidéo
	$('#add a').click(function(event){
		event.preventDefault();
		$('.pro').hide();
		var kk = $(this).attr('href');
		if($(kk).length > 0) {
			$(kk).fadeIn();
		}
		return false;
	});
	*/
	
	$('.newImage').click(function(event){
		$('.popup').fadeIn();
	});
	
	/* * Kill popup when click out of window */
	$('.popup').click(function(event){
		if(this != event.target) return;
		$(this).fadeOut();
	});
	/* * Special link "add image" * */
		$('.preview').click(function(e) {// On click in image preview for Image type
			//if(this != event.target) return;
			//if(this == $('.deleteImage')) return;
			
			e.preventDefault();
			var image = $(this).find('img');
			$('#hiddenLink').val(image.attr("src"));
			$('input[name=type]').val('image');
			
			$(".preview").removeClass("active");
			$(this).addClass("active");
			// Finish, fadeout + add preview
			$('.popup').fadeOut();
			$('.previewConfirm').empty().append(image);
		});
		/** Futur Add : Who call me, add val in target output insteand of generic **/
		/* *End */
	
	
	
	$('.cancel').click(function(event){
		event.preventDefault();
		$('.pro').show();
		$('#formVideo').hide();
		$('#formImage').hide();
	});
	
	$('.imagesForProduct .preview').click(function(e) {// On click in image preview for Image type
		e.preventDefault();
		$('#hiddenLink').val($(this).find('img').attr("src"));
		$('input[name=type]').val('image');
		
		$(".imagesForProduct .preview").removeClass("active");
		$(this).addClass("active");
	});
	
	$('#formVideo input').change(function() {// On input change for Video type
		$('#hiddenLink').val($(this).val());
		$('input[name=type]').val('video');
	});
	
	$('#seeAllImages').click(function(e) {
		e.preventDefault();
		if($('#allImages').css('display') == 'none') {
			$('#seeAllImages').html('&laquo; Cacher les images de ma collection');
			$('#allImages').slideDown();
		} else {
			$('#allImages').slideUp();
			$('#seeAllImages').html('Voir toutes les images de ma collection &raquo;');
		}
	});
	
	$('.ytVid a').click(function(e) {
		e.preventDefault();
		var link = $(this).attr('href');
		var idY = link.split("-");
		$(link).find('.phYoutubePict').hide();
		$(link).find('.phYoutubeVideo').html('<iframe width="385" height="217" src="http://www.youtube.com/embed/'+idY[1]+'?autoplay=1" frameborder="0" allowfullscreen></iframe>');
	});
	
	var idsInOrder;
	$( "#sortable" ).sortable({
		placeholder: "placeholder", 
		cancel: '#newstory, :input,button, input, textarea',
		update: function( event, ui ) {
			idsInOrder = $("#sortable").sortable("toArray");
			var url = $('#urlUpdateOrder').attr('href');
			$.post(url, {changeorder: idsInOrder}).done(function(data) { 
				//done;
			});
		}
    });
    $( "#sortable" ).disableSelection();
	
	$(document).on("click", ".editStory", function(e){
	//$('.editStory').click(function(e){
		e.preventDefault();
		var link = $(this).attr('href');
		var article = $(this).parent().parent();
		article.find('.showStory').hide();
		article.find('.formStory').show();
	});
	
	$(document).on("click", ".deleteStory", function(e){
	//$('.deleteStory').on('click', function(e){
		e.preventDefault();
		var article = $(this).parent().parent();
		var link = $(this).attr('href');
		
		// hide asynchrone
		article.hide('slow', function() {article.remove()});
		$.get(link).done(function(data) {
			//article.hide('slow');
		});
	});

	$(document).on("submit", ".editForm form", function(e){
	//$('.editForm form').submit(function(e){
		e.preventDefault();
		var article = $(this).parent().parent(),
		form = $(this),
		title = form.find('input[name="title"]').val(),
		description = form.find('textarea[name="description"]').val();
		
		$.post(form.attr('action'), form.serialize()).done(function(data) {
			article.find('.showStory').show();
			article.find('.editForm').hide();

			article.find('.title').empty().append(title);
			article.find('.description').empty().append(description);
		});
	});
	
	$(document).on("click", ".deleteImage", function(e){
		e.preventDefault();
		var link = $(this).attr('href');
		var preview = $(this).closest(".preview");
		
		preview.hide('slow', function() {preview.remove()});
		$.get(link).done(function(data) {
			//article.hide('slow');
		});
		
	});
	
	
	
	
	
	$('.fullzoom').click(function(e) {
		e.preventDefault();
		var image = $(this).find('img');
		
		$('body').append('<div id="zoomWindow" style="position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(35,35,35,0.35);text-align:center">\
		<div style="position:relative;display:inline-block"><img src="'+image.attr('src')+'" style="margin-top:25px;max-width:90%;max-height:90%" /><div class="stepError" style="margin:0;top:5px;right:-3px"></div></div></div>');
		
		$('#zoomWindow').on('click', function() {$(this).remove();});
	});
	
	$('#formNewStory').submit(function(e){
		e.preventDefault();
		
		form = $(this);
		$.post(form.attr('action'), form.serialize()).done(function(data) {
			$('article.canmove').remove();
			$('#sortable').append($(data).find('article.canmove'));

			$('html, body').animate({  
				scrollTop:$('article:last').offset().top
			}, 'slow');
			
		});
	});
});
</script>

<br/><br/><br/><br/>
<h3>Prévisualisation de l'histoire</h3>
<?php /*/ 	<li class="ui-state-default <?=$story['type']?>" id="sort_<?=$story['_id']?>"> */?>
<div id="timeline" style="position:relative;width:100%">
	<div id="timelineTop" style="background:url('/favstory/new/img/timeline_76.png') no-repeat 50%;height:14px">&nbsp;</div>
	<div id="timelineMid" style="background:url(/favstory/new/img/timeline_78.png) repeat-y 50%">
		<?php $i=0;?>
		<ul id="sortable">
		<!--addNewStory-->
			<article id="newstory">
				<div class="image">
					<div class="imageContain" style="text-align:center;margin:10px 10px">
						<ul class="pro clearfix" id="add" style="display:block;width:400px;">
							<li><a href="#formImage" class="newImage"><i class="icon-picture icon-4x"></i><br/>Ajouter une image</a></li>
							<li><a href="#formVideo"><i class="icon-film icon-4x"></i><br/>Ajouter une vidéo</a></li>
							<li style="width:80%;margin-top:15px;max-height:170px" class="previewConfirm"></li>
						</ul>
						<div id="formVideo" style="display:none;width:400px;margin:50px 10px;">
							<?=$this->form->field(array('link' => 'Lien vers la vidéo (compatible Youtube)'), array('value' => 'http://'))?>
							
							<a href="#cancel" class="cancel">Annuler</a>
						</div>
						
						<div id="formImage" style="display:none">
						<div class="imagesForProduct" style="overflow:auto;width:430px;height:238px">
							<?php foreach($docs as $file) { ?>
							<div class="preview processing image-preview success" style="margin:2px;padding:2px">  <div class="details">   <div class="filename"><span><?=$file->realname?></span><div class="size" style="bottom:0"><strong><?php echo (int) ($file->size/1000)?></strong> KB</div></div>
							
							<?=$this->html->image(array('Upload::view', 'args' => $file->_id), array('style' => 'padding:5px'))?>
							
							</div>  <div class="progress"><span class="upload" style="width: 100%;"></span></div></div>
							<?php } ?>
						</div>
						
						<a href="#cancel" class="cancel">Annuler</a>
			
						</div>
					</div>
				</div>
					<div class="dateTimeline">
						<span style="font-size:40px">+</size>
					</div>
				
				<div class="text">
					<div class="formStory">
						<?=$this->form->create(null, array('url' => array('controller' => 'posts', 'action' => 'story', 'args' => array($post['_id'], '?step=etape3')), 'method' => 'post', 'id' => 'formNewStory'))?>
						<?=$this->form->hidden('idProduct', array('value' => $post['_id']))?>
						<?=$this->form->hidden('type', array('value' => 'undefined'))?>
						<?=$this->form->field('title')?>
						<?=$this->form->field('description', array('type' => 'textarea', 'rows' => 4, 'style' => 'max-width:380px'))?>
						<?=$this->form->hidden('link', array('value' => '', 'id' => 'hiddenLink'))?>
						<?=$this->form->submit('Ajouter l\'histoire', array('class' => 'btn2', 'style' => 'margin:0'))?>
						<?=$this->form->end();?>
					</div>
				</div>
				<div style="clear:both"></div>
			</article>
		<!--/addNewStory-->
		<?php foreach($storys as $story): ?>
			<?php $i++;?>
			<article id="<?=$story['_id']?>" class="canmove">
			<div class="storyActions">
				<a href="#" class="tip" title="Glissez-déposez les éléments de l'histoire avec la souris">?</a>
				<?=$this->html->link('Modifier', array('action' => 'editStory', 'args' => $story['_id']), array('class' => 'modify editStory'))?>
				<?=$this->html->link('Supprimer', array('action' => 'deleteStory', 'args' => $story['_id']), array('class' => 'delete deleteStory'))?>
			</div>

			<?php if($story['type'] == 'image') { ?>
			<div class="image">
				<div class="story-arrow"></div>
				<div class="story-top"></div>
				<div class="story-center">
					<div class="imageContain" style="text-align:center">
						<a href="<?=$story['link']?>" target="_blank" class="fullzoom"><img src="<?=$story['link']?>" alt="" /></a>
					</div>
				</div>
				<div class="story-bot"></div>
			</div>
			<?php }?>

			<?php if($story['type'] == 'video') { ?>
			<div class="video">
					<div class="videoContain" style="text-align:center">
					<?=$this->html->image('video_hover.png', array('class' => 'videoHover'));?>
						<?php
							$pattern = '/.*(?:youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=)([^#\&\?]*).*/';
							$subject = $story['link'];
							preg_match($pattern, $subject, $matches);

							if($matches && strlen($matches[1]) == 11) {
								$videoyoutube = $matches[1];
						?>
							<div class="ytVid" id="youtube-<?=$videoyoutube?>">
							<div class="phYoutubePict">
								<a href="#youtube-<?=$videoyoutube?>"><img src="http://img.youtube.com/vi/<?=$videoyoutube?>/mqdefault.jpg" /></a>
							</div>
							<div class="phYoutubeVideo"></div>
							</div>
						<?php } ?>
					</div>
			</div>
			<?php }?>

			<div class="dateTimeline">
				<?=$i?>
			</div>
			
			<div class="text">
				<div class="textContain showStory">
					<div class="title"><?=$story['title']?></div>
					<div class="description"><?=nl2br($story['description'])?></div>
				</div>
				<div class="formStory editForm" style="display:none">
					<?=$this->form->create(null, array('url' => array('action' => 'editStory', 'args' => $story['_id'])));?>
					<?=$this->form->field('title', array('value' => $story['title'], 'id' => 'title'.$story['_id']))?>
					<?=$this->form->field('description', array('type' => 'textarea', 'value' => $story['description'], 'id' => 'desc'.$story['_id']))?>
					<?=$this->form->hidden('order', array('value' => $story['order']));?>
					<?=$this->form->submit('Mettre à jour', array('class' => 'btn2'))?>
					<?=$this->form->end();?>
				</div>
			</div>
			<div style="clear:both"></div>
			</article>
		<?php endforeach; ?>
		</ul>
		<?=$this->html->link('', array('action' => 'orderStorys', 'args' => $post['_id']), array('id' => 'urlUpdateOrder'));?>
		
	<div style="clear:both"></div>
	</div>
	<div id="timelineBot" style="background:url('/favstory/new/img/timeline_103.png') no-repeat 50%;height:13px">&nbsp;</div>
</div>

	<div style="padding-top:50px;text-align:center">
	<?=$this->html->link('Publier mon produit', array('action' => 'publish', 'args' => $post['_id']), array('class' => 'btn2')); ?>
	</div>

</div>

</div>