<?=$this->form->create(null, array('url' => 'Files::index', 'type' => 'file')); ?>

<?=$this->form->field('upload', array('type' => 'file'));?>	

<?=$this->form->submit('Upload');?>
<?=$this->form->end();?>