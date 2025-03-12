<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;

class Sitemap_Action extends Typecho_Widget implements Widget_Interface_Do
{
    public function action()
    {
        // 获取数据库实例和选项
        $db = Typecho_Db::get();
        $options = Typecho_Widget::widget('Widget_Options');

        // 查询页面、文章、分类和标签数据
        $pages = $this->fetchContents($db, $options, 'page');
        $articles = $this->fetchContents($db, $options, 'post');
        $cates = Typecho_Widget::widget('Widget_Metas_Category_List@cate');
        $tags = $this->fetchTags($db);

        // 设置响应头为 XML
        header("Content-Type: application/xml");
        // 输出 XML 声明
        echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
        // 输出 XSL 样式表引用
        echo "<?xml-stylesheet type='text/xsl' href='" . $options->pluginUrl . "/Sitemap/sitemap.xsl'?>\n";
        // 输出 urlset 标签开头
        echo "<urlset xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\"\nxsi:schemaLocation=\"http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd\"\nxmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">";

        // 输出网站首页信息
        $this->outputUrl($options->siteUrl, date('Y-m-d', $options->gmtTime), 'daily', '1.0');

        // 输出页面信息
        foreach ($pages as $page) {
            $page['permalink'] = $this->getPermalink($page, $options);
            $this->outputUrl($page['permalink'], date('Y-m-d', $page['modified']), 'monthly', '0.6');
        }

        // 输出文章信息
        foreach ($articles as $article) {
            $article = $this->processArticle($article, $db);
            $article['permalink'] = $this->getPermalink($article, $options);
            $this->outputUrl($article['permalink'], date('Y-m-d', $article['modified']), 'never', '0.8');
        }

        // 输出分类信息
        while ($cates->next()) {
            $art_rs = $this->fetchLastModifiedContent($db, $cates->mid);
            $lastmod = $art_rs ? date('Y-m-d', $art_rs['modified']) : date('Y-m-d', $options->gmtTime);
            $this->outputUrl($cates->permalink, $lastmod, 'monthly', '0.5');
        }

        // 输出标签信息
        foreach ($tags as $tag) {
            $art_rt = $this->fetchLastModifiedContent($db, $tag['mid']);
            $lastmod = $art_rt ? date('Y-m-d', $art_rt['modified']) : date('Y-m-d', $options->gmtTime);
            $tag['permalink'] = $this->getPermalink($tag, $options);
            $this->outputUrl($tag['permalink'], $lastmod, 'monthly', '0.5');
        }

        // 输出 urlset 标签结尾
        echo "</urlset>";
    }

    /**
     * 查询内容数据
     * @param Typecho_Db $db 数据库实例
     * @param Typecho_Widget $options 选项实例
     * @param string $type 内容类型
     * @return array 内容数据数组
     */
    private function fetchContents($db, $options, $type)
    {
        return $db->fetchAll($db->select()->from('table.contents')
            ->where('table.contents.status = ?', 'publish')
            ->where('table.contents.created < ?', $options->gmtTime)
            ->where('table.contents.type = ?', $type)
            ->order('table.contents.created', Typecho_Db::SORT_DESC));
    }

    /**
     * 查询标签数据
     * @param Typecho_Db $db 数据库实例
     * @return array 标签数据数组
     */
    private function fetchTags($db)
    {
        return $db->fetchAll($db->select()->from('table.metas')
            ->where('table.metas.type = ?', 'tag')
            ->order('table.metas.mid', Typecho_Db::SORT_DESC));
    }

    /**
     * 处理文章数据
     * @param array $article 文章数据
     * @param Typecho_Db $db 数据库实例
     * @return array 处理后的文章数据
     */
    private function processArticle($article, $db)
    {
        $article['categories'] = $db->fetchAll($db->select()->from('table.metas')
            ->join('table.relationships', 'table.relationships.mid = table.metas.mid')
            ->where('table.relationships.cid = ?', $article['cid'])
            ->where('table.metas.type = ?', 'category')
            ->order('table.metas.order', Typecho_Db::SORT_ASC));
        $article['category'] = urlencode(current(Typecho_Common::arrayFlatten($article['categories'], 'slug')));
        $article['slug'] = urlencode($article['slug']);
        $article['date'] = new Typecho_Date($article['created']);
        $article['year'] = $article['date']->year;
        $article['month'] = $article['date']->month;
        $article['day'] = $article['date']->day;
        return $article;
    }

    /**
     * 获取内容的永久链接
     * @param array $content 内容数据
     * @param Typecho_Widget $options 选项实例
     * @return string 永久链接
     */
    private function getPermalink($content, $options)
    {
        $type = $content['type'];
        $routeExists = (NULL != Typecho_Router::get($type));
        $pathinfo = $routeExists ? Typecho_Router::url($type, $content) : '#';
        return Typecho_Common::url($pathinfo, $options->index);
    }

    /**
     * 查询最后修改的内容
     * @param Typecho_Db $db 数据库实例
     * @param int $mid 分类或标签的 ID
     * @return array|null 最后修改的内容数据或 null
     */
    private function fetchLastModifiedContent($db, $mid)
    {
        return $db->fetchRow($db->select()->from('table.contents')
            ->join('table.relationships', 'table.contents.cid = table.relationships.cid')
            ->where('table.contents.status = ?', 'publish')
            ->where('table.relationships.mid = ?', $mid)
            ->order('table.relationships.cid', Typecho_Db::SORT_DESC)
            ->limit(1));
    }

    /**
     * 输出 url 标签信息
     * @param string $loc 链接地址
     * @param string $lastmod 最后修改时间
     * @param string $changefreq 更改频率
     * @param string $priority 优先级
     */
    private function outputUrl($loc, $lastmod, $changefreq, $priority)
    {
        echo "\t<url>\n";
        echo "\t\t<loc>" . htmlspecialchars($loc) . "</loc>\n";
        echo "\t\t<lastmod>" . $lastmod . "</lastmod>\n";
        echo "\t\t<changefreq>" . $changefreq . "</changefreq>\n";
        echo "\t\t<priority>" . $priority . "</priority>\n";
        echo "\t</url>\n";
    }
}