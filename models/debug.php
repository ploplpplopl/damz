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

?>