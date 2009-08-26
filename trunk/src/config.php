<?php

$in_production="false";

/* Configuration settings for entire site */
// set level of php error reporting --  ONLY display errors
// (will hide ugly warnings if databse goes offline/is unreachable)
if ($in_production) {
  error_reporting(E_ERROR);	// for production
 } else {
  error_reporting(E_ERROR | E_PARSE);    // for development
 }

/* exist settings */
if($in_production) {
$basedir = "/home/httpd/html/beck/lincoln";
$server = "bohr.library.emory.edu";
$base_path = "/lincoln/";
$base_url = "http://beck.library.emory.edu$base_path/";
 } else {
//development
$basedir = "/home/ahickco/public_html/lincoln";
$server = "wilson.library.emory.edu";
$base_path = "/~ahickco/lincoln/";
$base_url = "http://$server$base_path/";
 }

// root directory and url where the website resides
// production version
/* $basedir = "/home/httpd/html/beck/lincoln";
$server = "beck.library.emory.edu";
$base_path = "/lincoln";
$base_url = "http://$server$base_path/";
*/

// add basedir to the php include path (for header/footer files and lib directory)
set_include_path(get_include_path() . ":" . $basedir . ":" . "$basedir/lib" . ":" . "$basedir/xslt");

//shorthand for link to main css file
$cssfile = "web/css/lincoln.css";
$csslink = "<link rel='stylesheet' type='text/css' href='$base_url/$cssfile'>";

$port = "8080";
$db = "lincoln";

$exist_args = array('host'   => $server,
	      	    'port'   => $port,
		    'db'     => $db,
		    'dbtype' => "exist");





/* function to print html header in all php files */
// lincoln.css is default css file
// finish option - hack to allow including DC metadata for sermons in the header
function html_head ($pagetitle, $pagecss = "web/css/lincoln.css", $finish = true) {
    print "<html>
            <head>
                <title>The Martyred President : $pagetitle</title>
                <meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">
                <link rel=\"stylesheet\" type=\"text/css\" href=\"$pagecss\">
		<link rel=\"shortcut icon\" href=\"images/lincoln.ico\">";
    if ($finish) print "\n</head>";
}
?>

