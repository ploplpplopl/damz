<?php

$css = '
<style>
.cycle-slideshow img{ 
    position:absolute;
	top:0;
	left:0;
    width:100%;
	padding:0;
	display:block;
}

.cycle-slideshow img:first-child{
    position:static;
	z-index:100;
}

.cycle-pager{ 
    text-align:center;
	width:100%;
	z-index:500;
	overflow:hidden;
}
.cycle-pager span{ 
    font-size:50px;
	line-height:1;
	width:16px;
	height:16px; 
    display:inline-block;
	color:#ddd;
	cursor:pointer; 
}
.cycle-pager span.cycle-pager-active{color:#D69746;}
.cycle-pager > * {cursor:pointer;}

.cycle-caption{
	position:absolute;
	color:rgba(255,255,255,.75);
	bottom:15px;
	right:15px;
	z-index:700;
}
.cycle-overlay{ 
    position:absolute;
	bottom:0;
	width:100%;
	z-index:600;
    background:rgba(0,0,0,.5);
	color:rgba(255,255,255,.75);
	padding:15px;
}
.cycle-overlay div:first-child{ 
    font-size:1.25em;
}

.cycle-prev, .cycle-next{
	position:absolute;
	top:0;
	width:30%;
	opacity:0;
	filter:alpha(opacity=0);
	z-index:800;
	height:100%;
	cursor:pointer;
}
.cycle-prev{left:0;background:url(http://malsup.github.com/images/left.png) 5% 50% no-repeat;}
.cycle-next{right:0;background:url(http://malsup.github.com/images/right.png) 95% 50% no-repeat;}
.cycle-prev:hover, .cycle-next:hover{opacity:.7;filter:alpha(opacity=70);}

.disabled{opacity:.5;filter:alpha(opacity=50);}

.cycle-paused:after{
    content:"Pause";
	color:#fff;
	background:#000;
	padding:10px;
    z-index:500;
	position:absolute;
	top:10px;
	right:10px;
    border-radius:10px;
    opacity:.5;
	filter:alpha(opacity=50);
}

</style>
';
require_once 'views/head.php';
?>

<div class="row">
	<div class="col-12">
		<h1>Impression de documents à Caen</h1>
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
			
			<img src="http://malsup.github.io/images/p1.jpg" data-cycle-title="Spring" data-cycle-desc="Sonnenberg Gardens">
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
<script src="http://malsup.github.com/jquery.cycle2.js"></script>
<script src="http://malsup.github.io/min/jquery.cycle2.caption2.min.js"></script>
';

require_once 'views/footer.php';
