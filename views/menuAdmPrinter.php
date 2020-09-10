<?php

$action = !empty($_GET['action']) ? $_GET['action'] : '';
$menuItems = [
	[
		'url' => 'admin',
		'name' => '<i class="fas fa-home"></i> Accueil',
	],
	[
		'url' => 'adminOrders',
		'name' => '<i class="fas fa-file"></i> Commandes',
	],
	/*[
		'name' => 'Gestion',
		'items' => [
			[
				'url' => 'adminPaliersNB',
				'name' => 'Paliers N&amp;B',
			],
			[
				'url' => 'adminPaliersCouleur',
				'name' => 'Paliers couleur',
			],
			[
				'url' => 'adminCouleurs',
				'name' => 'Couleurs',
			],
		],
	],*/
];
$menuItemsExceptions = [
	'adminOrdersPast' => 'adminOrders',
];

?>
<nav class="navbar navbar-expand-sm navbar-light bg-light">
	<div id="navbarNavDropdown">
		<ul class="navbar-nav">
<?php foreach ($menuItems as $menuItem): ?>
	<?php if (empty($menuItem['items'])): ?>
			<li class="nav-item<?php echo ($menuItem['url'] == $action || (array_key_exists($action, $menuItemsExceptions) && $menuItemsExceptions[$action] == $menuItem['url']) ? ' active' : ''); ?>">
				<a class="nav-link" href="/index.php?action=<?php echo $menuItem['url']; ?>"><?php echo $menuItem['name']; ?></a>
			</li>
	<?php /*else: ?>
			<li class="nav-item dropdown">
				<a class="nav-link dropdown-toggle<?php echo (in_array($action, array_column($menuItem['items'], 'url')) ? ' active' : ''); ?>" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo $menuItem['name']; ?></a>
				<div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
		<?php foreach ($menuItem['items'] as $menuItemSub): ?>
					<a class="dropdown-item<?php echo ($menuItemSub['url'] == $action || (array_key_exists($action, $menuItemsExceptions) && $menuItemsExceptions[$action] == $menuItemSub['url']) ? ' active' : ''); ?>" href="/index.php?action=<?php echo $menuItemSub['url']; ?>"><?php echo $menuItemSub['name']; ?></a>
		<?php endforeach; ?>
				</div>
			</li>
		<?php */endif; ?>
<?php endforeach; ?>
		</ul>
	</div>
</nav>
