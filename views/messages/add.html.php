<h2><?=$this->html->link('Boite de récéption', array('controller' => 'messages', 'action' => 'index'));?> &raquo; Ecrire un nouveau message</h2>

<?php
$this->form->config(array('templates' => array('error' => '<div class="error"{:options}>{:content}</div>')));
?>


<?=$this->form->create(); ?>
	<?=$this->form->label('to_id', 'À');?>
	<?=$this->form->select('to_id', $users);?>
    <?=$this->form->field(array('title' => 'Objet'));?>
    <?=$this->form->field(array('message' => 'Message'), array('type' => 'textarea', 'rows' => 4));?>		
    <?=$this->form->submit('Envoyer'); ?>
<?=$this->form->end();?>