<html>
  <head>
    <link rel="stylesheet" type="text/css" href="sermons.css"> 
    <title>Lincoln Sermons</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  </head>
<body>

<?php

include_once("lib/taminoConnection.class.php");

include("header.html");

$id = $_GET['id'];

$args = array('host' => "vip.library.emory.edu",
		'db' => "LINCOLN",
	      	'coll' => 'sermons',
	        'debug' => false );
$tamino = new taminoConnection($args);

$query = 'for $div in input()/TEI.2/:text/body/div1
where $div/@id = "' . $id . '"
return $div';
$xsl_file = "sermon.xsl";

$rval = $tamino->xquery($query);
if ($rval) {       // tamino Error code (0 = success)
  print "<p>Error: failed to retrieve contents.<br>";
  print "(Tamino error code $rval)</p>";
  exit();
} 


print '<div class="content">  
          <h2>Sermon</h2>';
print "<hr>";

$tamino->xslTransform($xsl_file);
$tamino->printResult();

print "<hr>";

include("footer.html");
?> 
   
  </div>
   
</body>
</html>
