<?xml version="1.0" encoding="ISO-8859-1"?> 

<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
	xmlns:html="http://www.w3.org/TR/REC-html40" 
	xmlns:xq="http://metalab.unc.edu/xq/"
	xmlns:tei="http://www.tei-c.org/ns/1.0"
	xmlns:exist="http://exist.sourceforge.net/NS/exist">

<xsl:output method="html"/>  

<xsl:template match="/"> 
        <xsl:apply-templates select="//div"/>
</xsl:template>

<xsl:template match="div">
  <p>
    <span class="author"><xsl:value-of select="tei:author"/></span>
    <ul>
      <xsl:apply-templates select="div1/tei:bibl"/>
   </ul>
  </p>
</xsl:template>


<xsl:template match="tei:bibl">
<li class="sermon">
  <a>
    <xsl:attribute name="href">sermon.php?id=<xsl:value-of select="@xml:id"/></xsl:attribute>
  <xsl:value-of select="tei:title"/></a><br/>
  <xsl:value-of select="tei:date"/>, 
  <xsl:value-of select="tei:pubPlace"/><br/>
</li>
</xsl:template>


</xsl:stylesheet>