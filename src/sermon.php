<?php
include("config.php");
include_once("lib/xmlDbConnection.class.php");

$id = $_GET['id'];
$terms = $_GET['keyword'];

// use tamino settings from config file
//$args = $exist_args;
$exist_args{"debug"} = false;

$xmldb = new xmlDbConnection($exist_args);

$for = 'for $div in /TEI.2/text/body/div1[@id  = "' . $id . '"]';
if ($terms != '') {$for .= "[. |= \"$terms\"]";}
$let = 'let $hdr := root($div)/TEI.2/teiHeader';
$return = 'return <result>{$hdr}{$div}</result>';
$xsl_file = "xslt/sermon.xsl";

$query = "declare option exist:serialize 'highlight-matches=all';";
$query .= "$for $let $return";

$xmldb->xquery($query);

// metadata information for cataloging
$header_xsl1 = "xslt/teiheader-dc.xsl";
$header_xsl2 = "xslt/dc-htmldc.xsl";

$xmldb->xslTransform($header_xsl1);
$xmldb->xslTransformResult($header_xsl2);

$title = $xmldb->findNode("bibl/title");
$t = explode(":", $title, 2);
$title = $t[0];
$subtitle = $t[1];
$author = $xmldb->findNode("author");
$a = explode(",", $author, 2);
$author = $a[0];

html_head("Sermon : $author - $title", "web/css/sermons.css", false);
/* print "<html>
         <head>
            <title>The Martyred President : Sermon : $author - $title</title>
             <link rel='stylesheet' type='text/css' href='sermons.css'>
	     "; */
$xmldb->printResult();
print "          </head>";

print "\n<body>";
include("web/html/header.html");


print '<div class="content">';
/*$xmldb->highlightInfo($terms);*/ //using exist highlight

$xmldb->xslTransform($xsl_file);
$xmldb->printResult();

print "<p class='clear'>&nbsp;</p>";
print "</div>";

include("web/html/footer.html");
include("web/html/google-tracklinc.xml");
?> 
   
  </div>
   
</body>
</html>
