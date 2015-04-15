<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="<?php print $language; ?>" xml:lang="<?php print $language; ?>">

<head>
  <title><?php print $head_title; ?></title>
  <?php print $head; ?>

<link rel="stylesheet" href="<?php print $base_path . $directory; ?>/handi.css" type="text/css">
<link rel="stylesheet" href="<?php print $base_path . $directory; ?>/form.css" type="text/css">
  <?php print $styles; ?>
  <?php print $scripts; ?>
</head>

<body class="<?php print_r($body_classes);?>">
<img src="images/background.jpg" class="bg">
<div class="fullscreen">
<!-- HEADER -->
<div class="top" id="menu">
	<ul id="nav">
		<li class="logo"><a href="/" accesskey="1"><img src="<?php print $base_path . $directory; ?>/images/handi2day_sh.png" alt="Handi2Day" title="Handi2Day" /></a></li>
		<li class="header_l"></li>

		<li><?php print l2('<br /><br />Accueil', '', 'accueil active');?></li>
		<li><?php print l2('<br /><br />Accès au Salon', 'acces_salon', 'acces');?></li>
		<li><?php print l2('<br /><br />Info Salon', 'infos-salon', 'info');?></li>
		<li><?php print l2('<br /><br />Recommandations', 'recommandations', 'entreprises');?></li>

		<?php if($logged_in): ?>
		<li><?php print l2('<br /><br />Mon compte', 'mon-compte', 'connexion');?></li>
		<li class="register_blank"><?php print l2('Se déconnecter', 'logout', '');?></li>
		<?php else:?>
		<li><?php print l2('<br /><br />Se connecter', 'user/login', 'connexion');?></li>
		<li class="register"><?php print l2('<img src="/images/blank.gif" alt="Inscrivez-vous" title="Inscrivez-Vous" width="80" height="80"/>', 'inscription', 'register');?></li>
		<?php endif;?>

		<li class="header_r"></li>
	</ul>
</div>


<div class="centered">
<div class="home">

<div class="salon">
	<img src="themes/zen/images/rond_topv8.png?080413" alt="" title="Le salon en ligne et sur mobiles dédiés aux candidats en situation de handicap" /><br />
	<a href="http://itunes.apple.com/fr/app/handi2day/id432311376?mt=8" class="appstore"><img src="images/blank.gif" alt="Application Handi2Day" title="Application Handi2Day" width="304" height="49" /></a><br />
	<img src="themes/zen/images/rond_bot.png" alt="" title="Le salon en ligne et sur mobiles dédiés aux candidats en situation de handicap" />
</div>

<div style="float:right;padding-top:200px;">
		<?php if($logged_in): ?>
<!--		<a href="/coaching"><img src="themes/zen/images/coaching.png" alt="Accédez au coaching" title="Accédez au coaching" /></a><br /><br />-->
		<a href="/acces_salon"><img src="themes/zen/images/salon.png" alt="Accédez au salon" title="Accédez au salon" /></a><br /><br />
		<?php else:?>
<!--		<a href="/speed_dating"><img src="themes/zen/images/dating.png" alt="Accédez au coaching" title="Accédez au coaching" /></a><br /><br />-->
		<a href="/inscription"><img src="themes/zen/images/boutoninscrivezvous.png" alt="Inscrivez-vous" title="Inscrivez-vous" /></a><br /><br />
		<?php endif;?>
		<a href="/infos-salon"><img src="themes/zen/images/plus.png" alt="En savoir plus" title="En savoir plus" /></a>
</div>

</div>
</div>
  
<div class="bottom2 open">
	<ul id="nav_bot">
		<li class="large">
		<a href="/acces_salon" title="Exposants">Exposants</a>
		<a href="#" class="prev"><img src="images/arrow_l.png" alt="Gauche" title="Exposants précédents" class="left" /></a>
		<a href="#" class="next"><img src="images/arrow_r.png" alt="Droite" title="Exposants suivants" class="right" /></a>
		
		<div class="default" style="padding-top:0px;top:-10px">
			<ul>
			<?php print $footer_message; ?></p>
			</ul>
		</div>
		
		</li>
		<li>
		<a href="#" title="Organisateurs">Organisateurs</a>
		<div  style="padding-top:10px;">
			<a href="http://www.job2day.fr/"><img src="images/logos/small_logo_job2day.png" alt="Job2Day" title="Job2Day" /></a> 	
			<a href="http://www.handicap.fr/"><img src="images/logo_handicapfr.png" alt="Handicap.fr" title="Handicap.fr" /></a>
		</div>
		</li>
		<li class="small"><a href="#" title="Parrain">Parrain</a>
			<div style="padding-top:10px;">
			<a href="http://www.agefiph.fr/"><img src="images/logos/agefiph.png" alt="agefiph" title="agefiph" /></a>
			</div>
		</li>
		<li style="position:relative;"><a href="#" title="Partenaires">Partenaires</a>
		<a href="#" class="prev2"><img src="images/arrow_l.png" alt="Gauche" title="Exposants précédents" class="left2" /></a>
		<a href="#" class="next2"><img src="images/arrow_r.png" alt="Droite" title="Exposants suivants" class="right2" /></a>
		<div class="parteners" style="padding:5px 0 0 0;margin-left:15px;">
		<ul> 
			<li class="carousel"><a href="http://www.pole-emploi.fr/"><img src="images/logos/pole_emploi.png" alt="Pôle Emploi" title="Pôle Emploi" /></a></li>
			<li class="carousel"><a href="http://www.forumgv.com/"><img src="images/logos/logo_fgv.png" alt="Fonds Handicap et Societe" title="FHS" /></li>
			<li class="carousel"><a href="http://www.fondshs.fr/"><img src="images/logos/FHS.png" alt="Fonds Handicap et Societe" title="FHS" /></li>
			<li class="carousel"><a href="http://www.caubel.com/"><img src="images/logos/lc_conseil_2.png" alt="LC Conseil" title="LC Conseil" /></a></li>
			<li class="carousel"><a href="http://www.multiposting.fr/"><img src="images/logos/multiposting.png" alt="multiposting" title="multiposting" /></li>
		</ul>
		</div>
		</li>
	</ul>
	<div class="bottom">
		<p><a href="/accessibilite" accesskey="0">Aide &amp; Accessibilit&eacute; </a> | <a href="#contenu">Contenu</a> | <a href="#menu">Menu</a> | <a href="/legals">Mentions l&eacute;gales</a></p>
	</div>
</div>

<?php print $closure; ?>

</div>
<div id="Help"><a href="mailto:team@handi2day.fr" accesskey="9"><img src="<?php print $base_path . $directory; ?>/images/blank.gif" alt="" width="86" height="95" /></a></div>

<a title="Google Analytics Alternative" href="http://getclicky.com/223475"></a>
<script src="http://static.getclicky.com/js" type="text/javascript"></script>
<script type="text/javascript">try{ clicky.init(66399311); }catch(err){}</script>

</body>
</html>