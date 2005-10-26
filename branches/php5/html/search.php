<?php
include("config.php");

include_once("lib/xmlDbConnection.class.php");


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
$docid = $_GET["id"];		// limit keyword search to one sermon
$kwic = $_GET["kwic"];		// is this a kwic search or not? defaults to not
$position = $_GET["pos"];  // position (i.e, cursor)
$maxdisplay = $_GET["max"];  // maximum  # results to display

// set some defaults
if ($kwic == '') $kwic = "false";
// if no position is specified, start at 1
if ($position == '') $position = 1;
// set a default maxdisplay
if ($maxdisplay == '') $maxdisplay = 10;       // what is a reasonable default?

$kwarray = processterms($kw);
$ttlarray=processterms($title);
$autharray=processterms($author);
$darray=processterms($date);
$plarray=processterms($place);

$doctitle = "Search Results";
$doctitle .= ($kwic == "true" ? " - Keyword in Context" : "");

html_head($doctitle);
print "<body>";
include("header.html");



$declare ='declare namespace tf="http://namespaces.softwareag.com/tamino/TaminoFunction" 
declare namespace xs="http://www.w3.org/2001/XMLSchema"';
$for = ' for $a in input()/TEI.2/:text/body/div1';
$let = 'let $b := $a/head/bibl 
	let $myref := tf:createTextReference($a//p, "' . $kw . '")';

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
$return = ' return <div1> {$a/@id} {$b} <count>{count($ref)}</count>';

// if this is a keyword in context search, get context nodes
// return previous pagebreak (get closest by max of previous sibling pb & previous p/pb)
$return .= ($kwic == "true" ? '<context><page>{tf:highlight($a//p[tf:containsText(.,"' . $kw . '")], $myref , "MATCH")}</page></context>' : ''); 
$return .= '</div1>';
//$order = 'order by $b/author'; 
// order results by relevance


/* let $pbsib := $p/preceding-sibling::pb/@n
 let $psib := $p/preceding-sibling::p/pb/@n
 let $seq := ($pbsib, $psib)
let $pb := max($seq)  
*/

//$sort = 'sort by (author)';
$sort = 'sort by (xs:int(count) descending)';

$countquery = "$declare <total>{count($for $let $where return \$a)}</total>";
$query = "$declare $for $let $ref_let $where $return $sort";

// first, get the count for number of matching sermons
$tamino->xquery($countquery);
$total = $tamino->findNode("total");

$tamino->xquery($query, $position, $maxdisplay); 
$tamino->getCursor();

$xsl_file = "search.xsl";
$kwic_xsl = "kwic.xsl";
$kwic1_xsl = "kwic-towords.xsl";
$kwic2_xsl = "kwic-words.xsl";

// pass search terms into xslt as parameters 
// (xslt passes on terms to browse page for highlighting)
$term_list = implode("|", $myterms);
$xsl_params = array("term_list"  => $term_list);



print '<div class="content">';
print "<h2 align='center'>" . ($kwic == "true" ? "Keyword in Context " : "") . "Search Results</h2>";
if (!($docid)) {
  // only display # of results if we are looking at more than one document
  print "<p align='center'>Found <b>" . $total . "</b> matching sermon";
  if ($exist->count != 1) { print "s"; }
  print ". Results sorted by relevance.</p>"; 
}

$myopts = "keyword=$kw&title=$title&author=$author&date=$date&place=$place&mode=$mode";
// based on KWIC mode, set options for search link & transform result appropriately
switch ($kwic) {
     case "true": $altopts = "$myopts&pos=$position&max=$maxdisplay&kwic=false";
 	    	$mylink = "Summary"; 
	        $myopts .= "&kwic=true";	// preserve for result links
		$tamino->xslTransform($kwic1_xsl);
		//				print "DEBUG: went through one transform.";
		//		  $tamino->displayXML(1);
		$tamino->xslTransformResult($kwic2_xsl);
		//		print "DEBUG: went through second transform.";
		//  $tamino->displayXML(1);
		$xsl_params{"mode"} = "kwic";
		$tamino->xslTransformResult($xsl_file, $xsl_params);
		break;
     case "false": $altopts .= "$myopts&pos=$position&max=$maxdisplay&kwic=true";
		$mylink = "Keyword in Context"; 
		$xsl_params{"selflink"} = "search.php?$myopts";
		$tamino->xslTransform($xsl_file, $xsl_params);
		break;
}

$tamino->highlightInfo($myterms);
$tamino->count = $total;	// set tamino count from first (count) query, so resultLinks will work
$rlinks = $tamino->resultLinks("search.php?$myopts", $position, $maxdisplay);
print $rlinks;
print "<p>View <a href='search.php?$altopts'>$mylink</a> search results. </p>";
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
