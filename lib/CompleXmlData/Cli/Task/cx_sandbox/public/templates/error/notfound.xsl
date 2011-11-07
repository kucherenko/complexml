<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE xsl:stylesheet [
        <!ENTITY copy "&#169;">
        <!ENTITY nbsp "&#160;">
        <!ENTITY laquo "&#171;">
        <!ENTITY raquo "&#187;">
        ]>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <xsl:output encoding="UTF-8" method="html" omit-xml-declaration="yes" indent="no"
                doctype-public="-//W3C//DTD XHTML 1.0 Strict//EN"
                doctype-system="http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"/>

    <xsl:include href="../layout.xsl"/>

    <xsl:template match="/">
        <xsl:apply-templates select="page">
            <xsl:with-param name="title">Error</xsl:with-param>
        </xsl:apply-templates>
    </xsl:template>

    <xsl:template match="content">
        <h2>
            Error
            <xsl:choose>
                <xsl:when test="exception/type='CompleXml_Application_ControllerNotFoundException' or exception/type='CompleXml_Application_ActionNotFoundException'">
                    404
                </xsl:when>
                <xsl:otherwise>
                    500
                </xsl:otherwise>
            </xsl:choose>
        </h2>
        <xsl:if test="debug_mode">
            <h4><xsl:value-of select="exception/type"/>(code:<xsl:value-of select="exception/code"/>)
            </h4>
            <p>
                <xsl:value-of select="exception/message"/>
                <br/>
                File:
                <xsl:value-of select="exception/file"/>
                <br/>
                Line:
                <xsl:value-of select="exception/line"/>
                <br/>
                <pre>
                    <xsl:value-of select="exception/trace"/>
                </pre>
            </p>
            <h4>XSLT engine details</h4>
            <p>
                Version:
                <xsl:value-of select="system-property('xsl:version')"/>
                <br/>
                Vendor:
                <xsl:value-of select="system-property('xsl:vendor')"/>
                <br/>
                Vendor URL:
                <xsl:value-of select="system-property('xsl:vendor-url')"/>
            </p>
        </xsl:if>
    </xsl:template>

</xsl:stylesheet>