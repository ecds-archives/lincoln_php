<?php


/* Configuration settings for entire site */

// set level of php error reporting -- turn off warnings when in production
//error_reporting(E_ERROR | E_PARSE); 



/* tamino settings common to all pages */

/* exist settings  */
$config{"server"} = "vip.library.emory.edu";
$config{"db"} = "LINCOLN";
$config{"coll"} = "sermons";

// base settings for all connections to exist
$tamino_args = array('host'   => $config{"server"},
 		    'db'     => $config{"db"},
		     'coll'   => $config{"coll"});


/* function to print html header in all php files */
function html_head ($pagetitle, $pagecss = "lincoln.css") {     // lincoln.css is default css file
    print "<html>
            <head>
                <title>The Martyred President : $pagetitle</title>
                <meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">
                <link rel=\"stylesheet\" type=\"text/css\" href=\"$pagecss\">
		<link rel=\"shortcut icon\" href=\"images/lincoln.ico\">
             </head>";
}
?>

