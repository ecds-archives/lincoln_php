

<?php
include("config.php");
html_head("Sermon", "sermons.css");

include_once("lib/taminoConnection.class.php");

print "<body>";

include("header.html");

$id = $_GET['id'];
$terms = $_GET['term'];

$args = array('host' => "vip.library.emory.edu",
		'db' => "LINCOLN",
	      	'coll' => 'sermons',
	        'debug' => false);
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
//print "<hr>";
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
