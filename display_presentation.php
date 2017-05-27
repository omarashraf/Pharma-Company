<?php
	require_once "helper/formvalidator.php";
	require_once "helper/db_connection.php";
	require_once "helper/helper_functions.php";
	$pres_link = $_GET["pres_link"];
?>

<iframe src="<?php echo $pres_link; ?>" width="100%" height="100%"></iframe>