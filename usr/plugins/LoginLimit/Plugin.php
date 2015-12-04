<?php
/**
 * 限制后台登陆失败次数
 * 
 * @package LoginLimit 
 * @author hongweipeng
 * @version 1.0.0
 * @link http://blog.west2online.com
 * @email hongweichen8888@sina.com
 */
class LoginLimit_Plugin implements Typecho_Plugin_Interface {

     /**
     * 激活插件方法,如果激活失败,直接抛出异常
     * 
     * @access public
     * @return void
     * @throws Typecho_Plugin_Exception
     */
    public static function activate() {
        //插件注册
        Typecho_Plugin::factory('Widget_User')->login = array(__CLASS__, 'dologin');
        Typecho_Plugin::factory('Widget_User')->loginSucceed = array(__CLASS__, 'loginSucceed');
        Typecho_Plugin::factory('Widget_Login')->loginFail = array(__CLASS__, 'loginFail');
    }
    
    /**
     *用户登录操作
     *@return void
     */
    public static function dologin($name, $password, $temporarily, $expire) {
        @session_start();//typecho不默认开启seesion
        $time = $_SERVER['REQUEST_TIME'];
        if(!isset($_SESSION['login_start'])) {
            //如果不存在
            self::setLoginSession();
        }else if($time - $_SESSION['login_start'] > 1800){
            self::setLoginSession();
        }

        $limit = self::getLimitCount();
        if($_SESSION['try_count'] >= $limit) {
            //尝试次数超过允许的设置次数
            $mins = ceil( ($_SESSION['login_start'] + 1800 - $time) / 60 );
            self::showErrorMsg('尝试次数超过允许的设置次数,请'.$mins.'分钟后再试');
        }
        
        Typecho_Widget::widget('Widget_Login')->login($name, $password, $temporarily, $expire);
    }
    
    /**
     *用户登录成功
     *@return void
     */
    public static function loginSucceed() {
        self::destoryLoginSession();
    }
    /**
     *用户登录失败
     *@return void
     */
    public static function loginFail() {
        $_SESSION['try_count']++;
        self::showErrorMsg('无效的账号密码，您还可以尝试'.(self::getLimitCount() - $_SESSION['try_count']).'次');
    }
    /**
     *创建本插件设置的session
     *@return void
     */
    public static function setLoginSession() {
        $_SESSION['login_start'] = $_SERVER['REQUEST_TIME'];//开始尝试登录的时间
        $_SESSION['try_count']   = 0;//已经尝试了n次
    }
    /**
     *销毁本插件设置的session
     *@return void
     */
    public static function destoryLoginSession() {
        @session_start();
        if(isset($_SESSION['login_start'])) {
            unset($_SESSION['login_start']);
            unset($_SESSION['try_count']);
        }
    }
    /**
     *显示错误信息并跳回登录界面
     *@return void
     */
    public static function showErrorMsg($msg) {
        $referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'javascript:history.back(-1);';
        Typecho_Widget::widget('Widget_Notice')->set(_t($msg), 'error');
        Typecho_Response::redirect($referer);
    }

    /**
     *获取设置项，半小时允许尝试的登陆次数
     */
    public static function getLimitCount() {
        $limit = Typecho_Widget::widget('Widget_Options')->plugin('LoginLimit')->timetlimit;
        return (int)$limit;
    }

    /**
     * 禁用插件方法,如果禁用失败,直接抛出异常
     * 
     * @static
     * @access public
     * @return void
     * @throws Typecho_Plugin_Exception
     */
    public static function deactivate(){
        self::destoryLoginSession();
    }

    /**
     * 获取插件配置面板
     * 
     * @access public
     * @param Typecho_Widget_Helper_Form $form 配置面板
     * @return void
     */
    public static function config(Typecho_Widget_Helper_Form $form){

        /** 分类名称 */
        $name = new Typecho_Widget_Helper_Form_Element_Text('timetlimit', NULL, '15', _t('半小时内允许尝试登陆失败的次数'));
        $form->addInput($name);
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
}
