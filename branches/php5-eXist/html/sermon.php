

<?php
include("config.php");
html_head("Sermon", "sermons.css");

include_once("lib/xmlDbConnection.class.php");

print "<body>";

include("header.html");

$id = $_GET['id'];
$terms = $_GET['term'];

$args = array('host' => "vip.library.emory.edu",
		'db' => "LINCOLN",
	      	'coll' => 'sermons',
	        'debug' => false);
$tamino = new xmlDbConnection($args);

$query = 'for $div in input()/TEI.2/:text/body/div1
where $div/@id = "' . $id . '"
return $div';
$xsl_file = "sermon.xsl";

$tamino->xquery($query);

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
