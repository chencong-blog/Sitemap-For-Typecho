<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="2.0"
    xmlns:html="http://www.w3.org/TR/REC-html40"
    xmlns:sitemap="http://www.sitemaps.org/schemas/sitemap/0.9"
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

    <xsl:output method="html" version="1.0" encoding="UTF-8" indent="yes"/>

    <xsl:template match="/">
        <html xmlns="http://www.w3.org/1999/xhtml">
            <head>
                <title>ChenCong.BLOG 网站地图</title>
                <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
                <style type="text/css">
                    body {
                        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                        font-size: 14px;
                        color: #333;
                        background-color: #f4f7fa;
                        margin: 0;
                        padding: 0;
                    }

                    h1 {
                        background-color: #003366;
                        color: white;
                        text-align: center;
                        padding: 25px;
                        margin: 0;
                        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
                    }

                    #intro {
                        background-color: #e0e9f2;
                        border: 1px solid #b3c6d9;
                        border-radius: 5px;
                        padding: 20px;
                        margin: 25px;
                        line-height: 1.6;
                    }

                    #intro p {
                        margin: 0;
                    }

                    #content {
                        margin: 25px;
                        background-color: white;
                        border: 1px solid #b3c6d9;
                        border-radius: 5px;
                        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
                        padding: 25px;
                    }

                    table {
                        width: 100%;
                        border-collapse: collapse;
                        border: 1px solid #b3c6d9;
                    }

                    th {
                        background-color: #004488;
                        color: white;
                        border-bottom: 2px solid #003366;
                        text-align: center; 
                        padding: 15px;
                        font-size: 15px;
                        border: 1px solid #b3c6d9;
                        width: 40%; 
                    }

                    td {
                        border-bottom: 1px solid #b3c6d9;
                        padding: 15px;
                        font-size: 14px;
                        border: 1px solid #b3c6d9;
                        width: 40%; 
                    }

                    td:nth-child(2), th:nth-child(2) {
                        width: 5%; 
                    }
                    td:nth-child(3), th:nth-child(3) {
                        width: 5%; 
                    }
                    td:nth-child(4), th:nth-child(4) {
                        width: 9%; 
                    }

                    td:nth-child(2), td:nth-child(3), td:nth-child(4) {
                        text-align: center;
                    }

                    tr.even {
                        background-color: #f0f4f8;
                    }

                    tr.odd {
                        background-color: transparent;
                    }

                    tr:hover {
                        background-color: #d6e4f2;
                        font-weight: bold; 
                    }

                    a {
                        color: #0066cc;
                        text-decoration: none;
                    }

                    a:hover {
                        text-decoration: underline;
                    }

                    #footer {
                        text-align: center;
                        padding: 15px;
                        font-size: 12px;
                        color: #999;
                    }

                    #back-to-home {
                        position: fixed;
                        bottom: 80px;
                        right: 30px;
                        background-color: #003366;
                        color: white;
                        padding: 10px;
                        border-radius: 5px;
                        text-decoration: none;
                        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
                        display: flex;
                        justify-content: center;
                        align-items: center;
                        font-size: 2em; 
                    }

                    #back-to-home:hover {
                        background-color: #004488;
                    }
                </style>
            </head>
            <body>
                <h1>ChenCong.BLOG 网站地图</h1>
                <div id="intro">
                    <p>
                        本 ChenCong.BLOG 的 XML 网站地图，依行业标准构建，旨在为
                        <a href="http://www.baidu.com">Baidu</a>,
                        <a href="http://www.bing.com">Bing</a>,
                        <a href="http://www.google.com">Google</a>,
                        <a href="http://www.so.com">360</a> and
                        <a href="http://www.sogou.com">Sogou</a> 等主流搜索引擎，提供精准高效的内容抓取引导。助力搜索引擎理解网站结构与布局，提升网站索引效率及搜索排名表现。<br/>
                        若想深入了解 XML 网站地图知识，如原理、构建与优化等，可访问权威平台
                        <a href="http://sitemaps.org">sitemaps.org</a>。谷歌官方的
                        <a href="http://code.google.com/sm_thirdparty.html">网站地图程序列表</a>，也详细展示了各类程序工具及特性，是极具价值的参考资源，能助您深入剖析、灵活运用网站地图技术。
                    </p>
                </div>
                <div id="content">
                    <table cellpadding="5">
                        <tr>
                            <th>博客文章URL地址</th>
                            <th>优先级</th>
                            <th>更新频率</th>
                            <th>上次更改时间</th>
                        </tr>
                        <xsl:variable name="lower" select="'abcdefghijklmnopqrstuvwxyz'"/>
                        <xsl:variable name="upper" select="'ABCDEFGHIJKLMNOPQRSTUVWXYZ'"/>
                        <xsl:for-each select="sitemap:urlset/sitemap:url">
                            <tr>
                                <xsl:attribute name="class">
                                    <xsl:choose>
                                        <xsl:when test="position() mod 2 = 0">even</xsl:when>
                                        <xsl:otherwise>odd</xsl:otherwise>
                                    </xsl:choose>
                                </xsl:attribute>
                                <td>
                                    <xsl:variable name="itemURL">
                                        <xsl:value-of select="sitemap:loc"/>
                                    </xsl:variable>
                                    <a href="{$itemURL}">
                                        <xsl:value-of select="sitemap:loc"/>
                                    </a>
                                </td>
                                <td>
                                    <xsl:value-of select="concat(sitemap:priority*100,'%')"/>
                                </td>
                                <td>
                                    <xsl:value-of select="concat(translate(substring(sitemap:changefreq, 1, 1),concat($lower, $upper),concat($upper, $lower)),substring(sitemap:changefreq, 2))"/>
                                </td>
                                <td>
                                    <xsl:value-of select="concat(substring(sitemap:lastmod,0,11),concat(' ', substring(sitemap:lastmod,12,5)))"/>
                                </td>
                            </tr>
                        </xsl:for-each>
                    </table>
                </div>
                <div id="footer">
                    Copyright © 1981 ChenCong.BLOG. All rights reserved.
                </div>
                <a id="back-to-home" href="https://chencong.blog" title="蔥籽·中國菠蘿閣">
                    &#x1F3E0;
                </a>
            </body>
        </html>
    </xsl:template>
</xsl:stylesheet>