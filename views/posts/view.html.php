<?php
$fullname = $post['user']['firstname'] .' '. $post['user']['lastname'];
$location = ($post['user']['location'] ? $post['user']['location'] : 'France');
$avatar = ($post['user']['avatar'] ? $post['user']['avatar'] : '/favstory/new/img/avatar-user.png');
?>

<style>
#productMenu ul, #productMenuSmall ul{list-style:none}
.authorFav a:hover{text-decoration:underline}
</style>

<div id="top">
	<div class="center">
		<h1><?=$post['title']?></h1>
		<div id="author">
		<div class="authorName">
			<?=$this->html->link('
				<b>'. $fullname .'</b>
				<span>'. $location .'</span>
			', 'posts/boutique/'.$post['user']['_id'], array('escape' => false));
			?>
		</div>
		<div class="authorAvatar" style="float:left">
			<img src="<?=$avatar?>" style="width:98px;height:98px;border-radius:100px;border:10px solid #F2F2F2" />
		</div>
		<div class="authorFav">
			<?=$this->html->link('<b class="red">Ajouter</b><span>à mes favoris</span>', array('controller' => 'favoris', 'action' => 'add', 'args' => $post['_id']), array('id' => 'btnFav', 'escape' => false))?>
		</div>
		</div>
	</div>
	<div style="clear:both"></div>
</div>

<div id="productMenu">
	<ul>
		<li><a href="#histoire">Histoire</a></li>
		<li><a href="#description">Description</a></li>
		<li><a href="#voshistoires">Vos histoires</a></li>
		<li><a href="#Classer">Classer</a></li>
		<li><a href="#" class="tip" title="Partager cet objet sur Twitter"><img src="/favstory/new/img/i_42.png" alt="" title="" /></a></li>
		<li><a href="#" class="tip" title="Partager cet objet sur Facebook"><img src="/favstory/new/img/i_44.png" alt="" title="" /></a></li>
		<li><a href="#" class="tip" title="Partager cet objet sur Pinterest"><img src="/favstory/new/img/i_46.png" alt="" title="" /></a></li>
		<li style="padding-top:4px;border:0"><img src="/favstory/new/img/i_30.png" style="position:absolute;margin-left:80px;margin-top:-65px"/><img src="/favstory/new/img/i_39.png" /></li>
	</ul>
	
	<div style="clear:both"></div>
</div>

<div id="productMenuSmall">
	<ul>
		<li style="border-left:0">
		<a href="#" class="authorblock">
			<div class="authorAvatar" style="float:left">
				<img src="<?=$avatar?>" style="width:40px"/>
			</div>
			<div class="authorName" style="background:url('/favstory/new/img/i_22.png') no-repeat;float:left;width:135px;text-align:right;padding-right:15px;">
				<b style="color:#46423a;text-transform:uppercase;font:14px 'Roboto Condensed';font-weight:bold;display:block"><?=$fullname?></b>
				<span style="color:#7e7e7e;text-transform:uppercase;font:12px 'Roboto Condensed';"><?=$location?></span>
			</div>
		</a>
		</li>
		<li><a href="#histoire">Histoire</a></li>
		<li><a href="#description">Description</a></li>
		<li><a href="#voshistoires">Vos histoires</a></li>
		<li><a href="#Classer">Classer</a></li>
		<li><a href="#" class="tip" title="Partager cet objet sur Twitter"><img src="/favstory/new/img/i_42.png" alt="" title="" /></a></li>
		<li><a href="#" class="tip" title="Partager cet objet sur Facebook"><img src="/favstory/new/img/i_44.png" alt="" title="" /></a></li>
		<li><a href="#" class="tip" title="Partager cet objet sur Pinterest"><img src="/favstory/new/img/i_46.png" alt="" title="" /></a></li>
		<li style="padding-top:4px;border:0"><img src="/favstory/new/img/i_39.png" /></li>
	</ul>
	
	<div style="clear:both"></div>
</div>

<div id="content">
	<div id="carousel" style="width:920px;margin:15px auto">
		<div style="background:url(/favstory/new/img/i_59.png);width:60px;height:60px;float:left;margin-top:220px;margin-right:40px" class="moveLeft">&nbsp;</div>
		<div style="background:url(/favstory/new/img/i_56.png);width:713px;height:470px;float:left">
			<div class="contenairImg" style="width:640px;height:390px;line-height:390px;overflow:hidden;margin-top:40px;margin-left:35px;text-align:center">
			
				<div class="zoomIn" style="position:relative;width:100%;height:100%;">
					<?php
						//echo $this->html->image(array('Upload::view', 'args' => $docs{0}->_id), array('class' => 'topimage', 'style' => 'max-width:640px;max-height:390px;vertical-align:middle;'));
					?>
					<?=$this->html->image(array('Upload::view', 'args' => $docs{0}->_id), array('class' => 'topimage', 'style' => 'width:100%'));?>

					<div id="loadImages" style="display:none">
					<?php
					foreach($docs as $file) {
						echo $this->html->image(array('Upload::view', 'args' => $file->_id));
					}
					?>
					</div>
				</div>
			</div>
		</div>
		<div style="background:url(/favstory/new/img/i_62.png);width:60px;height:60px;float:left;margin-top:220px;margin-left:40px" class="moveRight">&nbsp;</div>
	</div>
	
	<div style="clear:both;width:900px;text-align:center;margin:10px auto">
		<hr class="left" />
		<img src="/favstory/new/img/i_73.png" style="position:absolute;z-index:10;" id="moveIcon" />
		<img src="/favstory/new/img/i_73.png" style="visibility:hidden" />
		<hr class="right" />
	</div>
	
	
	<div id="timeline" style="position:relative;width:100%">
		<div id="timelineTop" style="background:url('/favstory/new/img/timeline_76.png') no-repeat 50%;height:14px">&nbsp;</div>
		<div id="timelineMid" style="background:url(/favstory/new/img/timeline_78.png) repeat-y 50%">
			<?php $i=0;?>
			<?php foreach($storys as $story): ?>
			<?php $i++;?>
			<article>
			<?php if($story['type'] == 'image') { ?>
			<div class="image">
				<div class="story-arrow"></div>
				<div class="story-top"></div>
				<div class="story-center">
					<div class="imageContain" style="text-align:center">
						<a href="<?=$story['link']?>" target="_blank"><img src="<?=$story['link']?>" alt="" /></a>
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
							<div class="phYoutubePict"><a href="#youtube-<?=$videoyoutube?>"><img src="http://img.youtube.com/vi/<?=$videoyoutube?>/mqdefault.jpg" /></a></div>
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
				<div class="textContain">
					<div class="title"><?=$story['title']?></div>
					<?=nl2br($story['description'])?>
				</div>
			</div>
			<div style="clear:both"></div>
			</article>
			<?php endforeach; ?>
			
			<div style="clear:both"></div>
		</div>
		<div id="timelineBot" style="background:url('/favstory/new/img/timeline_103.png') no-repeat 50%;height:13px">&nbsp;</div>
	</div>
</div>

<style>
.comments ul {padding:0;margin:0}

.comments li {float: left;width: 200px;height: 297px;background: #fff;margin: 0 20px 35px 0;box-shadow: 0px 0px 5px 1px rgba(0, 0, 0, 0.2);padding: 10px;overflow: hidden;border-bottom: 4px solid transparent;}

.author .avatar{width:40px;height:40px;float:left;display:block;margin:0 15px 0 2px}
.author p {float:left;margin:0;padding:4px 0;color:#66625a;text-transform:uppercase;font-size:12px;font-family:'Roboto Condensed', Arial, sans-serif;}
.author b {color:#46423a}

ul.products .imgcont {width:200px;height:200px;overflow:hidden}
ul.products .imgcont img{min-height:200px;min-width:200px;width:100%;}
</style>

<div class="comments" style="width:900px;margin:50px auto 0">
<?php if($comments) { ?>
<ul>
<?php foreach ($comments as $comm) { ?>
<?php $avatar = ($comm['user']['avatar'] ? $comm['user']['avatar'] : 'i_19.png'); ?>
<li style="height:200px">
	<div class="author clearfix">
		<?=$this->html->image($avatar, array('class' => 'avatar avatarSmall'))?>
		<p>
			<b style="display:block"><?=$comm['user']['firstname'] .' '. $comm['user']['lastname']?></b>
			<?=date('D, d M Y H:i:s', $comm['created']); ?>
		</p>
	</div>
	<div class="textcont" style="margin-top:10px;color:#777">
		<?=$comm['body']; ?>
	</div>
</li>
<? } ?>

<?php if($me) { ?>
<li style="width:440px;height:200px" class="meOnComment">
<?php $this->form->config(array('templates' => array('error' => '<div class="error"{:options}>{:content}</div>'))) ?>
<?=$this->form->create(null, array('url' => array('controller' => 'comments', 'action' => 'add'), 'class' => 'formEmpty', 'style' => 'margin:0;padding:0;background:transparent;border:0;-webkit-box-shadow:0 0 0;')); ?>

	<div class="author clearfix">
	<?=$this->html->image($me['avatar'], array('class' => 'avatar avatarSmall'))?>
		<p>
			<b style="display:block"><?=$me['firstname'] .' '. $me['lastname']?></b>
			<?=date('D, d M Y H:i:s'); ?>
		</p>
	</div>
		
	<?=$this->form->hidden('post_id', array('value' => $post['_id'])); ?>
	<?=$this->form->field(array('body'=>'Commentaire'), array('type' => 'textarea', 'style' => 'border-color:#CCC;width:420px')); ?>
	<?=$this->form->submit('Ajouter mon histoire', array('class' => 'btn2', 'style' => 'width:auto;padding:0 10px;background-position-x:center')); ?>
<?=$this->form->end(); ?>
</li>
<?php } ?>

</ul>
<div style="clear:both"></div>

<?php } ?>

<div style="margin:0 auto;width:60%">


</div>

</div>


<?php
/*
<?php $fullname = $post['user']['firstname'] .' '. $post['user']['lastname']?>

<h1>Créateur : <?=$this->html->link($fullname, array('controller' => 'shop', 'action' => 'view', 'args' => $post['user']['_id']))?></h1>
<ul>
	<li id="libtn-fav"><?=$this->html->link('Ajouter à mes objets préférés', array('controller' => 'favoris', 'action' => 'add', 'args' => $post['_id']), array('id' => 'btnFav'))?></li>
	
	<li><?=$this->html->link('<strike>Suivre ce créateur</strike> <i>## non implémenté</i>', array('controller' => 'favoris', 'action' => 'createur', 'args' => $post['user']['_id']), array('escape'=>false))?></li>
	<li><?=$this->html->link('<strike>Contacter ce créateur</strike> <i>## non implémenté</i>', array('controller' => 'users', 'action' => 'contact', 'args' => $post['user']['_id']),array('escape'=>false))?></li>
</ul>

<div class="ancres"><a href="#histoire" class="ancre">Histoire</a><a href="#description" class="ancre">Description & Infos pratiques</a><a href="#avis" class="ancre">Avis</a></div>

<h2 class="sectionTitle" id="description">Description & infos pratiques</h2>
<article style="overflow:hidden">
    <h1><?=$post['title']; ?></h1>
	<p><?php echo nl2br($post['body']); ?></p>
	<p>
		<?=$this->html->image($post['image']);?>
	</p>
    <p><?php echo nl2br($post['bodyfull']); ?></p>
	
	<div style="clear:both"></div>
	
	<!--<div id="product-price">-->
	<div id="product-price" style="display:none">
		<div class="big" style="color:#666;font:2em Arial"><?=$post['price']?>€ </div>
		<div><?=$post['ship']?> € de frais de livraison</div>
		<hr/>
		<?=$post['stock']?> disponibles
		<hr/>
		<select><option>Choisissez votre taille</option></select>
		<hr/><hr/>
		<div><input type="checkbox" value="1" checked="checked" /> Sticker & Message perso</div>		
		<?=$this->html->link('Je commande', array('controller' => 'checkout', 'action' => 'add', 'args' => array($post['_id'])), array('style' => 'display:block;width:100%;padding:8px 0;font: 24px Arial;margin: 30px 0 0;background:#31AFD9;color:#FFF'))?>
	</div>
	
	<!-- PAS DE COMMANDE -->
	<!--
		<h2><?=$this->html->link('Je commande', array('controller' => 'checkout', 'action' => 'add', 'args' => array($post['_id'])));?></h2>
	-->
</article>

<h2 class="sectionTitle" id="histoire">Histoire</h2>

<h3>Aucune histoire pour le moment.</h3>
 
<h2 class="sectionTitle" id="avis">Avis & Impressions</h2>
<div class="comments">
<?php
if ($comments) {
foreach ($comments as $comm) {
?>
<div class="comment">
    <h3><?=$comm['user']['firstname'] .' '. substr($comm['user']['lastname'],0,1)?>.</h3>
    <!--<p><em><?=$comm['created']; ?></em></p>-->
	<p><em><?=date('D, d M Y H:i:s', $comm['created']); ?></em></p><!-- HELPER DATE -->
    <p><?=$comm['body']; ?></p>
</div>
<?php
}
} else {
?>
    <h3>Aucune histoire pour le moment. Soyez le premier à donner votre avis sur le produit.</h3>
<?php
}
?>
</div>

<hr />

<?php if($me) { ?>

	<h2>Ajouter votre histoire</h2>
	<?php
	$this->form->config(array('templates' => array('error' => '<div class="error"{:options}>{:content}</div>')));
	?>
	<?=$this->form->create($comment, array('url' => array('controller' => 'comments', 'action' => 'add'))); ?>
		<?=$this->form->hidden('post_id', array('value' => $post['_id'])); ?>
		<?=$this->form->field(array('body'=>'Commentaire'), array('type' => 'textarea')); ?>
		<?=$this->form->submit('Ajouter mon histoire'); ?>
	<?=$this->form->end(); ?>

<?php } ?>

*/
?>