<?php
class DomainTheme_Action extends Typecho_Widget implements Widget_Interface_Do
{
	private $db;
	private $options;
	private $prefix;

	/**
     *判断用户是否已登录，未登录自动跳转到登录页面
     *@return void
     */
    public function checkLogin() {
        $user = Typecho_Widget::widget('Widget_User');
        if(! $user->hasLogin()) {
            Typecho_Widget::widget('Widget_Notice')->set(_t("未登录"), 'error');
            $this->response->redirect($this->options->adminUrl);
        }

    }
			
	public function insertDomainTheme()
	{
		$this->checkLogin();
		if (DomainTheme_Plugin::form('insert')->validate()) {
			//$this->response->goBack();
		}
		/** 取出数据 */
		$link = $this->request->from('name', 'url', 'sort', 'image', 'description', 'user');
		$link['order'] = $this->db->fetchObject($this->db->select(array('MAX(order)' => 'maxOrder'))->from($this->prefix.'domaintheme'))->maxOrder + 1;

		/** 插入数据 */
		$link['lid'] = $this->db->query($this->db->insert($this->prefix.'domaintheme')->rows($link));

		/** 设置高亮 */
		$this->widget('Widget_Notice')->highlight('link-'.$link['lid']);

		/** 提示信息 */
		$this->widget('Widget_Notice')->set(_t('链接 <a href="%s">%s</a> 已经被增加',
		$link['url'], $link['name']), NULL, 'success');

		/** 转向原页 */
		$this->response->redirect(Typecho_Common::url('extending.php?panel=DomainTheme%2Fmanage-domaintheme.php', $this->options->adminUrl));
	}


	public function updateLink()
	{
		$this->checkLogin();
		if (domaintheme_Plugin::form('update')->validate()) {
			$this->response->goBack();
		}

		/** 取出数据 */
		$link = $this->request->from('lid', 'name', 'sort', 'image', 'url', 'description', 'user');

		/** 更新数据 */
		$this->db->query($this->db->update($this->prefix.'domaintheme')->rows($link)->where('lid = ?', $link['lid']));

		/** 设置高亮 */
		$this->widget('Widget_Notice')->highlight('link-'.$link['lid']);

		/** 提示信息 */
		$this->widget('Widget_Notice')->set(_t('链接 <a href="%s">%s</a> 已经被更新',
		$link['url'], $link['name']), NULL, 'success');

		/** 转向原页 */
		$this->response->redirect(Typecho_Common::url('extending.php?panel=DomainTheme%2Fmanage-domaintheme.php', $this->options->adminUrl));
	}

    public function deleteLink()
    {
    	$this->checkLogin();
        $lids = $this->request->filter('int')->getArray('lid');
        $deleteCount = 0;
        if ($lids && is_array($lids)) {
            foreach ($lids as $lid) {
                if ($this->db->query($this->db->delete($this->prefix.'domaintheme')->where('lid = ?', $lid))) {
                    $deleteCount ++;
                }
            }
        }
        /** 提示信息 */
        $this->widget('Widget_Notice')->set($deleteCount > 0 ? _t('链接已经删除') : _t('没有链接被删除'), NULL,
        $deleteCount > 0 ? 'success' : 'notice');
        
        /** 转向原页 */
        $this->response->redirect(Typecho_Common::url('extending.php?panel=DomainTheme%2Fmanage-domaintheme.php', $this->options->adminUrl));
    }

    public function sortLink()
    {
        $domaintheme = $this->request->filter('int')->getArray('lid');
        if ($domaintheme && is_array($domaintheme)) {
			foreach ($domaintheme as $sort => $lid) {
				$this->db->query($this->db->update($this->prefix.'domaintheme')->rows(array('order' => $sort + 1))->where('lid = ?', $lid));
			}
        }
    }

	public function action()
	{
		$this->db = Typecho_Db::get();
		$this->prefix = $this->db->getPrefix();
		$this->options = Typecho_Widget::widget('Widget_Options');
		$this->on($this->request->is('do=insert'))->insertDomainTheme();
        /*
		$this->on($this->request->is('do=addhanny'))->addHannysBlog();
		$this->on($this->request->is('do=update'))->updateLink();
		$this->on($this->request->is('do=delete'))->deleteLink();
		$this->on($this->request->is('do=sort'))->sortLink();
		$this->response->redirect($this->options->adminUrl);
        */
	}
}
