<?php
include("config.php");
include_once("lib/xmlDbConnection.class.php");

$id = $_GET['id'];
$terms = $_GET['term'];

// use tamino settings from config file
$args = $tamino_args;
$args{"debug"} = false;

$tamino = new xmlDbConnection($args);

$query = 'for $div in input()/TEI.2/:text/body/div1,
 $fig in $div//figure[@entity  = "' . $id . '"]
return <div>{$div/@id}{$div/head}{$fig}
<siblings>{for $f in $div//figure return <figure>{$f/@entity}</figure>}</siblings>
</div>';

$xsl_file = "page.xsl";

$tamino->xquery($query);

$fig = $tamino->findNode("figDesc");

html_head("Page Image : $fig");
/* print "<html>
         <head>
            <title>The Martyred President : Page Image : $fig</title>
             <link rel='stylesheet' type='text/css' href='lincoln.css'>
	     "; */
$tamino->printResult();
print "          </head>";

print "\n<body>";
include("header.html");


print '<div class="content">'; 
$tamino->xslTransform($xsl_file);
$tamino->printResult($terms);

print "<p class='clear'>&nbsp;</p>";
print "</div>";

include("footer.html");
?> 
   
  </div>
   
</body>
</html>
