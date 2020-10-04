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
			$output .= '<div class="' . $class . ' alert-dismissible">';
			$output .= '<ul>';
			foreach ((array) $_SESSION['message_' . $type] as $message) {
				$output .= '<li>' . $message . '</li>
				<a href="#" class="close" >&times;</a>';
			}
			$output .= '</ul>';
			$output .= '</div>';
			unset($_SESSION['message_' . $type]);
		}
	}
	
	return $output;
}

/**
 * HTML rendering for sorting arrows.
 *
 * @param int $order The order to sort.
 * @param int $sort_order The GET parameter 'sort_order'.
 * @param int $sort_way The GET parameter 'sort_way'.
 * @return string
 */
function getOrderArrows($order, $sort_order, $sort_way) {
	$markup = '<span class="order">';
	$markup .= '<a' . ($sort_order == $order && $sort_way == 1 ? ' class="active"' : '') . ' href="' . getUrl(['sort_order' => $order, 'sort_way' => 1]) . '">▲</a>';
	$markup .= '<a' . ($sort_order == $order && $sort_way == 2 ? ' class="active"' : '') . ' href="' . getUrl(['sort_order' => $order, 'sort_way' => 2]) . '">▼</a>';
	$markup .= '</span>';
	return $markup;
}

/**
 * Replacement of GET parameters for an url.
 *
 * @param array $pairs An array of parameters.
 * @param array $url The url to change (default: current url).
 * @return string The new url.
 */
function getUrl($pairs, $url = NULL) {
	if (!$url) {
		$url = $_SERVER['QUERY_STRING'];
	}
	parse_str($url, $params);
	return '?' . http_build_query(array_merge($params, $pairs), '_', '&amp;');
}


