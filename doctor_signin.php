<?php
	require_once "helper/formvalidator.php";
	require_once "helper/db_connection.php";
	require_once "helper/helper_functions.php";
	session_start();
?>

	<form id='doctor_signin' action='doctor_signin.php' method='post'>
		<input type='hidden' name='doctor_signin_submitted' value='1'/>
		<label for='doctor_email' >Doctor email: </label>
		<input type='text' name='doctor_email' id='doctor_email' /> <br>
		<label for='doctor_password' >Company password: </label>
		<input type='password' name='doctor_password' id='doctor_password' /> <br>
		<input type='submit' name='doctor_signin_submit' value='Sign in' />
	</form>

<?php
	if (isset($_POST['doctor_signin_submitted'])) {
		$validator = new FormValidator();
		$validator->addValidation("doctor_email","email","Please fill a valid Doctor email");
		$validator->addValidation("doctor_email","req","Please fill in Doctor email");
		$validator->addValidation("doctor_password","req","Please fill in Doctor password");
		if($validator->ValidateForm()) {
			$retrieve_existing_doc = "SELECT * FROM doctors WHERE"
								   . " email = '" . $_POST['doctor_email']
								   . "' AND password = '" . $_POST['doctor_password']
								   . "'";
			$retrieve_existing_doc = mysqli_query($conn, $retrieve_existing_doc);
			if (mysqli_num_rows($retrieve_existing_doc) > 0) {
				while ($row = mysqli_fetch_array($retrieve_existing_doc)) {
					$_SESSION['doc_email'] = $row['email'];
					redirect('doctor_homepage.php', false);
					break;
				}
			}
		}
		else {
			$error_hash = $validator->GetErrors();
			foreach($error_hash as $inpname => $inp_err) {
				echo "<p>$inpname : $inp_err</p>\n";
			}
		}
	}
?>
