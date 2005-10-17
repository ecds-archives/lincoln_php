<?php
include("config.php");
include_once("lib/xmlDbConnection.class.php");

$id = $_GET['id'];
$terms = $_GET['term'];

$args = array('host' => "vip.library.emory.edu",
		'db' => "LINCOLN",
	      	'coll' => 'sermons',
	        'debug' => false);
$tamino = new xmlDbConnection($args);

$query = 'for $div in input()/TEI.2/:text/body/div1[@id  = "' . $id . '"]
let $hdr := root($div)/TEI.2/teiHeader
return <result>{$hdr}{$div}</result>';
$xsl_file = "sermon.xsl";

$rval = $tamino->xquery($query);
if ($rval) {       // tamino Error code (0 = success)
  print "<p>Error: failed to retrieve contents.<br>";
  print "(Tamino error code $rval)</p>";
  exit();
} 

$tamino->xquery($query);

// metadata information for cataloging
$header_xsl1 = "teiheader-dc.xsl";
$header_xsl2 = "dc-htmldc.xsl";

$tamino->xslTransform($header_xsl1);
//$tamino->displayXML(1);
$tamino->xslTransformResult($header_xsl2);
//$tamino->displayXML(1);


print "<html>
         <head>
            <title>The Martyred President : Sermon</title>
             <link rel='stylesheet' type='text/css' href='sermons.css'>
";
$tamino->printResult();
print "          </head>";

print "\n<body>";
include("header.html");


print '<div class="content">
          <h2>Sermon</h2>';
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
