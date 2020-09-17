<?php

$css = '<link rel="stylesheet" href="/public/css/home.css">';

require_once 'views/head.php';
?>

<div class="row">
	<div class="col-12">
		<h1>Imprimez vos documents à distance !</h1>
		<div class="cycle-slideshow" 
			data-cycle-fx="scrollHorz"
			data-cycle-timeout="3000"
			data-cycle-speed="700"
			data-cycle-pause-on-hover="true"
			data-cycle-caption-plugin="caption2"
			data-cycle-overlay-fx-sel=">div"
			data-cycle-pager=".cycle-pager"
		>
			<div class="cycle-caption"></div>
			<div class="cycle-overlay"></div>
			<div class="cycle-prev"></div>
			<div class="cycle-next"></div>
			
			<img src="/public/img/imprimante.jpg" data-cycle-title="Spring" data-cycle-desc="Sonnenberg Gardens">
			<img src="http://malsup.github.io/images/p2.jpg" data-cycle-title="Redwoods" data-cycle-desc="Muir Woods National Monument">
			<img src="http://malsup.github.io/images/p3.jpg" data-cycle-title="Angle Island" data-cycle-desc="San Franscisco Bay">
			<img src="http://malsup.github.io/images/p4.jpg" data-cycle-title="Raquette Lake" data-cycle-desc="Adirondack State Park">
		</div>
		<div class="cycle-pager"></div>
	</div>
</div>
<div class="row">
	<div class="col-md-4">
		<p>Lien 1</p>
	</div>
	<div class="col-md-4">
		<p>Lien 2</p>
	</div>
	<div class="col-md-4">
		<p>Lien 3</p>
	</div>
</div>

<?php

$javascript = '
<script src="/public/js/jquery.cycle2.js"></script> 
<script src="/public/js/jquery.cycle2.caption2.min.js"></script>
';

require_once 'views/footer.php';
