<?php
include("config.php");
include_once("lib/xmlDbConnection.class.php");

$id = $_GET['id'];
$terms = $_GET['term'];

// use tamino settings from config file
$args = $exist_args;
$args{"debug"} = false;

$xmldb = new xmlDbConnection($args);

$query = 'for $div in /TEI.2/text/body/div1,
 $fig in $div//figure[@entity  = "' . $id . '"]
return <div>{$div/@id}{$div/head}{$fig}
<siblings>{for $f in $div//figure return <figure>{$f/@entity}</figure>}</siblings>
</div>';

$xsl_file = "xslt/page.xsl";

$xmldb->xquery($query);

$fig = $xmldb->findNode("figDesc");

html_head("Page Image : $fig");
/* print "<html>
         <head>
            <title>The Martyred President : Page Image : $fig</title>
             <link rel='stylesheet' type='text/css' href='web/css/lincoln.css'>
	     "; */
$xmldb->printResult();
print "          </head>";

print "\n<body>";
include("web/html/header.html");


print '<div class="content">'; 
$xmldb->xslTransform($xsl_file);
$xmldb->printResult($terms);

print "<p class='clear'>&nbsp;</p>";
print "</div>";

include("web/html/footer.html");
?> 
   
  </div>
   
</body>
</html>
