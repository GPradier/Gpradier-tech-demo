<style>
th{text-align:center;font-weight:bold}
</style>

<h2>Liste des commentaires</h2>
<h3>Trier & Grouper par Utilisateurs</h3>
<table>
	<tr>
		<th>ID</th>
		<th>USER</th>
		<th>PRODUIT</th>
		<th>COMMENT</th>
	</tr>
<?php
$last = null;
foreach($comments as $comment) {
$user = $users[$comment['user_id']];
$product = $products[$comment['post_id']];
?>
	<tr>
		<td><abbr title="<?=$comment['_id']?>">_id</abbr></td>
		<td>
			<?php if($last != $comment['user_id']) { ?>
				<abbr title="<?=$comment['user_id']?>"><?=$user['username']?></abbr>
			<?php } ?>
		</td>
		<td><abbr title="<?=$product['_id']?>"><?=$product['title']?></abbr></td>
		<td><?=$comment['body']?></td>
	</tr>
<?php $last = $comment['user_id'];?>
<?php } ?>
</table>


<h3>Trier & Grouper par Produits</h3>
<table>
	<tr>
		<th>ID</th>
		<th>USER</th>
		<th>PRODUIT</th>
		<th>COMMENT</th>
	</tr>
<?php
$last = null;
foreach($commentsB as $comment) {
$user = $users[$comment['user_id']];
$product = $products[$comment['post_id']];
?>
	<tr>
		<td><abbr title="<?=$comment['_id']?>">_id</abbr></td>
		<td><abbr title="<?=$comment['user_id']?>"><?=$user['username']?></abbr></td>
		<td>
			<?php if($last != $comment['post_id']) { ?>
				<abbr title="<?=$product['_id']?>"><?=$product['title']?></abbr>
			<?php } ?>
		</td>
		<td><?=$comment['body']?></td>
	</tr>
<?php $last = $comment['post_id'];?>
<?php } ?>
</table>