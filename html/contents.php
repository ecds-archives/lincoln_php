<?php

include("config.php");
html_head("Contents", "lincoln.css");
include_once("lib/xmlDbConnection.class.php");

print "<body>";

include("header.html");

$args = array('host' => "bohr.library.emory.edu",
	      'port' => "8080",
	      'db' => "lincoln",
	      'dbtype' => "exist",
	        'debug' => false);
$exist = new xmlDbConnection($args);

/*
$query = 'for $a in input()/TEI.2/:text/body/div1 
let $bibl := $a/head/bibl  
return <div> {$a/@id} {$bibl} </div> sort by (@id)';
*/
/*$query = 'for $a in input()/TEI.2
let $auth := $a/teiHeader/fileDesc/titleStmt/author
let $body := $a/:text/body
return <div> {$auth}
{for $div1 in $body/div1 return <div1>{$div1/@id}{$div1/head/bibl}</div1> } </div> sort by (author)'; */

$query = 'for $a in //TEI.2
let $auth := $a/teiHeader/fileDesc/titleStmt/author
let $body := $a//text/body
order by $auth
return <div> {$auth}
{for $div1 in $body/div1 return <div1>{$div1/@id}{$div1/head/bibl}</div1> } </div>'; 


$xsl_file = "contents.xsl";  

$exist->xquery($query);

print '<div class="content">  
          <h2>Contents</h2>';
print "<hr>";
$xsl_file = "contents.xsl";
$exist->xslTransform($xsl_file); 
$exist->printResult(); 

print "<hr>"; 
print "</div>";

include("footer.html");
?> 
   
  </div>
   
</body>
</html>
