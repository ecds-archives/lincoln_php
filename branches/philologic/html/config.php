<?php


/* Configuration settings for entire site */

// set level of php error reporting -- turn off warnings when in production
//error_reporting(E_ERROR | E_PARSE); 



/* tamino settings common to all pages */

$tamino_server = "vip.library.emory.edu";
$tamino_db = "LINCOLN";
$tamino_coll= "sermons";

/* function to print html header in all php files */
function html_head ($pagetitle, $pagecss = "lincoln.css") {     // lincoln.css is default css file
    print "<html>
            <head>
                <title>Lincoln Sermons : $pagetitle</title>
                <meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">
                <link rel=\"stylesheet\" type=\"text/css\" href=\"$pagecss\">
             </head>";
}
?>

