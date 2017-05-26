<?php
	require_once "helper/formvalidator.php";
	require_once "helper/db_connection.php";
	require_once "helper/helper_functions.php";
	session_start();
?>
	<form id='company_signup' action='company_register.php' method='post'>
		<input type='hidden' name='company_signup_submitted' value='1'/>
		<label for='company_name' >Company name: </label>
		<input type='text' name='company_name' id='company_name' /> <br>
		<label for='company_country' >Company country: </label>
		<select type='text' name='company_country' id='company_country'></select> <br>
		<label for='company_city' >Company city: </label>
		<select type='text' name='company_city' id='company_city'></select> <br>
		<label for='company_email' >Company email: </label>
		<input type='text' name='company_email' id='company_email' /> <br>
		<label for='company_password' >Company password: </label>
		<input type='password' name='company_password' id='company_password' /> <br>
		<input type='submit' name='company_signup_submit' value='Submit' />
	</form>


	<!-- api for generating all countries with associated cities -->
	<script src="js/countries.js"></script>
	<script language="javascript">
        populateCountries("company_country", "company_city");
    </script>

<?php
	// corresponding form is submitted
	if (isset($_POST['company_signup_submitted'])) {
		$validator = new FormValidator();
		$validator->addValidation("company_name","req","Please fill in Company name");
		$validator->addValidation("company_city","req","Please fill in Company city");
		$validator->addValidation("company_country","req","Please fill in Company country");
		$validator->addValidation("company_email","email",
		"The input for Email should be a valid email value");
		$validator->addValidation("company_email","req","Please fill in Company email");
		$validator->addValidation("company_password","req","Please fill in Company password");
		// password must start with a letter and contains 4-14 characters
		$validator->addValidation("company_password","regexp=/^[a-zA-Z]\w{3,14}$/","Please fill in Company password");
		if($validator->ValidateForm()) {
			echo "<h2>Validation Success!</h2>";
			// inserting new corporate in the corporates table
			$new_corp_query = "INSERT INTO corporates (corporate_email, corporate_password, corporate_name,
							corporate_city, corporate_country) VALUES ('"  
							. strtolower($_POST["company_email"])
							. "', '" . (strtolower($_POST["company_password"]))
							. "', '" . (strtolower($_POST["company_name"]))
							. "', '" . (strtolower($_POST["company_city"]))
							. "', '" . (strtolower($_POST["company_country"]))
							. "')";
			// if conditional is left to execute the query
			// body for debugging
			if (mysqli_query($conn, $new_corp_query)) {
				//echo "inserted";
				$retrieve_corp_id_query = "SELECT corporate_id FROM corporates WHERE corporate_email = '"
										. $_POST["company_email"] . "'";
				
				if (mysqli_query($conn, $retrieve_corp_id_query)) {
					$result = mysqli_query($conn, $retrieve_corp_id_query);
					if (mysqli_num_rows($result) > 0) {
						while ($row = mysqli_fetch_assoc($result)) {
							$_SESSION["corp_id"] = $row["corporate_id"];
							redirect("corporate_homepage.php", false);
							break;
						}
					}
					else {
						// echo "No data!";
					}
				}
				else {
					// echo $retrieve_corp_id_query . " --> " . mysqli_error($conn);
				}
			}
			else {
				//echo $new_corp_query . "--" . mysqli_error($conn);
			}
		}
		else {
			echo "<h2>Validation failed!</h2>";
		}
		mysqli_close($conn);
	}
?>