<?php
include("config.php");
include_once("lib/xmlDbConnection.class.php");

$id = $_GET['id'];
$terms = $_GET['term'];

// use tamino settings from config file
$args = $tamino_args;
$args{"debug"} = false;

$tamino = new xmlDbConnection($args);

$query = 'for $div in input()/TEI.2/:text/body/div1[@id  = "' . $id . '"]
let $hdr := root($div)/TEI.2/teiHeader
return <result>{$hdr}{$div}</result>';
$xsl_file = "sermon.xsl";

$tamino->xquery($query);

// metadata information for cataloging
$header_xsl1 = "teiheader-dc.xsl";
$header_xsl2 = "dc-htmldc.xsl";

$tamino->xslTransform($header_xsl1);
$tamino->xslTransformResult($header_xsl2);

$title = $tamino->findNode("bibl/title");
$t = explode(":", $title, 2);
$title = $t[0];
$subtitle = $t[1];
$author = $tamino->findNode("author");
$a = explode(",", $author, 2);
$author = $a[0];

html_head("Sermon : $author - $title", "sermons.css", false);
/* print "<html>
         <head>
            <title>The Martyred President : Sermon : $author - $title</title>
             <link rel='stylesheet' type='text/css' href='sermons.css'>
	     "; */
$tamino->printResult();
print "          </head>";

print "\n<body>";
include("header.html");


print '<div class="content">';
$tamino->highlightInfo($terms);

$tamino->xslTransform($xsl_file);
$tamino->printResult($terms);

print "<p class='clear'>&nbsp;</p>";
print "</div>";

include("footer.html");
?> 
   
  </div>
   
</body>
</html>
