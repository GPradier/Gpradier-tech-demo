<h2>Liste des créateurs</h2>

    <ul>
        <?php foreach ($users as $user) { ?>
            <li><?=$user->username; ?></li>
        <?php } ?>
    </ul>