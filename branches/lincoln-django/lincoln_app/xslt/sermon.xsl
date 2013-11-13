<?xml version="1.0" encoding="utf-8"?> 

<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0"
	xmlns:html="http://www.w3.org/TR/REC-html40" 	
	xmlns:tei="http://www.tei-c.org/ns/1.0"
	xmlns:exist="http://exist.sourceforge.net/NS/exist"
  xmlns:xq="http://metalab.unc.edu/xq/">
<!--	xmlns:ino="http://namespaces.softwareag.com/tamino/response2" 
	xmlns:xq="http://metalab.unc.edu/xq/"> -->

<!--<xsl:import href="html/teihtml-param.xsl"/>-->
<!--<xsl:import href="common/teicommon.xsl"/>
-->

<!-- temporary fix for graphic image source: FIXME -->
<xsl:variable name="numberBackHeadings"></xsl:variable>
<xsl:variable name="numberHeadings"></xsl:variable>
<xsl:variable name="numberHeadingsDepth"></xsl:variable>
<xsl:variable name="graphicsSuffix">.gif</xsl:variable>
<xsl:variable name="graphicsPrefix">http://beck.library.emory.edu/lincoln/image-content/</xsl:variable>
<xsl:variable name="headingNumberSuffix"></xsl:variable>
<xsl:param name="makePageTable">true</xsl:param>
<xsl:param name="showFigures">true</xsl:param>

<!--<xsl:include href="html/teihtml-main.xsl"/>-->
<!-- <xsl:include href="teinote.xsl"/> -->
<!--<xsl:include href="footnotes.xsl"/>-->
<!--<xsl:include href="html/teihtml-notes.xsl"/>-->
<!-- <xsl:include href="html/teihtml-bibl.xsl"/> -->
<!--<xsl:include href="html/teihtml-front.xsl"/>-->
<!--<xsl:include href="html/teihtml-figures.xsl"/>-->
<!--<xsl:include href="html/teihtml-lists.xsl"/>-->
<!-- <xsl:include href="html/teihtml-struct.xsl"/> -->
<!--<xsl:include href="html/teihtml-tables.xsl"/>
<xsl:include href="html/teihtml-pagetable.xsl"/> -->
<!-- xref needed for locateParentdiv -->
<!--<xsl:include href="html/teihtml-xref.xsl"/>-->



<xsl:output method="xml"/>  

<xsl:template match="/"> 
    <!--<xsl:call-template name="footnote-init"/>--> <!-- for popup footnotes -->
    <div><xsl:apply-templates select="//tei:div1"/></div>
    <!--<xsl:call-template name="endnotes"/>-->
</xsl:template>


<xsl:template match="tei:head/tei:bibl">
  <xsl:element name="h2">
  <xsl:element name="i">
      <xsl:value-of select="tei:title"/>
  </xsl:element>, <xsl:value-of select="tei:author"/>, <xsl:value-of select="tei:date"/>,<br/>
  <xsl:value-of select="tei:pubPlace"/>.
  </xsl:element>
</xsl:template>

<!-- rewrite this to reflect new page break structure -->
<!-- <xsl:template match="tei:p/tei:figure|tei:figure"> -->
<!-- <p class="pageimage">  -->
<!--  <xsl:element name="a"> -->
<!--   <xsl:attribute name="href">page.php?id=<xsl:value-of select="@facs"/></xsl:attribute> -->
<!--   <xsl:element name="img"> -->
<!--    <xsl:attribute name="class">page</xsl:attribute> -->
<!--    <xsl:attribute name="src"><xsl:value-of select="concat(substring-before(@facs, '.jpg'), '.gif')"/></xsl:attribute> -->
   <!-- <xsl:attribute name="src"><xsl:value-of -->
   <!-- 	select="concat($graphicsPrefix, @entity,$graphicsSuffix)"/></xsl:attribute> -->
   <!-- display text if images are not turned on -->
<!--    <xsl:attribute name="alt"><xsl:value-of select="normalize-space(figDesc)"/></xsl:attribute> -->
<!--     show text on mouse-over (in some browsers) -->
<!--    <xsl:attribute name="title"><xsl:value-of select="normalize-space(figDesc)"/></xsl:attribute> -->
<!--   </xsl:element> --> <!-- img -->
<!--   </xsl:element> --> <!-- a  -->
<!--  </p>  -->
<!-- </xsl:template> -->

<xsl:template match="tei:pb">
    <hr class="pb"/>
  <p class="pagebreak"> 
    <a>	<!-- anchor to jump to a specific page -->
      <xsl:attribute name="name">page<xsl:value-of select="@n"/></xsl:attribute>
      Page <xsl:value-of select="@n"/>
   </a>
    </p> 
    <p class="pageimage">
      <a>
	<xsl:attribute name="href"><xsl:value-of select="@facs"/></xsl:attribute>
      <xsl:element name="img">
	<xsl:attribute name="src"><xsl:value-of select="concat($graphicsPrefix,substring-before(@facs, '.jpg'),$graphicsSuffix)"/></xsl:attribute>
	<xsl:attribute name="alt"><xsl:value-of select="concat('page', ' ', ./@n)"/></xsl:attribute>
	<xsl:attribute name="title">Page <xsl:value-of select="@n"/> of <xsl:value-of select="//tei:head//tei:title"/></xsl:attribute>
      </xsl:element> <!-- end img -->
      </a> <!-- end a -->
    </p>
</xsl:template>

<xsl:template match="tei:p">
<p><xsl:apply-templates/></p>
</xsl:template>


<xsl:template match="tei:lb">
  <br/>
</xsl:template>

<xsl:template match="tei:lg">
  <p>
    <xsl:attribute name="class"><xsl:value-of select="@type"/></xsl:attribute>
    <xsl:apply-templates/>
  </p>
</xsl:template>

<xsl:template match="tei:l">
  <xsl:apply-templates/><br/>  
</xsl:template>

<xsl:template match="tei:hi">
  <xsl:choose>
    <xsl:when test="@rend = 'italic'">
      <i><xsl:apply-templates/></i>
    </xsl:when>
  </xsl:choose>
</xsl:template>

<xsl:template match="tei:lg/tei:head">
  <xsl:apply-templates/>
  <xsl:element name="br"/>
</xsl:template>

<!-- mark exist matches for highlighting -->
  <xsl:template match="exist:match">
    <span class="match"><xsl:apply-templates/></span>
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