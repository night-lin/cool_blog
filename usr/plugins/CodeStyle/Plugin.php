<?php
/**
 * 代码显示样式风格 可多用户不同风格
 * 
 * @package CodeStyle 
 * @author hongweipeng
 * @version 0.6.1
 * @link http://blog.west2online.com
 */
class CodeStyle_Plugin implements Typecho_Plugin_Interface {
     /**
     * 激活插件方法,如果激活失败,直接抛出异常
     * 
     * @access public
     * @return void
     * @throws Typecho_Plugin_Exception
     */
    public static function activate() {
        Typecho_Plugin::factory('Widget_Archive')->header = array(__CLASS__, 'header');
        Typecho_Plugin::factory('Widget_Archive')->footer = array(__CLASS__, 'footer');
    }

    /**
     * 禁用插件方法,如果禁用失败,直接抛出异常
     * 
     * @static
     * @access public
     * @return void
     * @throws Typecho_Plugin_Exception
     */
    public static function deactivate(){}

    /**
     * 获取插件配置面板
     * 
     * @access public
     * @param Typecho_Widget_Helper_Form $form 配置面板
     * @return void
     */
    public static function config(Typecho_Widget_Helper_Form $form){
        //设置代码风格样式
        $styles = array_map('basename', glob(dirname(__FILE__) . '/markdown/styles/*.css'));
        $styles = array_combine($styles, $styles);
        $name = new Typecho_Widget_Helper_Form_Element_Select('code_style', $styles, 'segmentfault.css', _t('选择你的代码风格'));
        $form->addInput($name->addRule('enum', _t('必须选择配色样式'), $styles));

        //是否显示行号
        $showline = new Typecho_Widget_Helper_Form_Element_Radio('ifshowline', array(
            0   =>  _t('不显示'),
            1   =>  _t('显示')
        ), 0, _t('显示行号'), _t('显示行号可能存在错位，还在调整中。。。'));
        $form->addInput($showline->addRule('enum', _t('必须选择一个模式'), array(0, 1)));
    }

    /**
     * 个人用户的配置面板
     * 
     * @access public
     * @param Typecho_Widget_Helper_Form $form
     * @return void
     */
    public static function personalConfig(Typecho_Widget_Helper_Form $form){}

    /**
     * 插件实现方法
     * 
     * @access public
     * @return void
     */
    public static function render() {
        
    }

    /**
     *为header添加css文件
     *@return void
     */
    public static function header() {
        $style = Helper::options()->plugin('CodeStyle')->code_style;
        $cssUrl = Helper::options()->pluginUrl . '/CodeStyle/markdown/styles/' . $style;
        echo '<link rel="stylesheet" type="text/css" href="' . $cssUrl . '" />';
        if(Helper::options()->plugin('CodeStyle')->ifshowline != 0) {
            echo '<link rel="stylesheet" type="text/css" href="' . Helper::options()->pluginUrl.'/CodeStyle/showline.css" />';
        }
    }

    /**
     *为footer添加js文件
     *@return void
     */
    public static function footer() {
        $jsUrl = Helper::options()->pluginUrl . '/CodeStyle/markdown/highlight.pack.js';
        echo <<<HTML
            <script src="http://cdn.bootcss.com/jquery/2.1.4/jquery.min.js"></script>
            <script type="text/javascript" src="{$jsUrl}"></script>
            <script type="text/javascript">
                hljs.initHighlightingOnLoad();
            </script>
HTML;
        if(Helper::options()->plugin('CodeStyle')->ifshowline != 0) {
            echo '<script type="text/javascript" src="'.Helper::options()->pluginUrl.'/CodeStyle/showline.js"></script>';
        }
    }
}
