<?php foreach($posts as $post): ?>

<div class="product">
    <h1><?= $this->html->link($post['title'], 'posts/view/'.$post['_id']); ?></h1>
    <p style="display:none">
		<?=$this->html->link('Modifier', array('controller' => 'posts', 'action' => 'edit', 'args' => array($post['_id']))); ?>
		<?=$this->html->link('Supprimer', array('controller' => 'posts', 'action' => 'delete', 'args' => array($post['_id'])), array('onclick' => 'return confirm("Supprimer ce produit ?")')); ?>
		
		<?=$this->html->link('Commander', array('controller' => 'checkout', 'action' => 'add', 'args' => array($post['_id']))); ?>
	</p>
	<div class="picture"><?=$this->html->image($post['image']);?></div>
    <p><?=$post['body'] ?></p>
</div>
<?php endforeach; ?>

<div style="clear:both"></div>

<div id="pagination">
    <p class="next floated"><?php
        if ($total <= $limit || $page == 1) {
            //echo '<li>Next Entries &rarr;';
        } else {
            echo $this->html->link('Produits suivants &rarr;', array(
                'controller' => 'posts', 'action' => 'index',
                 'page' => $page - 1, 'limit' => $limit
            ), array('escape' => false));
        } ?>
    </p>
    <p class="prev"><?php
        if ($total <= $limit || $page == ceil($total/$limit)) {
            //echo '&larr; Previous Entries</li>';
        } else {
            echo $this->html->link('&larr; Produits précédents', array(
                'controller' => 'posts', 'action' => 'index',
                'page' => $page + 1, 'limit' => $limit
            ), array('escape' => false));
        }?>
    </p>
</div>