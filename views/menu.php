<?php

$action = !empty($_GET['action']) ? $_GET['action'] : '';
$menuItems = [
	[
		'url' => '/',
		'name' => 'Accueil',
	],
	[
		'url' => '/impression',
		'name' => 'Impression',
	],
	[
		'url' => '/contact',
		'name' => 'Contact',
	],
];

?>
<nav class="navbar navbar-expand-md navbar-dark bg-dark">
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav mr-auto">
<?php foreach ($menuItems as $menuItem): ?>
            <li class="nav-item<?php echo ($menuItem['url'] == $_SERVER['REQUEST_URI'] ? ' active' : ''); ?>"><a class="nav-link" href="<?php echo $menuItem['url']; ?>"><?php echo $menuItem['name']; ?></a></li>
<?php endforeach; ?>
        </ul>
        <ul class="navbar-nav">
<?php if (empty($_SESSION['user']['id_user'])): ?>
            <li class="nav-item"><a class="nav-link" href="/connexion">Connexion</a></li>
            <li class="nav-item"><a class="nav-link" href="/inscription">Inscription</a></li>
<?php else: ?>
			<li class="nav-item dropdown">
				<a class="nav-link dropdown-toggle active" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo $_SESSION['user']['pseudo']; ?></a>
				<div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
					<a class="dropdown-item<?php echo (in_array($action, ['account']) ? ' active' : ''); ?>" href="/mon-compte">Mon compte</a>
					<div class="dropdown-divider"></div>
					<a class="dropdown-item" href="/deconnexion">Déconnexion</a>
				</div>
			</li>
<?php endif; ?>
        </ul>
    </div>
</nav>
