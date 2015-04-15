<?php
/*
$data = array(
	$a=42,
	$b="$a", $c=str_repeat('10', $b+1), $d=$c===decbin(42)
);

foreach ($data as $value) {
    echo $value," => ",gettype($value), "\n";
}
*/
?>

<h2>Boite de récéption</h2>

<h4><?=$this->html->link('Ecrire un nouveau message', 'Messages::add')?></h4>

<h4>Messages reçus</h4>
<?php
if(count($msgReceived)==0) {
	echo '<h5>Aucun message reçu.</h5>';
}
else {
?>
	<table>
	<?php foreach($msgReceived as $msg) {
		$from = $users[$msg['from_id']];?>
		<tr>
			<td><?=$this->form->checkbox($msg['_id'])?></td>
			<td>
				<?=$msg['title'];?><br/>
				<?=substr($msg['message'], 0, 40);?>
			</td>
			<td><?=$this->html->link($from['username'], '##')?></td>
			<td><?=date('j F', $msg['created']);?></td>
			<!--<td>Produit concerné</td>-->
		</tr>
		<?php
	}
	?>
	</table>
<?php
}
?>
<hr/>

<h4>Messages envoyés</h4>
<?php
if(count($msgSent)==0) {
	echo '<h5>Aucun message envoyé.</h5>';
}
else {
?>
	<table>
	<?php foreach($msgSent as $msg) {
		$to = $users[$msg['to_id']];?>
		<tr>
			<td><?=$this->form->checkbox($msg['_id'])?></td>
			<td>
				<?=$msg['title'];?><br/>
				<?=substr($msg['message'], 0, 40);?>
			</td>
			<td>À : <?=$this->html->link($to['username'], '##')?></td>
			<td><?=date('j F', $msg['created']);?></td>
			<!--<td>Produit concerné</td>-->
		</tr>
		<?php
	}
	?>
	</table>
<?php
}
?>