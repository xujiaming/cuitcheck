<?php 
namespace Home\Model;
use Think\Model;

/**
 * 功能：题库授权的数据模型
 * @author maqingwen
 * @data 2017/4/16
 */
Class TestdatabasePermissionModel extends Model{

	/**
	 * 自定义验证规则
	 */
	protected $_validate = array(
		array('college_id', 'require', array('success'=>false, 'msg'=>'请选择学院')),
		array('permiss_level', 'require', array('success'=>false, 'msg'=>'请选择权限类型')),
		array('testdb_id,college_id', 'checkRepeat', array('success'=>false, 'msg'=>'该学院权限已存在，不可重复添加'), 1, 'callback', 1)
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
	 * 验证新增的数据是否重复
	 */
	public function checkRepeat($data){

		$map['del_flag'] = array('eq', '1');
		$map['testdb_id'] = array('eq', $data['testdb_id']);
		$map['college_id'] = array('eq', $data['college_id']);

		$data = M('TestdatabasePermission')
		->where($map)->select();

		if (sizeof($data) == 0){
			return true;
		}else{
			return false;
		}
	}

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
	 * 获取题库列表
	 * @param $requestPage, $course_id, $college_id, $keyword, $status, $rows
	 * @return 
	 */
	public function getAllList($requestPage, $course_id, $college_id, $keyword, $status, $rows){

		$map['kh_testdatabase.del_flag'] = array('eq', '1');
		
		if ($keyword != ''){
			$map['kh_testdatabase.id|kh_testdatabase.name'] = array('like', '%'.$keyword.'%');
		}
		if ($course_id != ''){
			$map['kh_course.id'] = array('eq', $course_id);
		}
		if ($college_id != ''){
			$map['kh_testdatabase.college_id'] = array('eq', $college_id);
		}
		if ($status != ''){
			$map['kh_testdatabase.status'] = array('eq', $status);
		}

		$limit = ($requestPage-1)*$rows.','.$rows;

		$total = M('Testdatabase')		//获取符合条件的数据条数
		->field('kh_testdatabase.id,kh_testdatabase.name, kh_lession.name as lessionname, kh_college.name as collegename, kh_testdatabase.status, kh_testdatabase.comment, kh_testdatabase.create_date,kh_course.name as coursename')
		->join('JOIN kh_lession ON kh_lession.id = kh_testdatabase.lession_id')
		->join('JOIN kh_course ON kh_course.id = kh_lession.course_id')
		->join('JOIN kh_college ON kh_college.id = kh_testdatabase.college_id')
		->where($map)->count(); 

		// p($map);die();
		$list = M('Testdatabase')
		->field('kh_testdatabase.id, kh_testdatabase.name, kh_lession.name as lessionname,kh_college.name as collegename, kh_testdatabase.status, kh_testdatabase.comment, kh_testdatabase.create_date, kh_course.name as coursename')
		->join('JOIN kh_lession ON kh_lession.id = kh_testdatabase.lession_id')
		->join('JOIN kh_course ON kh_course.id = kh_lession.course_id')
		->join('JOIN kh_college ON kh_college.id = kh_testdatabase.college_id')
		->where($map)
		->limit($limit)->select();
		// p($list);die();
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
	 * 功能：获取所有学院（超级管理员专用）
	 * @return list
	 */
	public function getCollegeList(){
		$map['del_flag'] = array('eq', '1');
		$list = M('College')
		->field('id, name')
		->where($map)->select();

		return $list;
	}

	/**
	 * 功能：获得指定题库下拥有权限的全部学院
	 * @param testdb_id
	 * @return list
	 */
	public function getPermissCollege($testdb_id, $keyword){

		$map['kh_testdatabase_permission.del_flag'] = array('eq', '1');
		if ($keyword != ''){
			$map['kh_college.name'] = array('like', '%'.$keyword.'%');
		}
		$map['kh_testdatabase_permission.testdb_id'] = array('eq', $testdb_id);
		$map['kh_dict.type'] = array('eq', 'testDBPermiss');

		$list = M('TestdatabasePermission')
		->field('kh_testdatabase_permission.college_id, kh_college.name, kh_dict.label, kh_testdatabase_permission.create_by, kh_testdatabase_permission.create_date')
		->join('JOIN kh_college ON kh_testdatabase_permission.college_id = kh_college.id')
		->join('JOIN kh_dict ON kh_dict.value = kh_testdatabase_permission.permiss_level')
		->where($map)->select();

		return $list;
	}

	/**
	 * 功能：根据学院id，题库id获取学院的权限信息
	 * @param college_id, testdb_id
	 * @return data
	 */
	public function getTestDBPer($college_id, $testdb_id){

		$map['del_flag'] = array('eq', '1');
		$map['college_id'] = array('eq', $college_id);
		$map['testdb_id'] = array('eq', $testdb_id);

		$data = M('TestdatabasePermission')
		->field('testdb_id, college_id, permiss_level')
		->where($map)
		->find();

		return $data;
	}

	/**
	 * 添加题库权限信息
	 */
	public function addTestDBPer(){

		//检查数据库中是否存在已经删除的同类数据，如果有，则恢复
		$map['college_id'] = array('eq', $_POST['college_id']);
		$map['testdb_id'] = array('eq', $_POST['testdb_id']);
		$map['del_flag'] = array('eq', '0');
		$data = M('TestdatabasePermission')
		->where($map)->select();

		if (sizeof($data) == 0){
			if (!$this->create($_POST,1)){
				return $this->getError();
			}else{
				$this->add();
				return $this->getret(true);
			}
		}else{
			$form['del_flag'] = '1';
			$form['permiss_level'] = $_POST['permiss_level'];
			$form['update_date'] = $this->getTime();
			$form['update_by'] = $this->getAccount();
			$this->where($map)->save($form);
			return $this->getret(true);
		}

		
	}

	/**
	 * 修改题库权限信息
	 */
	public function updateTestDBPer(){
		if (!$this->create($_POST, 2)){
			return $this->getError();
		}else{
			$map['testdb_id'] = array('eq', $_POST['testdb_id']);
			$map['college_id'] = array('eq', $_POST['college_id']);
			$this->where($map)->save();
			return $this->getret(true);
		}
	}

	/**
	 * 删除题库权限
	 */
	public function deleteTestDBPer($testdb_id, $college_id){
		$map['testdb_id'] = array('eq', $testdb_id);
		$map['college_id'] = array('eq', $college_id);
		$form['del_flag'] = '0';
		$form['update_date'] = $this->getTime();
		$form['update_by'] = $this->getAccount();
		$this->where($map)->save($form);

		$data['success'] = true;
		$data['msg'] = '删除成功';

		return $data;
	}

	private function getret($status){
		$arr['success'] = $status;
		return $arr;
	}
}