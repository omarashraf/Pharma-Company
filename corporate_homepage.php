<?php
	require_once "helper/formvalidator.php";
	require_once "helper/db_connection.php";
	require_once "helper/helper_functions.php";
	session_start();
	$related_products_query = "SELECT * FROM products WHERE company_id = " . $_SESSION['corp_id'];
	if (mysqli_query($conn, $related_products_query)) {
		$result = mysqli_query($conn, $related_products_query);
		if (mysqli_num_rows($result) > 0) {
			while ($row = mysqli_fetch_assoc($result)) {
				echo "<a href='corporate_prod_presentation.php?prod_id=" . $row['product_id']
					 . "'>" . $row['product_name'] . "</a> <br>";
			}
		}
	}
	echo "<a href='corporate_new_product.php'>Add new product</a>" 
?>