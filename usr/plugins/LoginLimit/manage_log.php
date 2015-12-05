<?php
include_once 'common.php';
include_once 'header.php';
include_once 'menu.php';
?>

<div class="main">
    <div class="body container">
        <?php include 'page-title.php'; ?>
        <div class="row typecho-page-main manage-metas">

                <div class="col-mb-12 col-tb-12" role="main">                  
                    <?php
                    	date_default_timezone_set('PRC');//设置中国时区
                    	$page_size = 3;
                    	$page = isset($_GET['page'])?(int)$_GET['page'] : 1;
						$prefix = $db->getPrefix();
						$all = $db->fetchAll($db->select('id')->from($prefix.'loginlog'));
						$show_logs = $db->fetchAll($db->select()->from($prefix.'loginlog')->order('add_time', Typecho_Db::SORT_DESC)->page($page,$page_size));
						$total_page = ceil(count($all) / $page_size);
                    ?>
                    <form method="post" name="manage_categories" class="operate-form">
                    <div class="typecho-list-operate clearfix">
                        <div class="operate">
                            <label><i class="sr-only"><?php _e('全选'); ?></i><input type="checkbox" class="typecho-table-select-all" /></label>
                            <div class="btn-group btn-drop">
                                <button class="btn dropdown-toggle btn-s" type="button"><i class="sr-only"><?php _e('操作'); ?></i><?php _e('选中项'); ?> <i class="i-caret-down"></i></button>
                                <ul class="dropdown-menu">
                                    <li><a lang="<?php _e('你确认要删除这些链接吗?'); ?>" href="<?php $options->index('/action/links-edit?do=delete'); ?>"><?php _e('删除'); ?></a></li>
                                </ul>
                            </div>
                            <label><a href="#"><?php _e('清空数据表'); ?></a></label>
                        </div>
                    </div>

                    <div class="typecho-table-wrap">
                        <table class="typecho-list-table typecho-list-operate">
                            <colgroup>
                                <col width="20"/>
								<col width="25%"/>
								<col width=""/>
								<col width="15%"/>
								<col width="25%"/>
                            </colgroup>
                            <thead>
                                <tr>
                                    <th> </th>
									<th><?php _e('登录用户'); ?></th>
									<th><?php _e('登录密码'); ?></th>
									<th><?php _e('客户端IP'); ?></th>
									<th><?php _e('登录时间'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
								<?php if(!empty($show_logs)): $alt = 0;?>
								<?php foreach ($show_logs as $log): ?>
                                <tr id="lid-<?php echo $log['id']; ?>">
                                    <td><input type="checkbox" value="<?php echo $log['id']; ?>" name="lid[]"/></td>
									<td><?php echo $log['try_username']; ?></td>
									<td><?php echo $log['try_password']; ?></td>
									<td><?php echo $log['ip']; ?></td>
									<td><?php echo date('Y-m-d H:i:s', $log['add_time']);?></td>
                                </tr>
                                <?php endforeach; ?>
                                <?php else: ?>
                                <tr>
                                    <td colspan="5"><h6 class="typecho-list-table-title"><?php _e('没有任何失败登录的记录'); ?></h6></td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>

                    </div>
                    </form>
				</div>
        </div>
    </div>
</div>




<?php 
include 'copyright.php';
include 'common-js.php';
include 'footer.php'; 
?>