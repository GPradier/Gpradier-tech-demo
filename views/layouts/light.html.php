<?php
use lithium\security\Auth;
?>
<!doctype html>
<html>
<head>
	<?php echo $this->html->charset();?>
	<title><?=$title;?> - FavStory </title>
	<?php echo $this->html->style(array('lithium_s', 'debug', 'favstory')); ?>
	<?php echo $this->scripts('<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>'); ?>
	<?php echo $this->html->script('favstory.js'); ?>
	<?php echo $this->html->link('Icon', null, array('type' => 'icon')); ?>
	
<link href='http://fonts.googleapis.com/css?family=Kavoon' rel='stylesheet' type='text/css'>
<link href='http://fonts.googleapis.com/css?family=Roboto+Condensed' rel='stylesheet' type='text/css'>
<style>
body {
background:url('/favstory/new/img/bkg-home.png') center top repeat;
}
form {
-moz-box-shadow: 2px 2px 12px 10px rgba(0,0,0,.35);
-webkit-box-shadow: 2px 2px 12px 10px rgba(0,0,0,.35);
box-shadow: 2px 2px 12px 10px rgba(0,0,0,.35);
width: 540px;
margin: 0 auto;
border: 5px solid #555;
}

input[type=submit] {
background: rgb(252,143,29);
color: #FFF !important;
border-radius: 100px;
text-transform: uppercase;
font-family: 'Roboto Condensed', Arial, sans-serif;
text-shadow: 0 0 5px rgb(221,117,9);
font-size: 22px;
margin: 16px auto 0;
font-weight: normal;
}
input[type=submit]:hover {background: rgb(221,117,9) !important;}

input {border:1px solid #CCC}

h2 {
    text-align: center;
    color: #FFF;
    text-transform: uppercase;
    font-size: 54px;
	margin:0;
}
</style>
</head>
<body>
	<header>
		<div id="header">
			<?=$this->html->link(' ', 'Pages::home', array('id' => 'logo'))?>
		</div>
	</header>
	<?php echo $this->content();?>
</body>
</html>