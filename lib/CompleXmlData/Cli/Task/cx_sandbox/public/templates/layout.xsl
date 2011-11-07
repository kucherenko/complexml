<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <xsl:output
            encoding="UTF-8"
            method="html"
            omit-xml-declaration="yes"
            indent="no"
            doctype-public="-//W3C//DTD XHTML 1.0 Strict//EN"
            doctype-system="http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"/>

    <xsl:template match="page">
        <xsl:param name="title"/>
        <xsl:param name="keywords"/>
        <xsl:param name="description"/>
        <html xmlns="http://www.w3.org/1999/xhtml">
            <head>
                <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
                <title><xsl:value-of select="$title"/></title>
                <meta name="keywords" content="{$keywords}"/>
                <meta name="description" content="{$description}"/>
            </head>
            <body>
                <xsl:apply-templates select="content" />
            </body>
        </html>
    </xsl:template>

</xsl:stylesheet>
