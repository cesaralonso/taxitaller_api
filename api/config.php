<?php
if ($_SERVER['HTTP_HOST'] == "localhost" or $_SERVER['HTTP_HOST'] == "127.0.0.1") {
	define('DB_USERNAME', 'root');
	define('DB_PASSWORD', '');
	define('DB_HOST', 'localhost');
	define('DB_NAME', 'taxytaller');
 }else {
	define('DB_USERNAME', 'cesarweb_taxy');
	define('DB_PASSWORD', '+D7MKHzsdTri');
	define('DB_HOST', 'localhost');
	define('DB_NAME', 'cesarweb_taxytaller');
}
?>
