<?php
	require_once "helper/formvalidator.php";
	require_once "helper/db_connection.php";
	require_once "helper/helper_functions.php";
	session_start();
?>

	<form action=<?php echo "presentation_questions.php?pres_id=" . $_GET["pres_id"]; ?> method="post" enctype="multipart/form-data">
		<input type="hidden" name="company_question_submitted" value="1"/>
		<label for="question">Question: </label>
	    <textarea name="question" id="question"></textarea> <br>
	    <label for="answerA">Answer A: </label>
	    <input type="text" name="answerA" id="answerA"> <br>
	    <label for="answerB">Answer B: </label>
	    <input type="text" name="answerB" id="answerB"> <br>
	    <label for="answerC">Answer C: </label>
	    <input type="text" name="answerC" id="answerC"> <br>
	    <label for="answerD">Answer D: </label>
	    <input type="text" name="answerD" id="answerD"> <br>
	    <label for="answerE">Answer E: </label>
	    <input type="text" name="answerE" id="answerE"> <br>
	    <label for="correctAnswer">Correct Answer: </label>
	    <select name="correctAnswer" id="correctAnswer">
	    	<option value="A">A</option>
	    	<option value="B">B</option>
	    	<option value="C">C</option>
	    	<option value="D">D</option>
	    	<option value="E">E</option>
	    </select> <br>
		<label for="score">Score: </label>
	    <input type="text" name="score" id="score"> <br>	    
	    <input type="submit" value="Next" name="next">
	    <input type="submit" value="Submit" name="submit">
	</form>

<?php
	if (isset($_POST["company_question_submitted"])) {

		// js check on the select option to match an existing answer
		$validator = new FormValidator();
		$validator->addValidation("question","req","Please fill in a question");
		$validator->addValidation("answerA","req","Please fill in answer A");
		$validator->addValidation("answerB","req","Please fill in answer B");
		$validator->addValidation("answerC","req","Please fill in answer C");
		$validator->addValidation("score","req","Please fill in score");

		if($validator->ValidateForm()) {
			$populate_questions = "INSERT INTO questions "
								. "(presentation_id, question, answer_a, answer_b, answer_c, "
								. "answer_d, answer_e, answer_correct, score)"
								. "VALUES ("
								. $_GET["pres_id"] . ", '"
								. $_POST["question"] . "', '"
								. $_POST["answerA"] . "', '"
								. $_POST["answerB"] . "', '"
								. $_POST["answerC"] . "', '"
								. $_POST["answerD"] . "', '"
								. $_POST["answerE"] . "', '"
								. $_POST["correctAnswer"] . "', "
								. $_POST["score"]
								. ")";
			if (mysqli_query($conn, $populate_questions)) {
				// for debugging
				echo "inserted";
			}
			else {
				echo $populate_questions . "--" . mysqli_error($conn);
			}
		}

		if (isset($_POST["submit"])) {
			redirect("corporate_homepage.php", false);
		}
	}
?>