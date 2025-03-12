<?php
if (!defined('__TYPECHO_ROOT_DIR__')) {
    exit;
}

/**
 * ChenCong.BLOG 网站 Sitemap.xml 文件生成器
 * 
 * @package Sitemap Generator
 * @author 蔥籽
 * @version 1.0.0
 * @link https://chencong.cn
 */
class Sitemap_Plugin implements Typecho_Plugin_Interface
{
    /**
     * 激活插件方法，如果激活失败，直接抛出异常
     * 
     * @access public
     * @return void
     * @throws Typecho_Plugin_Exception
     */
    public static function activate()
    {
        try {
            // 添加路由规则，将 /sitemap.xml 映射到 Sitemap_Action 类的 action 方法
            Helper::addRoute('sitemap', '/sitemap.xml', 'Sitemap_Action', 'action');
        } catch (Exception $e) {
            // 若添加路由失败，抛出插件异常并记录错误信息
            throw new Typecho_Plugin_Exception('插件激活失败：' . $e->getMessage());
        }
    }

    /**
     * 禁用插件方法，如果禁用失败，直接抛出异常
     * 
     * @static
     * @access public
     * @return void
     * @throws Typecho_Plugin_Exception
     */
    public static function deactivate()
    {
        try {
            // 移除之前添加的路由规则
            Helper::removeRoute('sitemap');
        } catch (Exception $e) {
            // 若移除路由失败，抛出插件异常并记录错误信息
            throw new Typecho_Plugin_Exception('插件禁用失败：' . $e->getMessage());
        }
    }

    /**
     * 获取插件配置面板
     * 
     * @access public
     * @param Typecho_Widget_Helper_Form $form 配置面板
     * @return void
     */
    public static function config(Typecho_Widget_Helper_Form $form)
    {
        // 目前没有配置项，留空即可
    }

    /**
     * 个人用户的配置面板
     * 
     * @access public
     * @param Typecho_Widget_Helper_Form $form 配置面板
     * @return void
     */
    public static function personalConfig(Typecho_Widget_Helper_Form $form)
    {
        // 目前没有个人用户配置项，留空即可
    }
}