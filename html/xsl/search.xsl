<?xml version="1.0" encoding="ISO-8859-1"?> 

<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
	xmlns:html="http://www.w3.org/TR/REC-html40" version="1.0"
	xmlns:ino="http://namespaces.softwareag.com/tamino/response2" 
	xmlns:xq="http://metalab.unc.edu/xq/">



<xsl:param name="term">0</xsl:param>
<xsl:param name="term2">0</xsl:param>
<xsl:param name="term3">0</xsl:param>

<!-- construct string to pass search term values to browse via url -->
<xsl:variable name="term_string"><xsl:if test="$term != 0">&amp;term[]=<xsl:value-of select="$term"/></xsl:if><xsl:if test="$term2 != 0">&amp;term[]=<xsl:value-of select="$term2"/></xsl:if><xsl:if test="$term3 != 0">&amp;term[]=<xsl:value-of select="$term3"/></xsl:if></xsl:variable>

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

</xsl:stylesheet>