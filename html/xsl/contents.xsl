<?xml version="1.0" encoding="ISO-8859-1"?> 

<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
	xmlns:html="http://www.w3.org/TR/REC-html40" version="1.0"
	xmlns:ino="http://namespaces.softwareag.com/tamino/response2" 
	xmlns:xq="http://metalab.unc.edu/xq/">

<xsl:output method="html"/>  

<xsl:template match="/"> 
        <xsl:apply-templates select="//div"/>
</xsl:template>

<xsl:template match="div">
  <p>
    <xsl:value-of select="author"/> 
    <ul>
      <xsl:apply-templates select="div1/bibl"/>
   </ul>
  </p>
</xsl:template>


<xsl:template match="bibl">
  <a>
    <xsl:attribute name="href">sermon.php?id=<xsl:value-of select="../@id"/></xsl:attribute>
  <xsl:value-of select="title"/></a><br/>
  <xsl:value-of select="date"/>, 
  <xsl:value-of select="pubPlace"/><br/>
</xsl:template>


</xsl:stylesheet>