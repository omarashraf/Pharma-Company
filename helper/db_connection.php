<?php

	// Connection credentials
	$servername = "localhost";
	$username = "root";
	$password = "";

	// Create connection
	$conn = mysqli_connect($servername, $username, $password, 'pharmaceutical_company');


	// Sample query
	$sql = "SELECT * FROM doctors";
	$result = mysqli_query($conn, $sql);

	// Sample retrieving data from result query
	if (mysqli_num_rows($result) > 0) {
		while($row = mysqli_fetch_assoc($result)) {
			// represent data as needed
		}
	}
?>