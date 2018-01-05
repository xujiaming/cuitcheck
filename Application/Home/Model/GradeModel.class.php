<?php 
namespace Home\Model;
use Think\Model;

/**
 * 年级管理数据模型
 */
Class GradeModel extends Model{


	/**
	 * 自定义验证规则
	 */
	protected $_validate = array(
		array('name', 'require', array('success'=>false, 'msg'=>'请填写年级名称')),	//验证学院名称不能为空
		array('name', '', array('success'=>false, 'msg'=>'学院名称已存在，请重新填写！'), 1, 'unique', 3)
	);

	/**
	 * 使用自动完成来填写部分默认数据项
	 */
	protected $_auto = array(
		array('create_date', 'getTime', 1, 'callback'),		//新增数据时将创建时间设为当前时间
		array('update_date', 'getTime', 2, 'callback'),		//更新数据时将更新时间设为当前时间
		array('create_by', 'getAccount', 1, 'callback'),//新增数据时将创建人设为当前用户account
		array('update_by', 'getAccount', 2, 'callback')	//更新时将更新人设为当前用户account
	);

	/**
	 * 获取session中的用户
	 */
	public function getAccount(){
		$account = session('account');

		return $account;
	}

	/**
	 * 获取当前时间
	 * 
	 */
	public function getTime(){
		return date('Y-m-d H:i:s', time());
	}

	/**
	 * 根据筛选条件获取全部数据
	 */
	public function getAllList($requestPage, $beginDate, $endDate, $keyword, $rows){

		$map['del_flag'] = array('eq', '1');
		if ($keyword != ''){
			$map['id|name'] = array('like', '%'.$keyword.'%');
		}
		if ($beginDate != '' && $endDate != ''){
			$map['create_date'] = array(array('egt', $beginDate), array('elt', $endDate));
		}

		$limit = ($requestPage-1)*$rows.','.$rows;

		$total = M('Grade')		//获取全部符合条件的数据条数
		->field('id, name, create_date, comment')
		->where($map)->count();

		$list = M('Grade')
		->field('id, name, create_date, comment')
		->where($map)
		->limit($limit)->select();

		$pages = 0;
		if ($total%$rows == 0){
			$pages = $total/$rows;
		}else{
			$pages = intval($total/$rows + 1);
		}

		$data = array(
			"pages" => $pages,
			"list" => $list
		);

		return $data;
	}
	/**
	 * 获取添加班级时获取的数据
	 */
	
	public function getgradelist(){

		$map['del_flag'] = array('eq', '1');
		$data = M('Grade')
		->field('id, name')
		->where($map)
		->limit($limit)->select();

		return $data;
	}



	/**
	 * 根据id获取年级信息
	 */
	public function getGradeById($id){

		$map['id'] = array('eq', $id);
		$map['del_flag'] = array('eq', '1');

		$data = M('Grade')
		->field('id, name, create_date, comment')
		->where($map)
		->find();

		return $data;
	}

	private function getret($status){
		$arr['success'] = $status;
		return $arr;
	}

	/**
	 * 修改年级信息
	 */
	public function updateGrade(){
		if (!$this->create()){
			return $this->getError();
		}else{
			$this->save();
			return $this->getret(true);
		}
	}

	/**
	 * 添加年级信息
	 */
	public function addGrade(){
		if (!$this->create()){
			return $this->getError();
		}else{
			$this->add();
			return $this->getret(true);
		}
	}

	/**
	 * 删除学院信息
	 */
	public function daleteGrade($id){
		$info = $this->checkisset($id);
		if ($info['status']){
			$data['success'] = false;
			$data['msg'] = $info['msg'];
		}else{

			$form['id'] = $id;
			$form['del_flag'] = '0';
			$form['update_date'] = $this->getTime();
			$form['update_by'] = $this->getAccount();
			$this->save($form);

			$data['success'] = true;
			$data['msg'] = '删除成功';
		}

		return $data;
	}

	/**
	 * 检查该年级下方是否有其他信息
	 * @param id
	 * @return boolean//有返回true，无返回false
	 */
	public function checkisset($id){
		$map['grade_id'] = $id;
		$map['del_flag'] = 1;
		$class_is_exist = M('class')->where($map)->find();	// 行政班级
		$courseclass_is_exist = M('courseclass')->where($map)->find();	// 行课班级
		if($class_is_exist != NULL) {
			$msg['status'] = true;
			$msg['msg'] = "该年级下还有行政班级信息,暂时无法删除";
		}
		else if ($courseclass_is_exist != NULL) {
			$msg['status'] = true;
			$msg['msg'] = "该年级下还有行课班级信息,暂时无法删除";
		}
		else{
			$msg['status'] = false;
		}
		return $msg;
	}

}