<?xml version="1.0" encoding="ISO-8859-1"?> 

<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
	xmlns:html="http://www.w3.org/TR/REC-html40" version="1.0"
	xmlns:ino="http://namespaces.softwareag.com/tamino/response2" 
	xmlns:xq="http://metalab.unc.edu/xq/">


<!-- search terms for highlighting.  Should be in format:
     term1|term2|term3|term4  -->
<xsl:param name="term_list"/>
<xsl:param name="selflink"/>	<!-- link to self, for single document kwic link -->
<xsl:param name="mode"/>		<!-- kwic -->

<!-- generate an addendum to the url, in the form of:
     &term[]=string1&term[]=string2 etc. 
     This string should be appended to the browse (sermon.php)  url.  -->
<xsl:variable name="term_string">
  <xsl:call-template name="highlight-params">
    <xsl:with-param name="str"><xsl:value-of select="$term_list"/></xsl:with-param>
  </xsl:call-template>
</xsl:variable>

<xsl:output method="xml" omit-xml-declaration="yes"/>  

<xsl:template match="/"> 
    <xsl:choose>
      <xsl:when test="$mode = 'kwic'">
        <!-- don't put in a table, use # of matches -->
        <xsl:apply-templates select="//div1" mode="kwic"/>
      </xsl:when>
      <xsl:when test="//div1/count">
        <xsl:element name="table">
          <xsl:attribute name="class">searchresults</xsl:attribute>
	  <xsl:element name="tr">
	    <xsl:element name="th">
              <xsl:attribute name="class">tip</xsl:attribute>
		To view keyword in context results for a single
		document, click on the number of matches.
            </xsl:element>
	    <xsl:element name="th">number of matches</xsl:element>
	  </xsl:element>
          <xsl:apply-templates select="//div1" mode="count"/>
        </xsl:element>
      </xsl:when>
      <xsl:otherwise>
        <xsl:apply-templates select="//div1" />
      </xsl:otherwise>
    </xsl:choose>
</xsl:template>

<!-- put sermon title in a table in order to align matches count off to the side -->
<xsl:template match="div1" mode="count">
  <xsl:element name="tr">
    <xsl:element name="td">
      <xsl:apply-templates select="."/>
    </xsl:element>
    <xsl:element name="td">
      <xsl:attribute name="class">count</xsl:attribute>
	<!-- number of matches for a search -->
        <xsl:apply-templates select="count" mode="count"/>
    </xsl:element>
      <xsl:element name="td">
        <xsl:attribute name="class">link</xsl:attribute>
  	  <xsl:element name="a">  
    	    <xsl:attribute name="href"><xsl:value-of select="$selflink"/>&amp;id=<xsl:value-of select="@id"/>&amp;kwic=true</xsl:attribute> 
    	view context 
  	</xsl:element>  <!-- a -->
      </xsl:element> <!-- td -->
  </xsl:element>
</xsl:template>


<!-- kwic results -->
<xsl:template match="div1" mode="kwic">
<!-- enclose sermon title & number of  matches in a table  -->
  <xsl:element name="table">
   <xsl:attribute name="class">kwicsearchresults</xsl:attribute> 
    <xsl:element name="tr">
      <xsl:element name="td">
        <xsl:apply-templates select="bibl"/>
      </xsl:element>
      <xsl:element name="td">
        <xsl:attribute name="class">count</xsl:attribute>
	<!-- number of matches for a search -->
        <xsl:apply-templates select="count" mode="kwic"/>
      </xsl:element> <!-- td -->
    </xsl:element> <!-- tr -->
  </xsl:element> <!-- table -->

  <!-- now display context -->
  <xsl:apply-templates select="context"/>
</xsl:template>


<!-- # of matches within a sermon -->
<xsl:template match="count"> 
  <!-- do nothing in normal mode, for now -->  
</xsl:template>

<!-- # of matches within a sermon -->
<xsl:template match="count" mode="count"> 
  <xsl:element name="a">  
    <xsl:attribute name="href"><xsl:value-of select="$selflink"/>&amp;id=<xsl:value-of select="../@id"/>&amp;kwic=true</xsl:attribute> 
    <xsl:value-of select="."/>
  </xsl:element> 
</xsl:template>

<!-- # of matches within a sermon -->
<xsl:template match="count" mode="kwic"> 
<xsl:element name="b">	<!-- bold -->
  <xsl:value-of select="."/> match<xsl:if test=". &gt; 1">es</xsl:if>
</xsl:element>
</xsl:template>


<!-- keyword in context -->
<xsl:template match="context/p">
  <table class="kwic"> 
  <td class="pn">
  <xsl:if test="@pn != ''">
     <a>
          <xsl:attribute name="href">sermon.php?id=<xsl:value-of select="ancestor::div1/@id"/><xsl:value-of select="$term_string"/>#page<xsl:value-of select="@pn"/></xsl:attribute>
	page <xsl:value-of select="@pn"/></a>
  </xsl:if>
</td>
 <td>
   <xsl:apply-templates/>
  </td>
 </table>
</xsl:template>

<!-- ignore figure, figure descriptions -->
<xsl:template match="context//figure"/>


<xsl:template match="bibl">
 <p>
  <a><xsl:attribute name="href">sermon.php?id=<xsl:value-of select="../@id"/><xsl:value-of select="$term_string"/></xsl:attribute>
  <xsl:value-of select="title"/></a><br/>
  <xsl:value-of select="author"/>. <xsl:value-of select="pubPlace"/>, <xsl:value-of select="date"/>.
 </p>
</xsl:template>

<xsl:template match="total"/>


<xsl:template name="highlight-params">
  <xsl:param name="str"/>
  <xsl:choose>
    <xsl:when test="contains($str, '|')">
       <xsl:text>&amp;term[]=</xsl:text><xsl:value-of select="substring-before($str, '|')"/>
       <xsl:call-template name="highlight-params">
         <xsl:with-param name="str"><xsl:value-of select="substring-after($str, '|')"/></xsl:with-param>
       </xsl:call-template>
    </xsl:when>
    <xsl:when test="string-length($str) = 0">
  	<!-- empty string or not set; do nothing -->
    </xsl:when>
    <xsl:otherwise>
       <xsl:text>&amp;term[]=</xsl:text><xsl:value-of select="$str"/>
    </xsl:otherwise>
  </xsl:choose>

</xsl:template>

</xsl:stylesheet>
