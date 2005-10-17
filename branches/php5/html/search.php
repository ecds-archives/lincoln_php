<?php
include("config.php");

html_head("Search Results");
include_once("lib/xmlDbConnection.class.php");

print "<body>";

include("header.html");

$id = $_GET['id'];

// use tamino settings from config file
$args = $tamino_args;
$args{"debug"} = false;

$tamino = new xmlDbConnection($args);

// search terms
$kw = $_GET["keyword"];
$title = $_GET["title"];
$author = $_GET["author"];
$date = $_GET["date"];
$place= $_GET["place"];
$mode= $_GET["mode"];


$kwarray = processterms($kw);
$ttlarray=processterms($title);
$autharray=processterms($author);
$darray=processterms($date);
$plarray=processterms($place);


$declare ='declare namespace tf="http://namespaces.softwareag.com/tamino/TaminoFunction" 
declare namespace xs="http://www.w3.org/2001/XMLSchema"';
$for = ' for $a in input()/TEI.2/:text/body/div1';
$let = 'let $b := $a/head/bibl ';

$conditions = array();

//Working queries. format: $where = "where tf:containsText(\$a, '$kw')";
if ($kw) {
    if ($mode == "exact") {
        array_push($conditions, "tf:containsText(\$a, '$kw')");
	$ref_let = "let \$ref := tf:createTextReference(\$a, '$kw') ";
    }
    if ($mode == "synonym") {
        array_push($conditions, "tf:containsText(\$a, tf:synonym('$kw'))");
    }
    else {
      // create a "near text" ref for counting matches
      $ref_let = 'let $ref := tf:createNearTextReference($a, 50, ';
        foreach ($kwarray as $k){
            $term = ($mode == "phonetic") ? "tf:phonetic('$k')" : "'$k'";
            array_push($conditions, "tf:containsText(\$a, $term)");
	    if ($k != $kwarray[0]) { $ref_let .= ", "; }	// any term but the first, add a comma
	    $ref_let .= '"' . $k . '"';	
		
        }
	$ref_let .= ') ';
    }
}
if ($title) {
        foreach ($ttlarray as $t){
        array_push($conditions, "tf:containsText(\$b/title, '$t') ");
    }
}
if ($author) {
        foreach ($autharray as $a){
        array_push($conditions, "tf:containsText(\$b/author, '$a') ");
    }
}
if ($date) {
        foreach ($darray as $d){    
            array_push ($conditions, "tf:containsText(\$b/date, '$d') ");
    }
}
if ($place) {
    foreach ($plarray as $p){
    array_push ($conditions, "tf:containsText(\$b/pubPlace, '$p') ");
    }
}
foreach ($conditions as $c) {
    if ($c == $conditions[0]) {
        $where= "where $c";
    } else {
        $where.= " and $c";
            }
}

//have to take each individual keyword into an array.
$myterms = array();
if ($kw) {$myterms = array_merge($myterms, $kwarray); }
if ($title) {$myterms = array_merge($myterms, $ttlarray); }
if ($author) {$myterms = array_merge($myterms, $autharray); }
if ($date) {$myterms = array_merge($myterms, $darray); }
if ($place) {$myterms = array_merge($myterms, $plarray); }


//$return = ' return <div1> {$a/@id} {$b} ' . "<total>{count($for $let $where return \$a)}</total>" . '</div1>';
// FIXME: still need to return count of # of matches within sermon
$return = ' return <div1> {$a/@id} {$b} <count>{count($ref)}</count></div1>';
//$sort = 'sort by (author)';
$sort = 'sort by (xs:int(count) descending)';

$countquery = "$declare <total>{count($for $let $where return \$a)}</total>";
$query = "$declare $for $let $ref_let $where $return $sort";

// first, get the count for number of matching sermons
$tamino->xquery($countquery);
$total = $tamino->findNode("total");

$tamino->xquery($query); 
$tamino->getCursor();

$xsl_file = "search.xsl";


// pass search terms into xslt as parameters 
// (xslt passes on terms to browse page for highlighting)
$term_list = implode("|", $myterms);
$xsl_params = array("term_list"  => $term_list);



print '<div class="content">';
print "<p>Found <b>$total</b> matching sermons.</p>";
$tamino->xslTransform($xsl_file, $xsl_params);
$tamino->highlightInfo($myterms);
$tamino->printResult($myterms);
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
