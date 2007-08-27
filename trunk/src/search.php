<?php

include("config.php");
include_once("lib/xmlDbConnection.class.php");

$id = $_GET['id'];
// use settings from config file
//$args = $exist_args;
$exist_args{"debug"} = true;

$xmldb = new xmlDbConnection($exist_args);

// search terms
$kw = $_GET["keyword"];
$title = $_GET["title"];
$author = $_GET["author"];
$date = $_GET["date"];
$place= $_GET["place"];
$kwic = $_GET["kwic"];		// is this a kwic search or not? defaults to no
$position = $_GET["pos"];  // position (i.e, cursor)
$maxdisplay = $_GET["max"];  // maximum  # results to display

// set some defaults
// if no position is specified, start at 1
if ($position == '') $position = 1;
// set a default maxdisplay
if ($maxdisplay == '') $maxdisplay = 20;       // what is a reasonable default?

$doctitle = "Search Results";
$doctitle .= ($kwic == "true" ? " - Keyword in Context" : "");

html_head($doctitle);
print "<body>";
include("header.html");

$options = array();
if ($kw) 
  array_push($options, ". &= \"$kw\"");
if ($title)
  array_push($options, ".//head/bibl/title &= '$title'");
if ($author)
  array_push($options, ".//head/bibl/author &= '$auth'");
if ($date)
  array_push($options, ".//head/bibl/date &= '$date'");
if ($place)
  array_push($options, ".//head/bibl/pubPlace &= '$place'");

// there must be at least one search parameter for this to work
if (count($options)) {

  $searchfilter = "[" . implode(" and ", $options) . "]"; 
  //print("DEBUG: Searchfilter is $searchfilter");

  $query = "for \$a in /TEI.2//div1$searchfilter
let \$t := \$a/head
let \$matchcount := text:match-count(\$a)
order by \$matchcount descending
return <item>{\$a/@id}";
  if ($kw)	// only count matches for keyword searches
    $query .= "<hits>{\$matchcount}</hits>";
  $query .= "
  {\$t}";
  $query .= "</item>";

$xsl_file = "xsl/exist-search.xsl";
// only execute the query if there are search terms
if (count($options)) {
$xmldb->xquery($query, $position, $maxdisplay); 
$xsl_params = array('mode' => "search", 'keyword' => $kw, 'title' => $title, 'author' => $author, 'date' => $date,  'max' => $maxdisplay);
 }

  print "<p><b>Search results for texts where:</b></p>
 <ul class='searchopts'>";
  if ($kw) 
    print "<li>document contains keywords '$kw'</li>";
  if ($title)
    print "<li>title matches '$title'</li>";
  if ($author)
    print "<li>author matches '$auth'</li>";
  if ($date)
    print "<li>date matches '$date'</li>";
include("footer.html");

  print "</ul>";
  
  /*  if ($xmldb->count == 0) {
    print "<p><b>No matches found.</b>
You may want to broaden your search or consult the search tips for suggestions.</p>\n";
    include("searchform.php");
    }*/
  
  $xmldb->xslTransform($xsl_file, $xsl_params);
  $xmldb->printResult();
  
} else {
  // no search terms - handle gracefully  
  print "<p><b>Error!</b> No search terms specified.</p>";
}

?>

</body>
</html>
