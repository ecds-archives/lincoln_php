<?php
include("config.php");

html_head("Search Results");

include_once("lib/taminoConnection.class.php");

print "<body>";

include("header.html");

$id = $_GET['id'];

$args = array('host' => "vip.library.emory.edu",
		'db' => "LINCOLN",
	      	'coll' => 'sermons',
	        'debug' => false );
$tamino = new taminoConnection($args);

// search terms
$kw = $_GET["keyword"];
$title = $_GET["title"];
$author = $_GET["author"];
$date = $_GET["date"];
$place= $_GET["place"];

$declare ='declare namespace tf="http://namespaces.softwareag.com/tamino/TaminoFunction" ';
$for = ' for $a in input()/TEI.2/:text/body/div1';
$let = 'let $b :=$a/head/bibl';

//Working queries. format: $where = "where tf:containsText(\$a, '$kw')";
if ($kw) { $where = "where tf:containsText(\$a, '$kw')";}
if ($title) { $where = "where tf:containsText(\$b/title, '$title') ";}
if ($author) { $where = "where tf:containsText(\$b/author, '$author') ";}
if ($date) {$where = "where tf:containsText(\$b/date, '$date') ";}
if ($place) {$where = "where tf:containsText(\$b/pubPlace, '$place') ";}


//have to take each individual keyword into an array.



$return = ' return <div1> {$a/@id} {$b} ' . "<total>{count($for $where return \$a)}</total>" . '</div1>';
$sort = 'sort by (author)';

$query = "$declare $for $let $where $return $sort";
$tamino->xquery($query); 

$xsl_file = "search.xsl";

print '<div class="content">'; 
$tamino->xslTransform($xsl_file);
$tamino->printResult();
print '</div>';

?>

</body>
</html>