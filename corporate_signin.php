<?php
	require_once "helper/formvalidator.php";
	require_once "helper/db_connection.php";
	require_once "helper/helper_functions.php";
	session_start();
?>

	<form id='company_signin' action='corporate_signin.php' method='post'>
		<input type='hidden' name='company_signin_submitted' value='1'/>
		<label for='company_email' >Company email: </label>
		<input type='text' name='company_email' id='company_email' /> <br>
		<label for='company_password' >Company password: </label>
		<input type='password' name='company_password' id='company_password' /> <br>
		<input type='submit' name='company_signin_submit' value='Submit' />
	</form>

<?php
	if (isset($_POST['company_signin_submitted'])) {
		$validator = new FormValidator();
		$validator->addValidation("company_email","email","Please fill a valid Company email");
		$validator->addValidation("company_email","req","Please fill in Company email");
		$validator->addValidation("company_password","req","Please fill in Company password");
		if($validator->ValidateForm()) {
			$retrieve_existing_comp = "SELECT * FROM corporates";
			$retrieved_comp = mysqli_query($conn, $retrieve_existing_comp);
			if ($retrieved_comp) {
				if (mysqli_num_rows($retrieved_comp) > 0) {
					while ($row = mysqli_fetch_array($retrieved_comp)) {
						if ($_POST['company_email'] == $row['corporate_email']
							&& $_POST['company_password'] == $row['corporate_password']) {
							// redirect to homepage of logged in company
							$_SESSION["corp_id"] = $row['corporate_id'];
							redirect("corporate_homepage.php", false);
						}
					}
				}
				echo "<h2>Data Retrieved!</h2>";
			}
			else {
				mysqli_error($conn);
			}
		}
		else {
			echo "<h2>Error!</h2>";
		}
	}
?>