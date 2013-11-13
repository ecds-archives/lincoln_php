<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
	xmlns:html="http://www.w3.org/TR/REC-html40" 
	xmlns:xq="http://metalab.unc.edu/xq/"
	xmlns:tei="http://www.tei-c.org/ns/1.0"
	xmlns:exist="http://exist.sourceforge.net/NS/exist">

  <xsl:output method="xml"/>

<xsl:variable name="graphicsPrefix">http://beck.library.emory.edu/lincoln/image-content/</xsl:variable>
<xsl:variable name="graphicsSuffix">.jpg</xsl:variable>


  <xsl:template match="/">
    <xsl:apply-templates select="//div"/>
  </xsl:template>

  <xsl:template match="tei:head">
    <xsl:apply-templates select="//title"/>
    <xsl:apply-templates select="//author"/>
  </xsl:template>

  <xsl:template match="title">
    <i>
      <a>
        <xsl:attribute name="href">sermon.php?id=<xsl:value-of select="ancestor::div/@xml:id"/></xsl:attribute>
        <xsl:apply-templates/>
      </a>
    </i>
  </xsl:template>


<xsl:template match="figure">
  <xsl:variable name="myid"><xsl:value-of select="@entity"/></xsl:variable>
  <xsl:variable name="position">
    <xsl:for-each select="//siblings/figure">
      <xsl:if test="@entity = $myid">
        <xsl:value-of select="position()"/>
      </xsl:if>
    </xsl:for-each> 
  </xsl:variable>

  <p class="fullpage">

    <xsl:call-template name="pagenav">
      <xsl:with-param name="position"><xsl:value-of select="$position"/></xsl:with-param>
    </xsl:call-template>

    <xsl:element name="img">
      <xsl:attribute name="src"><xsl:value-of
      select="concat($graphicsPrefix, @entity,$graphicsSuffix)"/></xsl:attribute>
      <!-- display text if images are not turned on -->
      <xsl:attribute name="alt"><xsl:value-of select="normalize-space(figDesc)"/></xsl:attribute>
      <!-- show text on mouse-over (in some browsers) -->
      <xsl:attribute name="title"><xsl:value-of select="normalize-space(figDesc)"/></xsl:attribute>
    </xsl:element> <!-- img -->

    <xsl:call-template name="pagenav">
      <xsl:with-param name="position"><xsl:value-of select="$position"/></xsl:with-param>
    </xsl:call-template>
  </p> 
</xsl:template>


<xsl:template name="pagenav">
  <xsl:param name="position"/>

  <table class="pagenav">
    <tr>
      <td>
        <xsl:apply-templates select="//siblings/figure[$position - 1]">
          <xsl:with-param name="mode">Previous</xsl:with-param>
        </xsl:apply-templates>
      </td>
      <td class="desc">
        <xsl:apply-templates select="figDesc"/>
      </td>
      <td>
        <xsl:apply-templates select="//siblings/figure[$position + 1]">
          <xsl:with-param name="mode">Next</xsl:with-param>
        </xsl:apply-templates>
      </td>
    </tr>
  </table>
</xsl:template>


<!-- ignore 'sibling' figures : only used for navigation -->
<xsl:template match="siblings/figure"/>


<!-- generate next & previous links (if present) -->
<!-- note: all figures, with entity, are retrieved in a <siblings> node -->
<xsl:template name="next-prev">
<xsl:variable name="main_id"><xsl:value-of select="//div/figure/@entity"/></xsl:variable>
<!-- get the position of the current document in the siblings list -->
<xsl:variable name="position">
  <xsl:for-each select="//siblings/figure[@entity = $main_id]">
    <xsl:value-of select="position()"/>
  </xsl:for-each> 
</xsl:variable>

<xsl:element name="table">
  <xsl:attribute name="width">100%</xsl:attribute>

<!-- display articles relative to position of current article -->

  <xsl:apply-templates select="//siblings/figure[$position - 1]">
    <xsl:with-param name="mode">Previous</xsl:with-param>
  </xsl:apply-templates>

  <xsl:apply-templates select="//siblings/figure[$position + 1]">
    <xsl:with-param name="mode">Next</xsl:with-param>
  </xsl:apply-templates>

</xsl:element> <!-- table -->
</xsl:template>

<!-- print next/previous link with title & summary information -->
<xsl:template match="siblings/figure">
  <xsl:param name="mode"/>
  
  <xsl:variable name="linkrel">
    <xsl:choose>
      <xsl:when test="$mode='Previous'">
        <xsl:text>prev</xsl:text>
      </xsl:when>
      <xsl:when test="$mode='Next'">
        <xsl:text>next</xsl:text>
      </xsl:when>
    </xsl:choose>
  </xsl:variable>
  
  <xsl:element name="a">
    <xsl:attribute name="href">page.php?id=<xsl:value-of select="@entity"/></xsl:attribute>
    <!-- use rel attribute to give next / previous information -->
    <xsl:attribute name="rel"><xsl:value-of select="$linkrel"/></xsl:attribute>
    <xsl:if test="$mode = 'Previous'"><xsl:text>&lt;&lt; </xsl:text></xsl:if>
    <xsl:value-of select="$mode"/>
    <xsl:if test="$mode = 'Next'"><xsl:text> &gt;&gt;</xsl:text></xsl:if>
  </xsl:element> <!-- a -->   

</xsl:template>


</xsl:stylesheet>
