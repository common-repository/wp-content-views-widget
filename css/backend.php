<?php
 $absolute_path = explode('wp-content', $_SERVER['SCRIPT_FILENAME']);
 $wp_load = $absolute_path[0] . 'wp-load.php';
 require_once($wp_load);

  header('Content-type: text/css');
  header('Cache-control: must-revalidate');

?>
/* ANIMATION RIGHT TO LEFT*/
@-webkit-keyframes rightToLeft{ 
	0% {
	    opacity: 0;
	    -webkit-transform: translateX(300px);
	    -moz-transform: translateX(300px);
	    transform: translateX(300px) ;
	    
	}
	30% {
	    opacity: 1;
	    -webkit-transform: translateX(0px) ;
	    -moz-transform: translateX(0px) ;
	    transform: translateX(0px) ;

	}
	100% {
	    opacity: 1;
	    -webkit-transform: scale(1) ;
	    -moz-transform:  scale(1) ;
	    transform:  scale(1) ;
	    
	}
}




.rightToLeft{
    -webkit-animation: rightToLeft 2s ease-in-out;
    -moz-animation: rightToLeft 2s ease-in-out;
    -o-animation: rightToLeft 2s ease-in-out;
    -ms-animation: rightToLeft 2s ease-in-out;
    animation: rightToLeft 2s ease-in-out;	
	
}


.pro{
	color:#42b72a !important;
}
.proButton{
	background:#42b72a !important;
	color:#fff !important;
	padding:5px;
	margin-top:10px;
	border-radius:3px;
	text-decoration:none;
	box-shadow:1 2px 3px #777;
	font-size:20px;
	width:100%;
}

.ContentWidgetStyle{
	display:none;
}
.contentWidgetStyleToggler{
		text-align:center;
		font-weight:bold;
		font-size:20px;
}
