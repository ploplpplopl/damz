<?php

function debug($chaine,$bool) {
	if ($bool) {
		if (is_array($chaine)) {
			echo "\n<pre>"; 
			print_r($chaine);
			echo "</pre>\n";
		} else {
			echo "\n<pre>".$chaine."</pre>\n";
		}
	}
}

function displayMessage($errors = NULL) {
	$messageTypes = [
		'status' => 'alert alert-success',
		'warning' => 'alert alert-warning',
		'error' => 'alert alert-danger',
	];
	
	if (!empty($errors)) {
		if (!empty($_SESSION['message_error'])) {
			$_SESSION['message_error'] = array_merge((array) $_SESSION['message_error'], $errors);
		}
		else {
			$_SESSION['message_error'] = $errors;
		}
	}
	
	$output = '';
	foreach ($messageTypes as $type => $class) {
		if (isset($_SESSION['message_' . $type])) {
			$output .= '<div class="' . $class . '">';
			$output .= '<ul>';
			foreach ((array) $_SESSION['message_' . $type] as $message) {
				$output .= '<li>' . $message . '</li>';
			}
			$output .= '</ul>';
			$output .= '</div>';
			unset($_SESSION['message_' . $type]);
		}
	}
	
	return $output;
}
