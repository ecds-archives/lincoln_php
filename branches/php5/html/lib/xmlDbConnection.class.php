<?php 

include "taminoConnection.class.php";

class xmlDbConnection {

  // connection parameters
  var $host;
  var $port;
  var $db;
  var $coll;
  var $dbtype; 	// tamino,exist
  // whether or not to display debugging information
  var $debug;
  
  // these variables used internally
  var $xmldb;	// tamino or exist class object
  var $xsl_result;

  // xml/xpath variables - references
  var $xml;
  var $xpath;

  // variables for return codes/messages?

  // cursor variables (needed here?)
  var $cursor;
  var $count;
  var $position;

  // variables for highlighting search terms
  var $begin_hi;
  var $end_hi;


  function xmlDbConnection($argArray) {
    $this->host = $argArray['host'];
    $this->db = $argArray['db'];
    $this->coll = $argArray['coll'];
    $this->debug = $argArray['debug'];

    $this->dbtype = $argArray['dbtype'];
    if ($this->dbtype == "exist") {
      // create an exist object, pass on parameters
      $this->xmldb = new existConnection($argArray);
    } else {	// for backwards compatibility, make tamino default
      // create a tamino object, pass on parameters
     $this->xmldb = new taminoConnection($argArray);
    }

    // xmlDb count is the same as tamino or exist count 
    $this->count =& $this->xmldb->count;
    // xpath just points to tamino xpath object
    $this->xml =& $this->xmldb->xml;
    $this->xpath =& $this->xmldb->xpath;

    // variables for highlighting search terms
    $this->begin_hi[0]  = "<span class='term1'>";
    $this->begin_hi[1] = "<span class='term2'>";
    $this->begin_hi[2] = "<span class='term3'>";
    $this->end_hi = "</span>";
  }

  // send an xquery & get xml result
  function xquery ($query, $position = NULL, $maxdisplay = NULL) {
    // pass along xquery & parameters to specified xml db
    $this->xmldb->xquery($this->encode_xquery($query), $position, $maxdisplay);
  }

  // x-query : should only be in tamino...
  function xql ($query, $position = NULL, $maxdisplay = NULL) {
    // pass along xql & parameters to specified xml db
    $this->xmldb->xql($this->encode_xquery($query), $position, $maxdisplay);
  }

  // retrieve cursor, total count    (xquery cursor by default)
  function getCursor () {
    $this->xmldb->getCursor();
  }
  // get x-query cursor (for backwards compatibility)
  function getXqlCursor () {
    $this->xmldb->getXqlCursor();
  }

   // transform the database returned xml with a specified stylesheet
   function xslTransform ($xsl_file, $xsl_params = NULL) {
     /* load xsl & xml as DOM documents */
     $xsl = new DomDocument();
     $xsl->load("xsl/$xsl_file");

     /* create processor & import stylesheet */
     $proc = new XsltProcessor();
     $xsl = $proc->importStylesheet($xsl);
     if ($xsl_params) {
       foreach ($xsl_params as $name => $val) {
         $proc->setParameter(null, $name, $val);
       }
     }
     /* transform the xml document and store the result */
     $this->xsl_result = $proc->transformToDoc($this->xmldb->xml);
   }

   function printResult ($term = NULL) {
     if ($this->xsl_result) {
       if (isset($term[0])) {
         $this->highlightXML($term);
         // this is a bit of a hack: the <span> tags used for
         // highlighting are strings, and not structural xml; this
         // allows them to display properly, rather than with &gt; and
         // &lt; entities
         print html_entity_decode($this->xsl_result->saveXML());
       } else {
         print $this->xsl_result->saveXML();
       }
     }

   }

   // get the content of an xml node by name when the path is unknown
   function findNode ($name, $node = NULL) {
     // this function is for backwards compatibility... 
     if (isset($this->xpath)) {     // only use the xpath object if it has been defined
       $n = $this->xpath->query("//$name");
       // return only the value of the first one
       if ($n) { $rval = $n->item(0)->textContent; }
     } else {
       $rval =0;
     }
     return $rval;
   }



   // Highlight the search strings within the xsl transformed result.
   // Takes an array of terms to highlight.
   function highlightString ($str, $term) {
     // note: need to fix regexps: * -> \w* (any word character)
      // FIXME: how best to deal with wild cards?

     // only do highlighting if the term is defined
     for ($i = 0; (isset($term[$i]) && ($term[$i] != '')); $i++) {
       // replace tamino wildcard (*) with regexp -- 1 or more word characters 
       $_term = str_replace("*", "\w+", $term[$i]);
     // Note: regexp is constructed to avoid matching/highlighting the terms in a url 
       $str = preg_replace("/([^=|']\b)($_term)(\b)/i",
	      "$1" . $this->begin_hi[$i] . "$2$this->end_hi$3", $str);
     }
     return $str;
   }

   // highlight text in the xml structure
   function highlightXML ($term) {
     $this->highlight_node($this->xsl_result, $term);
   }

   // recursive function to highlight search terms in xml text
   function highlight_node ($n, $term) {
     $children = $n->childNodes;
     foreach ($children as $c) {
       if ($c instanceof domElement) {
	 $this->highlight_node($c, $term);
       } else if ($c instanceof DOMCharacterData) {
	 $c->nodeValue = $this->highlightString($c->nodeValue, $term);
       }
     }
   }
   
   // print out search terms, with highlighting matching that in the text
   function highlightInfo ($term) {
     if (isset($term[0])) {
       print "<p align='center'>The following search terms have been highlighted: ";
       for ($i = 0; isset($term[$i]); $i++) {
	 print "&nbsp; " . $this->begin_hi[$i] . "$term[$i]$this->end_hi &nbsp;";
       }
       print "</p>";
     }
   }

  // convert a readable xquery into a clean url for tamino or exist
  function encode_xquery ($string) {
    // get rid of multiple white spaces
    $string = preg_replace("/\s+/", " ", $string);
    // convert spaces to their hex equivalent
    $string = str_replace(" ", "%20", $string);
    // convert ampersand & # within xquery (e.g., for unicode entities) to hex
    $string = str_replace("&", "%26", $string);
    $string = str_replace("#", "%23", $string);
    return $string;
  }

   // print out xml (for debugging purposes)
   function displayXML () {
     if ($this->xml) {
       $this->xml->formatOutput = true;
       print "<pre>";
       print htmlentities($this->xml->saveXML());
       print "</pre>";
     }
   }


}
