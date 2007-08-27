<?php
include("config.php");
include_once("lib/xmlDbConnection.class.php");

$id = $_GET['id'];
$terms = $_GET['term'];

// use tamino settings from config file
//$args = $exist_args;
$exist_args{"debug"} = true;

$xmldb = new xmlDbConnection($exist_args);

$query = 'for $div in /TEI.2/text/body/div1[@id  = "' . $id . '"]
let $hdr := root($div)/TEI.2/teiHeader
return <result>{$hdr}{$div}</result>';
$xsl_file = "xsl/sermon.xsl";

$xmldb->xquery($query);

// metadata information for cataloging
$header_xsl1 = "xsl/teiheader-dc.xsl";
$header_xsl2 = "xsl/dc-htmldc.xsl";

$xmldb->xslTransform($header_xsl1);
$xmldb->xslTransformResult($header_xsl2);

$title = $xmldb->findNode("bibl/title");
$t = explode(":", $title, 2);
$title = $t[0];
$subtitle = $t[1];
$author = $xmldb->findNode("author");
$a = explode(",", $author, 2);
$author = $a[0];

html_head("Sermon : $author - $title", "sermons.css", false);
/* print "<html>
         <head>
            <title>The Martyred President : Sermon : $author - $title</title>
             <link rel='stylesheet' type='text/css' href='sermons.css'>
	     "; */
$xmldb->printResult();
print "          </head>";

print "\n<body>";
include("header.html");


print '<div class="content">';
$xmldb->highlightInfo($terms);

$xmldb->xslTransform($xsl_file);
$xmldb->printResult($terms);

print "<p class='clear'>&nbsp;</p>";
print "</div>";

include("footer.html");
?> 
   
  </div>
   
</body>
</html>
