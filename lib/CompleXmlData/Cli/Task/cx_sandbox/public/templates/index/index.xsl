<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <xsl:output encoding="UTF-8" method="html" omit-xml-declaration="yes" indent="no"
        doctype-public="-//W3C//DTD XHTML 1.0 Strict//EN"
        doctype-system="http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"/>

    <xsl:include href="../layout.xsl" />

    <xsl:template match="/">
        <xsl:apply-templates select="page">
            <xsl:with-param name="title">Congratulations, CompleXml project started!</xsl:with-param>
        </xsl:apply-templates>
    </xsl:template>

    <xsl:template match="content">
       <h1>Congratulations, CompleXml project started!</h1>
       <p>for more informations visit <a href="http://complexml.org/docs" target="_blank">documentation page</a></p> 
    </xsl:template>

</xsl:stylesheet>