<?php
	require_once "helper/formvalidator.php";
	require_once "helper/db_connection.php";
	require_once "helper/helper_functions.php";
	session_start();

	$retrieve_corp_info = "SELECT * FROM corporates WHERE corporate_id = " . $_SESSION["corp_id"];

	$res = mysqli_query($conn, $retrieve_corp_info);
	$corp_name = "";
	$corp_email = "";
	$corp_password = "";
	if (mysqli_num_rows($res) > 0) {
		while ($row = mysqli_fetch_assoc($res)) {
			$corp_name = $row["corporate_name"];
			$corp_email = $row["corporate_email"];
			$corp_password = $row["corporate_password"];
		}
	}
?>

	<form action='corporate_profile_edit.php' method='post'>
		<input type='hidden' name='company_edit_submitted' value='1'/>
		<label for='company_name' >Company name: </label>
		<input type='text' name='company_name' id='company_name' value=<?php echo $corp_name ?> /> <br>
		<label for='company_email' >Company email: </label>
		<input type='text' name='company_email' id='company_email' value=<?php echo $corp_email ?> /> <br>
		<label for='company_password' >Company password: </label>
		<input type='password' name='company_password' id='company_password' value=<?php echo $corp_password ?> /> <br>
		<input type='submit' name='company_edit_submit' value='Submit' />
	</form>

<?php
	if (isset($_POST["company_edit_submitted"])) {
		$validator = new FormValidator();
		$validator->addValidation("company_name","req","Please fill in Company name");
		$validator->addValidation("company_email","email", "The input for Email should be a valid email value");
		$validator->addValidation("company_email","req","Please fill in Company email");
		$validator->addValidation("company_password","req","Please fill in Company password");
		// password must start with a letter and contains 4-14 characters
		$validator->addValidation("company_password","regexp=/^[a-zA-Z]\w{3,14}$/","Please fill in Company password");
		if($validator->ValidateForm()) {
			$update_corp_info = "UPDATE corporates SET corporate_email = '" . $_POST["company_email"]
						      . "', corporate_password = '" . $_POST["company_password"]
						      . "', corporate_name = '" . $_POST["company_name"] . "'"
						      . " WHERE corporate_email = '" . $corp_email . "'";

			if (!mysqli_query($conn, $update_corp_info)) {
				echo $update_corp_info . "--" . mysqli_error($conn);
			}
			else {
				redirect("corporate_homepage.php", false);
			}
		}
	}
?>