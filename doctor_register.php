<?php
	require_once "helper/formvalidator.php";
	require_once "helper/db_connection.php";
	require_once "helper/helper_functions.php";
?>

	<form action="doc_register.php" method="post" enctype="multipart/form-data">
		<input type='hidden' name='doc_signup_submitted' value='1'/>
		<label for='doc_fname' >First name: </label>
		<input type='text' name='doc_fname' id='doc_fname' /> <br>
		<label for='doc_family_name' >Family name: </label>
		<input type='text' name='doc_family_name' id='doc_family_name'> <br>
		<label for='doc_email' >Email: </label>
		<input type='text' name='doc_email' id='doc_email'> <br>
		<label for='doc_password' >Password: </label>
		<input type='password' name='doc_password' id='doc_password' /> <br>
		<label for='doc_moblie' >Mobile: </label>
		<input type='text' name='doc_moblie' id='doc_moblie' /> <br>
		<label for='doc_landline' >Landline: </label>
		<input type='text' name='doc_landline' id='doc_landline' /> <br>
		<label for='doc_address' >Address: </label>
		<input type='text' name='doc_address' id='doc_address' /> <br>
		<label for='doc_country' >Country: </label>
		<select type='text' name='doc_country' id='doc_country'></select> <br>
		<label for='doc_city' >City: </label>
		<select type='text' name='doc_city' id='doc_city'></select> <br>
		<label for='doc_specialty' >Specialty: </label>
		<select type='text' name='doc_specialty' id='doc_specialty'></select> <br>
		<label for='doc_subspecialty' >Subspecialty: </label>
		<select multiple type='text' name='doc_subspecialty[]' id='doc_subspecialty'></select> <br>
		<label for='doc_lic_number' >Licence no.: </label>
		<input type='text' name='doc_lic_number' id='doc_lic_number' /> <br>
	    Select presentation to upload:
	    <input type="file" name="file_upload" id="file_upload" />
	    <input type="submit" value="Upload" name="submit">
	</form>

	<!-- api for generating all countries with associated cities -->
	<script src="js/countries.js"></script>
	<script language="javascript">
        populateCountries("doc_country", "doc_city");
    </script>

    <!-- api for generating all specialties with associated sub-specialties -->
    <script src="js/specialties.js"></script>
    <script type="text/javascript">
    	populateSpecialties("doc_specialty", "doc_subspecialty");
    </script>

<?php
	// corresponding form is submitted
	if (isset($_POST['doc_signup_submitted'])) {
		$validator = new FormValidator();
		$validator->addValidation("doc_fname","req","Please fill in first name");
		$validator->addValidation("doc_family_name","req","Please fill in family name");
		$validator->addValidation("doc_email","email",
		"The input for Email should be a valid email value");
		$validator->addValidation("doc_email","req","Please fill in email");

		// password must start with a letter and contains 4-14 characters
		$validator->addValidation("doc_password","regexp=/^[a-zA-Z]\w{3,14}$/","Please fill in password");
		$validator->addValidation("doc_password","req","Please fill in password");

		$validator->addValidation("doc_moblie","req","Please fill in mobile number");
		$validator->addValidation("doc_country","req","Please fill in country");
		$validator->addValidation("doc_city","req","Please fill in city");
		$validator->addValidation("doc_specialty","req","Please fill in specialty");
		$validator->addValidation("doc_lic_number","req","Please fill in licence number");

		if ($validator->ValidateForm()) {
			$target_dir = "licences/";
			$target_file = $target_dir . basename($_FILES["file_upload"]["name"]);
			$uploadOk = 1;
			$file_type = pathinfo($target_file,PATHINFO_EXTENSION);

			// Check if image file is a actual image or fake image
			if(isset($_POST["submit"])) {
			    $file_size = filesize($_FILES["file_upload"]["tmp_name"]);
			    if($file_size !== false) {
			        echo "File's size' - " . $file_size . ".";
			        $uploadOk = 1;

			        // Check file format
					if($file_type != "jpg" && $file_type != "jpeg" && $file_type != "png" && $file_type != "gif") {
					    echo "Sorry, only img files are allowed.";
					    $uploadOk = 0;
					}

					// Check file size
					if ($_FILES["file_upload"]["size"] > 500000) {
					    echo "Sorry, your file is too large.";
					    $uploadOk = 0;
					}

					// Check if file already exists
					if (file_exists($target_file)) {
					    echo "Sorry, file already exists.";
					    $uploadOk = 0;
					}

					if ($uploadOk == 1) {
						if (move_uploaded_file($_FILES['file_upload']['tmp_name'], $target_file)) {
							// insert new doctor's data into doctors table
							$new_doc_query = "INSERT INTO doctors (first_name, last_name, email,
											password, mobile_phone, landline, address, country, governorate,
											specialty, licence_number, licence_image) VALUES ('"  
											. (strtolower($_POST["doc_fname"]))
											. "', '" . (strtolower($_POST["doc_family_name"]))
											. "', '" . (strtolower($_POST["doc_email"]))
											. "', '" . (strtolower($_POST["doc_password"]))
											. "', '" . (strtolower($_POST["doc_moblie"]))
											. "', '" . (strtolower($_POST["doc_landline"]))
											. "', '" . (strtolower($_POST["doc_address"]))
											. "', '" . (strtolower($_POST["doc_country"]))
											. "', '" . (strtolower($_POST["doc_city"]))
											. "', '" . (strtolower($_POST["doc_specialty"]))
											. "', '" . (strtolower($_POST["doc_lic_number"]))
											. "', '" . $target_file
											. "')";

							// if conditional is left to execute the query
							// body for debugging
							if (mysqli_query($conn, $new_doc_query)) {
								// echo "doc inserted";
							}
							else {
								//echo $new_doc_query . "--" . mysqli_error($conn);
							}

							// inserting subsepcialty data in corresponding relation --> doctors_subspecialty
							foreach ($_POST['doc_subspecialty'] as $selectedOption) {
								$new_doc_subspecialty_query = "INSERT INTO doctors_subspecialty (doctor_email, 
															   doctor_subspecialty) 
															   VALUES ('"  
															   . (strtolower($_POST["doc_email"]))
															   . "', '" . (strtolower($selectedOption))
															   . "')";
								// if conditional is left to execute the query
								// body for debugging
								if (mysqli_query($conn, $new_doc_subspecialty_query)) {
									//echo "subspecialty inserted, ";
								}
								else {
									//echo $new_doc_query . "--" . mysqli_error($conn);
								}
							}
						} 
						else {
							echo "File was not uploaded!";
						}
					}
			    } else {
			        echo "File is not uploaded.";
			        $uploadOk = 0;
			    }
			}
		}
		else {
			// error resulting from validation fail
			/*$error_hash = $validator->GetErrors();
			foreach($error_hash as $inpname => $inp_err) {
				echo "<p>$inpname : $inp_err</p>\n";
			}*/
		}
	}

	echo "<h2>Welcome!</h2>";
?>