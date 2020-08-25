<?php

$action = !empty($_GET['action']) ? $_GET['action'] : '';
$menuItems = [
	[
		'url' => 'admin',
		'name' => '<img src="/public/img/icon-home.png" alt="Accueil" title="Page d\'accueil de l\'administration">',
	],
	[
		'url' => 'adminUsers',
		'name' => 'Utilisateurs',
	],
	[
		'url' => 'adminOrders',
		'name' => 'Commandes',
	],
	[
		'name' => 'Gestion',
		'items' => [
			[
				'url' => 'adminPaliersNB',
				'name' => 'Paliers N&amp;B',
			],
			[
				'url' => 'adminPaliersC',
				'name' => 'Paliers couleur',
			],
			[
				'url' => 'adminCouleurs',
				'name' => 'Couleurs',
			],
		],
	],
];

?>
<nav class="navbar navbar-expand-sm navbar-light bg-light">
	<div id="navbarNavDropdown">
		<ul class="navbar-nav">
<?php foreach ($menuItems as $menuItem): ?>
	<?php if (empty($menuItem['items'])): ?>
			<li class="nav-item<?php echo ($menuItem['url'] == $action ? ' active' : ''); ?>">
				<a class="nav-link" href="/index.php?action=<?php echo $menuItem['url']; ?>"><?php echo $menuItem['name']; ?></a>
			</li>
	<?php else: ?>
			<li class="nav-item dropdown">
				<a class="nav-link dropdown-toggle<?php echo (in_array($action, array_column($menuItem['items'], 'url')) ? ' active' : ''); ?>" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo $menuItem['name']; ?></a>
				<div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
		<?php foreach ($menuItem['items'] as $menuItemSub): ?>
					<a class="dropdown-item<?php echo ($menuItemSub['url'] == $action ? ' active' : ''); ?>" href="/index.php?action=<?php echo $menuItemSub['url']; ?>"><?php echo $menuItemSub['name']; ?></a>
		<?php endforeach; ?>
				</div>
			</li>
	<?php endif; ?>
<?php endforeach; ?>
		</ul>
	</div>
</nav>
