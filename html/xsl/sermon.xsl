<?xml version="1.0" encoding="ISO-8859-1"?> 

<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
	xmlns:html="http://www.w3.org/TR/REC-html40" version="1.0">

<!--	xmlns:ino="http://namespaces.softwareag.com/tamino/response2" 
	xmlns:xq="http://metalab.unc.edu/xq/"> -->

<xsl:import href="html/teihtml-param.xsl"/>
<xsl:import href="common/teicommon.xsl"/>

<xsl:variable name="numberBackHeadings"></xsl:variable>
<xsl:variable name="numberHeadings"></xsl:variable>
<xsl:variable name="numberHeadingsDepth"></xsl:variable>
<xsl:variable name="graphicsSuffix">.gif</xsl:variable>
<xsl:variable name="graphicsPrefix">http://chaucer.library.emory.edu/lincoln/images/</xsl:variable>
<xsl:variable name="headingNumberSuffix"></xsl:variable>
<xsl:param name="makePageTable">true</xsl:param>
<xsl:param name="showFigures">true</xsl:param>

<xsl:include href="html/teihtml-main.xsl"/>
<xsl:include href="html/teihtml-notes.xsl"/>
<xsl:include href="html/teihtml-bibl.xsl"/>
<xsl:include href="html/teihtml-front.xsl"/>
<xsl:include href="html/teihtml-figures.xsl"/>
<xsl:include href="html/teihtml-lists.xsl"/>
<xsl:include href="html/teihtml-struct.xsl"/>
<xsl:include href="html/teihtml-tables.xsl"/>
<xsl:include href="html/teihtml-pagetable.xsl"/>
<!-- xref needed for locateParentdiv -->
<xsl:include href="html/teihtml-xref.xsl"/>



<xsl:output method="html"/>  

<xsl:template match="/"> 
        <xsl:apply-templates select="//div1"/>
</xsl:template>

<!-- <xsl:template match="div1">
  <table>
   <tr><td></td><td>
      <xsl:apply-templates select="head"/>
     </td></tr>
      <xsl:apply-templates/>
  </table>
</xsl:template>
-->

<xsl:template match="p/figure|figure">
<p class="pageimage"> 
 <xsl:element name="a">
  <xsl:attribute name="href"><xsl:value-of
	select="concat($graphicsPrefix, @entity, '.jpg')"/></xsl:attribute>
  <xsl:element name="img">
   <xsl:attribute name="class">page</xsl:attribute>
   <xsl:attribute name="src"><xsl:value-of
	select="concat($graphicsPrefix, @entity,$graphicsSuffix)"/></xsl:attribute>
   <!-- display text if images are not turned on -->
   <xsl:attribute name="alt"><xsl:value-of select="normalize-space(figDesc)"/></xsl:attribute>
   <!-- show text on mouse-over (in some browsers) -->
   <xsl:attribute name="title"><xsl:value-of select="normalize-space(figDesc)"/></xsl:attribute>
  </xsl:element> <!-- img -->
  </xsl:element> <!-- a -->
 </p> 
</xsl:template>

<xsl:template match="pb">
    <hr class="pb"/>
  <p class="pagebreak"> 
    Page <xsl:value-of select="@n"/>
  </p> 
</xsl:template>

<!--
<xsl:template match="p">
  <xsl:if test="preceding::*[name() = 'pb' or name() = 'figure'][1]">

  </xsl:if>
  <p>
   <xsl:apply-templates/>
  </p>
 <xsl:if test="following::*[name() = 'pb'][1]">
    <xsl:value-of select="</td></tr>"/>
  </xsl:if>
 
</xsl:template>
-->

</xsl:stylesheet>