<?php

include_once("lib/taminoConnection.class.php");

include("header.html");

$args = array('host' => "vip.library.emory.edu",
		'db' => "LINCOLN",
	      	'coll' => 'sermons',
	        'debug' => false );
$tamino = new taminoConnection($args);

/*
$query = 'for $a in input()/TEI.2/:text/body/div1 
let $bibl := $a/head/bibl  
return <div> {$a/@id} {$bibl} </div> sort by (@id)';
*/
$query = 'for $a in input()/TEI.2
let $auth := $a/teiHeader/fileDesc/titleStmt/author
let $body := $a/:text/body
return <div> {$auth}
{for $div1 in $body/div1 return <div1>{$div1/@id}{$div1/head/bibl}</div1> } </div> sort by (author)'; 
$xsl_file = "contents.xsl";  

$rval = $tamino->xquery($query);
if ($rval) {       // tamino Error code (0 = success)
  print "<p>Error: failed to retrieve contents.<br>";
  print "(Tamino error code $rval)</p>";
  exit();
} 


print '<div class="content">  
          <h2>Contents</h2>';
print "<hr>";
$xsl_file = "contents.xsl";
$tamino->xslTransform($xsl_file); 
$tamino->printResult(); 

print "<hr>"; 
print "</div>";

include("footer.html");
?> 
   
  </div>
   
</body>
</html>
