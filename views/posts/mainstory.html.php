<script src="http://code.jquery.com/ui/1.10.1/jquery-ui.js"></script>
<?=$this->html->script('dropzone');?>
<?=$this->html->style('dropzone');?>

<div id="top" style=";border-bottom:1px solid #c4c2bc">
	<h2 style="margin:0 auto;padding:15px;width:980px"><?=$this->html->link('Liste de mes articles', array('controller' => 'posts', 'action' => 'seller'));?> &raquo; Création de ma &ldquo; Story &rdquo;</h2>
</div>

<div id="content">

<style>

.navigNode:hover span{color:#FFF;background:#ff930c;padding:0 5px}
h3{text-transform:uppercase;text-align:center;margin:15px 0}

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

</style>

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

<div id="carousel" style="width:920px;margin:30px auto 15px">
	<div style="background:url(/favstory/new/img/i_59.png);width:60px;height:60px;float:left;margin-top:220px;margin-right:40px;visibility:hidden" class="moveLeft">&nbsp;</div>
	<div style="background:url(/favstory/new/img/i_56.png);width:713px;height:470px;float:left">
		<div class="contenairImg" style="width:640px;height:390px;line-height:390px;overflow:hidden;margin-top:40px;margin-left:35px;">
			<?=$this->form->create(null, array('url' => array('controller' => 'Upload', 'action' => 'index'), 'class' => 'dropzone', 'type' => 'file', 'id' => 'my-awesome-dropzone'))?>
			<?=$this->form->end()?>
			<script>
			Dropzone.options.myAwesomeDropzone = {
			maxFilesize: 5,
			thumbnailWidth: 150,
			thumbnailHeight: 150,
			paramName: 'upload',
			accept: function(file, done) {done();},
			error: function(file, message){alert(message)}, 
			complete: function(file){
				$('form').each(function(){$(this).attr('action')});
			},
			//sending : function(file, obj) {JSON.stringify(obj)}
			};
			</script>
		</div>
		
		<div class="contenairListImgs" style="display:none;width:640px;height:390px;overflow:auto;margin-top:40px;margin-left:35px;">
			<?php foreach($docs as $file) { ?>
			<div class="preview" style="margin:2px;padding:2px">  <div class="details">   <div class="filename" style="line-height:20px"><span><?=$file->realname?></span><div class="size" style="bottom:0"><strong><?php echo (int) ($file->size/1000)?></strong> KB</div></div>
				<?=$this->html->image(array('Upload::view', 'args' => $file->_id), array('style' => 'padding:5px;background:#ebebeb'))?>
			</div></div>
			<?php } ?>
		</div>
	</div>
	<div style="background:url(/favstory/new/img/i_62.png);width:60px;height:60px;float:left;margin-top:220px;margin-left:40px" class="moveRight">&nbsp;</div>
	<div style="clear:both"></div>
</div>

<div class="etape3">
<script type="text/javascript">
$(function() {
	$('#add a').click(function(event){
		event.preventDefault();
//		$('#formVideo').hide();
//		$('#formImage').hide();
		$('.pro').hide();
		var kk = $(this).attr('href');
		if($(kk).length > 0) {
			$(kk).fadeIn();
		}
		return false;
	});
	
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
	
	$('.editStory').click(function(e){
		e.preventDefault();
		var link = $(this).attr('href');
		var article = $(this).parent().parent();
		article.find('.showStory').hide();
		article.find('.formStory').show();
	});
	
	$('.deleteStory').click(function(e){
		e.preventDefault();
		var article = $(this).parent().parent();
		var link = $(this).attr('href');
		
		// hide asynchrone
		article.hide('slow', function() {article.remove()});
		$.get(link).done(function(data) {
			//article.hide('slow');
		});
	});

	$('.editForm form').submit(function(e){
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
	
	$('.fullzoom').click(function(e) {
		e.preventDefault();
		var image = $(this).find('img');
		
		$('body').append('<div id="zoomWindow" style="position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(35,35,35,0.35);text-align:center">\
		<div style="position:relative;display:inline-block"><img src="'+image.attr('src')+'" style="margin-top:25px;max-width:90%;max-height:90%" /><div class="stepError" style="margin:0;top:5px;right:-3px"></div></div></div>');
		
		$('#zoomWindow').on('click', function() {$(this).remove();});
	});
});
</script>

<h3>Création de votre histoire</h3>
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
						<ul class="pro clearfix" id="add" style="display:block;width:400px;margin-top:50px;">
							<li><a href="#formImage">+<br/>Ajouter une image</a></li>
							<li><a href="#formVideo">+<br/>Ajouter une vidéo</a></li>
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
						<?=$this->form->create(null, array('url' => array('controller' => 'posts', 'action' => 'story', 'args' => array($post['_id'], '?step=etape3')), 'method' => 'post'), array('id' => 'formNewStory'))?>
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
			<div class="image">
				<div class="imageContain"   style="width:405px;height:240px">
					<?php if($story['type'] == 'image') { ?>
						<a href="<?=$story['link']?>" target="_blank" class="fullzoom">
							<img src="<?=$story['link']?>" alt="" style="min-width:100%;max-width:100%;max-height:400%;max-width:100% !important" />
						</a>
					<?php }?>
					
					<?php if($story['type'] == 'video') {
						$pattern = '/.*(?:youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=)([^#\&\?]*).*/';
						$subject = $story['link'];
						preg_match($pattern, $subject, $matches);

						if($matches && strlen($matches[1]) == 11) {
							$videoyoutube = $matches[1];
							//echo '<div class="phYoutubePict"><a href="http://www.youtube.com/watch?v='.$videoyoutube.'" target="_blank"><img src="http://img.youtube.com/vi/'. $videoyoutube .'/0.jpg" /></a></div>';
							
							echo '<div class="ytVid" id="youtube-'.$videoyoutube.'">';
							echo '<div class="phYoutubePict"><a href="#youtube-'.$videoyoutube.'"><img src="http://img.youtube.com/vi/'. $videoyoutube .'/0.jpg" /></a></div>';
							
							echo '<div class="phYoutubeVideo"></div>';
							echo '</div>';
						}
					} ?>
				</div>
			</div>
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
	<?=$this->html->link('Mettre à jour l\'histoire', array('action' => 'publish', 'args' => $post['_id']), array('class' => 'btn2')); ?>
	</div>

</div>

</div>