<?php

	// redirect to a new page
	function redirect($url, $permanent = false) {
	    header('Location: ' . $url, true, $permanent ? 301 : 302);
	    exit();
	}
?>