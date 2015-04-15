<?php
function Size($bytes)
{
//$bytes = $bytes/8;
    if ($bytes > 0)
    {
        $unit = intval(log($bytes, 1024));
        $units = array('o', 'Ko', 'Mo', 'Go');

        if (array_key_exists($unit, $units) === true)
        {
            return sprintf('%d %s', $bytes / pow(1024, $unit), $units[$unit]);
        }
    }

    return $bytes;
}

function imgExtension($ext) {
	$list = array('doc', 'htm', 'pdf', 'ppt', 'rtf', 'txt', 'xls', 'xml');
	
	if(in_array($ext, $list)) {
		return '/beta/img/ext/'.$ext.'.gif';
	}
	return '/beta/img/ico203.gif';
}
?>
<h1>Tous les documents</h1>

<?php foreach($all as $file) {?>
<?php //$uploader = $this->user->get($file->user);?>
<div style="float:left;margin:10px;padding:8px;background:#EEE;border:1px solid #ccc">

<b><?=$this->html->link($file->realname, array('Upload::view', 'args' =>$file->_id))	?></b> (<?=Size($file->size)?>)<br/>
Document <?=$file->extension?>
<p>Uploadé par <?=$uploader['username']?></p>
</div>
<?php } ?>
<div style="clear:both"></div>


<h2>Mes documents seulement</h2>

<div style="clear:both"></div>

<?php foreach($my_docs as $file) {?>
<b><?=$this->html->link($file->realname . '<span class="file-size">('.Size($file->size).')</span>', array('Upload::view', 'args' =>$file->_id), array('escape' => false, 'class' => 'file-details', 'style' => 'background-image:url('.imgExtension($file->extension).')'))?></b>
<?php } ?>


<?php foreach($my_docs as $file) {
var_dump($file);

//echo '<img src="'.$file->_id.'" />';
echo $this->html->image(array('Upload::view', 'args' => $file->_id), array('class' => 'imageProd'));
} ?>

<style>.imageProd{border:4px solid #F7F7F7;max-width:220px;max-height:120px}</style>

<div style="clear:both"></div>
<style>.file-details{background: #FBFBFC url(/beta/images/ico106.gif) no-repeat 11px 5px;width: 172px;float: left;-webkit-border-radius: 7px;border-radius: 7px;border: 1px solid #E3E3E6;padding: 5px 5px 5px 29px;color: #343434;font-size: 11px;line-height: 16px;margin-right: 3px;margin-bottom: 3px;position: relative;}
.file-details:hover{color:#38A6FF;border-color:#38A6FF}
.active, .active:hover{color:#FFF;background-color:#38A6FF;border-color:#FFF}
.file-size{font-weight:normal;float:right;}
</style>
<script>$('.file-details').click(function(e){e.preventDefault();$(this).toggleClass('active');});</script>

<h2>Ajouter un nouveau document</h2>
<?=$this->form->create(null, array('url' => 'Upload::index', 'type' => 'file')); ?>

<?=$this->form->field('upload', array('type' => 'file'));?>	

<?=$this->form->submit('Upload');?>
<?=$this->form->end();?>