<form action="corporate_new_product.php" method="post" enctype="multipart/form-data">
	<input type='hidden' name='corporate_new_product_submitted' value='1'/>
	<label for='product_name' >Product name: </label>
    <input type="text" name="product_name" id="product_name" /> <br>
    <label for='product_description' >Product description: </label>
    <textarea name="product_description" id="product_description"></textarea> <br>
    <label for='product_conc' >Concentration: </label>
    <input type="text" name="product_conc" id="product_conc" /> <br>
    <label for='product_dosage' >Dosage form: </label>
    <input type="text" name="product_dosage" id="product_dosage" /> <br>
    <input type="submit" value="Submit" name="submit_new_product">
</form>

<?php

	// new product form is submitted
	if (isset($_POST['submit_new_product'])) {
		$validator = new FormValidator();
		$validator->addValidation("product_name","req","Please fill in product name");
		$validator->addValidation("product_description","req","Please fill in product description");
		if ($validator->ValidateForm()) {
			// required data is entered
			$new_product_query = "INSERT INTO products (company_id, product_name, product_description, concentration, 					 dosage_form)"
							   . " VALUES ('" . $_SESSION["corp_id"]
							   . "', '" . $_POST['product_name']
							   . "', '" . $_POST['product_description']
							   . "', '" . $_POST['product_conc']
							   . "', '" . $_POST['product_dosage'] . "')";
			if (!mysqli_query($conn, $new_product_query)) {
				echo $new_product_query . " --> " . mysqli_error($conn);
			}
		}
	}
?>