<?php
include("config.php");

html_head("Search Results");

include_once("lib/xmlDbConnection.class.php");

print "<body>";

include("header.html");

$id = $_GET['id'];

$args = array('host' => "bohr.library.emory.edu",
	      'port' => "8080",
	      'db' => "lincoln",
	      'dbtype' => "exist",
	        'debug' => false);
$exist = new xmlDbConnection($args);

// search terms
$kw = $_GET["keyword"];
$title = $_GET["title"];
$author = $_GET["author"];
$date = $_GET["date"];
$place= $_GET["place"];
$mode= $_GET["mode"];

// arrays are not needed for eXist searching
$kwarray = processterms($kw);
$ttlarray = processterms($title);
$autharray = processterms($author);
$darray = processterms($date);
$plarray = processterms($place);

/* tamino query
$declare ='declare namespace tf="http://namespaces.softwareag.com/tamino/TaminoFunction" ';
$for = ' for $a in input()/TEI.2/:text/body/div1';
$let = 'let $b := $a/head/bibl';
*/

$for = 'for $a in //div1';
$let = 'let $b := $a/head/bibl';

$conditions = array();

//Working queries. format: $where = "where tf:containsText(\$a, '$kw')";
if ($kw) {
    if ($mode == "exact") {
	array_push($conditions, "contains(., '$kw')"); 
      //        array_push($conditions, "tf:containsText(\$a, '$kw')");
	//     array_push($conditions, "contains(\$a, '$kw')");
    }
    if ($mode == "synonym") {
      // dictionary for synonyms not set up in tamino; not supported in exist (AFAIK)
      //        array_push($conditions, "tf:containsText(\$a, tf:synonym('$kw'))");
    }
    else {
	array_push($conditions, ". &= '$kw'"); 
      //       array_push($conditions, "near(\$a, '$kw')");
	// eXist doesn't do a phonetic search  (AFAIK)
      /*   foreach ($kwarray as $k){
            $term = ($mode == "phonetic") ? "tf:phonetic('$k')" : "'$k'";
            array_push($conditions, "tf:containsText(\$a, $term)");
        }
      */
    }
}
if ($title) {
  array_push($conditions, "head/bibl/title &= '$title'");
//array_push($conditions, "where contains(\$b/title'$t']");
  /*        foreach ($ttlarray as $t){
        array_push($conditions, "tf:containsText(\$b/title, '$t') ");
	} */
}
if ($author) {
  array_push($conditions, "head/bibl/author &= '$author'");
  /*        foreach ($autharray as $a){
        array_push($conditions, "tf:containsText(\$b/author, '$a') ");
    }
  */
}
if ($date) {
  array_push($conditions, "head/bibl/date &= '$date'");
  /*        foreach ($darray as $d){    
            array_push ($conditions, "tf:containsText(\$b/date, '$d') ");
    }
  */
}
if ($place) {
  array_push($conditions, "head/bibl/pubPlace &= '$place'");
  /*
    foreach ($plarray as $p){
    array_push ($conditions, "tf:containsText(\$b/pubPlace, '$p') ");
    }
  */
}

// create the filter from conditions
foreach ($conditions as $c) {
    if ($c == $conditions[0]) {
	$filter = "[$c";
    } else {
        $filter .= " and $c";
    }
}
$filter .= "]";

//have to take each individual keyword into an array.
$myterms = array();
if ($kw) {$myterms = array_merge($myterms, $kwarray); }
if ($title) {$myterms = array_merge($myterms, $ttlarray); }
if ($author) {$myterms = array_merge($myterms, $autharray); }
if ($date) {$myterms = array_merge($myterms, $darray); }
if ($place) {$myterms = array_merge($myterms, $plarray); }


/* tamino query
 $return = ' return <div1> {$a/@id} {$b} ' . "<total>{count($for $let $where return \$a)}</total>" . '</div1>';
   $sort = 'sort by (author)'; */
$return = 'return <div1>{$a/@id}{$b}<total>' . "{count($for $let $where return \$a)}</total></div1>";
$order = 'order by $b/author'; 

// tamino $query = "$declare $for $let $where $return $sort";
//$query = "$for $let $order $where $return";
$query = "$for$filter $let $return";
$exist->xquery($query); 
$exist->getCursor();

$xsl_file = "search.xsl";


// pass search terms into xslt as parameters 
// (xslt passes on terms to browse page for highlighting)
$term_list = implode("|", $myterms);
$xsl_params = array("term_list"  => $term_list);



print '<div class="content">';
print "<p>Found " . $exist->count . " matching sermon";
if ($exist->count != 1) { print "s"; }
print ".</p>";
$exist->xslTransform($xsl_file, $xsl_params);
$exist->highlightInfo($myterms);
$exist->printResult($myterms);
print '</div>';

//Function that takes multiple terms separated by white spaces and puts them into an array
function processterms ($str) {
// clean up input so explode will work properly
    $str = preg_replace("/\s+/", " ", $str);  // multiple white spaces become one space
    $str = preg_replace("/\s$/", "", $str);	// ending white space is removed
    $str = preg_replace("/^\s/", "", $str);  //beginning space is removed
    $terms = explode(" ", $str);    // multiple search terms, divided by spaces
    return $terms;
}


?>

</body>
</html>
