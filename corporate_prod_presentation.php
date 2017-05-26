<?php
	require_once "helper/formvalidator.php";
	require_once "helper/db_connection.php";
	require_once "helper/helper_functions.php";
?>

	<form action=<?php echo "corporate_prod_presentation.php?prod_id=" . $_GET['prod_id'] ?> method="post" enctype="multipart/form-data">
		<input type='hidden' name='company_upload_submitted' value='1'/>
	    Select presentation to upload:
	    <input type="file" name="file_upload" id="file_upload" />
	    <input type="submit" value="Upload" name="submit">
	</form>

<?php
	// corresponding form is submitted
	if (isset($_POST['company_upload_submitted'])) {
		$target_dir = "presentations/";
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
				if($file_type != "pdf") {
				    echo "Sorry, only PDF files are allowed.";
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

				// all checks are satisfied --> $uploadOk = 1
				if ($uploadOk == 1) {
					if (move_uploaded_file($_FILES['file_upload']['tmp_name'], $target_file)) {
						// enter the file name/path in the presentations table
						$new_file_path = "INSERT INTO presentations (presentation_link, presentation_size)
											VALUES ('" . $target_file . "', " . $file_size . ")";
						// if conditional is left to execute the query
						if (mysqli_query($conn, $new_file_path)) {
							//echo "file inserted in db";
							$retrieve_new_pres = "SELECT * FROM presentations WHERE presentation_link = '"
											   . $target_file . "'";
							$result = mysqli_query($conn, $retrieve_new_pres);
							if (mysqli_num_rows($result) > 0) {
								while ($row = mysqli_fetch_assoc($result)) {
									$pres_id = $row['presentation_id'];
								}
							}
							$prod_pres_query = "INSERT INTO products_presentations (product_id, presentation_id)"
											 . " VALUES (" . $_GET['prod_id']
											 . ", " . $pres_id . ")";
							// if conditional is left to execute the query
							if (mysqli_query($conn, $prod_pres_query)) {
								//echo "INSERTED";
							}
						}
						else {
							//echo $new_corp_query . "--" . mysqli_error($conn);
						}
					}
				} 
				else {
					echo "File was not uploaded!";
				}
		    } else {
		        echo "File is not uploaded.";
		        $uploadOk = 0;
		    }
		}
	}
?>