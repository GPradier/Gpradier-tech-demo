<?=$this->form->create(null, array('url' => array('Upload::index', 'args' => $id_related), 'type' => 'file')); ?>
<?=$this->form->field('upload', array('type' => 'file'));?>	
<?=$this->form->submit('Upload');?>
<?=$this->form->end();?>