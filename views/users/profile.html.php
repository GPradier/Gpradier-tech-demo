<h2><?=$t('Mon compte')?></h2>

<table>
<tr><td colspan="2">Informations génériques</td></tr>
<tr><td><?=$t('Email')?> :</td><td><?=$me['username']?></td></tr>
<tr><td><?=$t('Prénom')?> :</td><td><?=$me['firstname']?></td></tr>
<tr><td><?=$t('Nom')?> :</td><td><?=$me['lastname']?></td></tr>

<tr><td colspan="2">Informations complémentaires</td></tr>
<tr><td><?=$t('Avatar')?> :</td><td><?=$this->html->image($me['avatar'], array('style' => 'max-width:150px;max-height:150px'))?></td></tr>
<tr><td><?=$t('Location')?> :</td><td><?=$me['location']?></td></tr>

</table>

<h3><?=$this->html->link($t('Modifier mon profil'), 'Users::edit')?></h3>