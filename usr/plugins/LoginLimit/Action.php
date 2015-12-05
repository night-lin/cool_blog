<?php
class LoginLimit_Action extends Typecho_Widget implements Widget_Interface_Do {

	private $db;
	private $options;
	private $prefix;

	public function action() {
		$this->db = Typecho_Db::get();
		$this->prefix = $this->db->getPrefix();
		$this->options = Typecho_Widget::widget('Widget_Options');
		$this->on($this->request->is('do=clearAll'))->clearAll();
		$this->on($this->request->is('do=deleteLog'))->deleteLog();
		$referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'javascript:history.back(-1);';
		$this->response->redirect($referer);
	}

	public function clearAll() {
		//清空数据表
		$sql = 'truncate table '.$this->prefix.'loginlog';
		if($this->db->query($sql) !== false) {
			$this->widget('Widget_Notice')->set(_t("数据表已清空"));
		}
	}

	public function deleteLog() {
		//删除选中的记录
		$lids = $this->request->filter('int')->getArray('id');
        $deleteCount = 0;
        if ($lids && is_array($lids)) {
            foreach ($lids as $lid) {
                if ($this->db->query($this->db->delete($this->prefix.'loginlog')->where('id = ?', $lid))) {
                    $deleteCount ++;
                }
            }
        }
        /** 提示信息 */
        $this->widget('Widget_Notice')->set($deleteCount > 0 ? _t('记录已经删除') : _t('没有记录被删除'), NULL,
        $deleteCount > 0 ? 'success' : 'notice');
        
        /** 转向原页 */
        //$this->response->redirect(Typecho_Common::url('extending.php?panel=Links%2Fmanage-links.php', $this->options->adminUrl));
	}



}