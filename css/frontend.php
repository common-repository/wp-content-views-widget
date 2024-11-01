<?php
 $absolute_path = explode('wp-content', $_SERVER['SCRIPT_FILENAME']);
 $wp_load = $absolute_path[0] . 'wp-load.php';
 require_once($wp_load);

  header('Content-type: text/css');
  header('Cache-control: must-revalidate');

?>

.content-widget ul{
	list-style:none;		
}

.column-1,
.columns-2,
.columns-3,
.columns-4,
.columns-5,
.columns-6{
	box-sizing:border-box;
	float:left !important;
}

.column-1{
	width:100% !important;
	float:none !important;
}
.columns-2{
	width:50% !important;
}
.columns-3{
	width:33.3% !important;
}
.columns-4{
	width:25% !important;
}
.columns-5{
	width:20% !important;
}
.columns-6{
	width:16.6% !important;
}

@media(max-width:767px){

	.content-widget ul{
		display: block;	
	}

	.columns-2,
	.columns-3,
	.columns-4,
	.columns-5,
	.columns-6{	
		float:none !important;
		width:100% !important;
	}
}




