

<?php
include("config.php");
html_head("Sermon", "sermons.css");

include_once("lib/xmlDbConnection.class.php");

print "<body>";

include("header.html");

$id = $_GET['id'];
$terms = $_GET['term'];

$args = array('host' => "bohr.library.emory.edu",
	      'port' => "8080",
	      'db' => "lincoln",
	      'dbtype' => "exist",
	      'debug' => false);
$exist = new xmlDbConnection($args);

/* tamino query
 $query = 'for $div in input()/TEI.2/:text/body/div1
where $div/@id = "' . $id . '"
return $div';
*/
$query = 'for $div in //div1[@id = "' . $id . '"] return $div';
$xsl_file = "sermon.xsl";

$exist->xquery($query);

print '<div class="content">
          <h2>Sermon</h2>';
$exist->highlightInfo($terms);

$exist->xslTransform($xsl_file);
$exist->printResult($terms);

print "<p class='clear'>&nbsp;</p>";
print "</div>";

include("footer.html");
?> 
   
  </div>
   
</body>
</html>
