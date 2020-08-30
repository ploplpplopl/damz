<?php

function debug($chaine, $bool) {
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

// Raccourci de var_dump() + ajout de tags <pre>
function vd() {
	echo '<pre>';
	foreach (func_get_args() as $arg) {
		var_dump($arg);
	}
	echo '</pre>';
}

/**
 * Affichage des messages utilisateur.
 *
 * @param array $errors Tableau d'erreurs ajoutées aux erreurs en session.
 * @return string Le markup des messages.
 */
function displayMessage($errors = NULL) {
	$messageTypes = [
		'status' => 'alert alert-success',
		'warning' => 'alert alert-warning',
		'error' => 'alert alert-danger',
	];
	
	// merge $_SESSION['message_error'] to $errors if needed
	if (!empty($errors)) {
		if (!empty($_SESSION['message_error'])) {
			$_SESSION['message_error'] = array_merge((array) $_SESSION['message_error'], $errors);
		}
		else {
			$_SESSION['message_error'] = $errors;
		}
	}
	
	// display errors
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

function getUrl($pairs){
	$QS = $_SERVER['QUERY_STRING'];
	$href = '?';
	if ($QS){
		parse_str($QS, $params);
		foreach ($pairs as $k => $v){
			// Suppression du paramètre si existant.
			if (isset($params[$k]))
				unset($params[$k]);
		}
		foreach ($params as $key => $val){
			if (is_array($val)){
				foreach ($val as $val2)
					$href .= $key.'[]='.$val2.'&amp;';
			} else {
				$href .= $key.'='.$val.'&amp;';
			}
		}
	}
	foreach ($pairs as $k => $v){
		$href .= $k.'='.$v.'&amp;';
	}
	return trim($href, '&amp;');
}


