<?php

/*	$app->get('/clientindex', function ($request, $response, $args) {
	    $file = '../public/index.html';
	    if (file_exists($file)) {
	        return $response->write(file_get_contents($file));
	    } else {
	        throw new \Slim\Exception\NotFoundException($request, $response);
	    }
	})*/

$locDir = "/home/pi/local/";

if ($_SERVER["REQUEST_METHOD"] == "GET") {
	var_dump($_GET);

	// basic headers
	header("Content-type: image/png");
	header("Expires: Mon, 1 Jan 2099 05:00:00 GMT");
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
	header("Cache-Control: no-store, no-cache, must-revalidate");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");
	 
	// get the file name
	$file= $locDir . @$_GET['file'];
	 
	// get the size for content length
	$size= filesize($file);
	header("Content-Length: $size bytes");
	 
	// output the file contents
	echo readfile($file);
	
}



?>