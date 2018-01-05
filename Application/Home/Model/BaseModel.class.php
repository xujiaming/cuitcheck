<?php 
namespace Home\Model;
use Think\Model;
/**
 * 模型基类
 * author :taolei
 */
class BaseModel extends Model {
	protected $_auto = array(
	array('create_date', 'getTime', 1, 'callback'),		//新增数据时将创建时间设为当前时间
	array('update_date', 'getTime', 2, 'callback'),		//更新数据时将更新时间设为当前时间
	array('create_by', 'getAccount', 1, 'callback'),    //新增数据时将创建人设为当前用户
	array('update_by', 'getAccount', 2, 'callback'),	//更新时将更新人设为当前用户
	array('del_flag', 'getDel_flag',1, 'callback'),
	);

	public function getDel_flag(){
	    return 1;
	}
	/**
	 * 获取当前时间
	 * 
	 */
	public function getTime(){
		return date('Y-m-d H:i:s', time());
	}
	/**
	 * 获取当前登录用户account
	 */
	public function getAccount(){
		$account = session('account');
		return $account;
	}

}
?>