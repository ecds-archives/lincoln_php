<?xml version="1.0" encoding="ISO-8859-1"?> 

<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
	xmlns:html="http://www.w3.org/TR/REC-html40" version="1.0"
	xmlns:ino="http://namespaces.softwareag.com/tamino/response2" 
	xmlns:xq="http://metalab.unc.edu/xq/">


<!-- search terms for highlighting.  Should be in format:
     term1|term2|term3|term4  -->
<xsl:param name="term_list"/>

<!-- generate an addendum to the url, in the form of:
     &term[]=string1&term[]=string2 etc. 
     This string should be appended to the browse (sermon.php)  url.  -->
<xsl:variable name="term_string">
  <xsl:call-template name="highlight-params">
    <xsl:with-param name="str"><xsl:value-of select="$term_list"/></xsl:with-param>
  </xsl:call-template>
</xsl:variable>

<xsl:output method="html"/>  

<xsl:template match="/"> 
        <xsl:apply-templates select="//div1"/>
</xsl:template>


<xsl:template match="bibl">
 <p>
  <a><xsl:attribute name="href">sermon.php?id=<xsl:value-of select="../@id"/><xsl:if test="$term_string != 0"><xsl:value-of select="$term_string"/></xsl:if></xsl:attribute>
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