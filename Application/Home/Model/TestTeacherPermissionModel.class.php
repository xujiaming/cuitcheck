<?php 
namespace Home\Model;
use Think\Model;

/**
 * 教师出题权限的数据模型
 * @author maqingwen
 */
class TestTeacherPermissionModel extends Model{


	/**
	 * 自定义验证规则
	 */
	protected $_validate = array(
		array('teacher_id', 'require', array('success'=>false, 'msg'=>'请选择教师')),
		array('start_time', 'require', array('success'=>false, 'msg'=>'请选择开始时间')),
		array('end_time', 'require', array('success'=>false, 'msg'=>'请选择结束时间')),
		array('teacher_id', 'checkRepeat', array('success'=>false, 'msg'=>'该教师权限未过期，不可重复添加'), 1, 'callback', 1)
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
	 * 验证添加的新教师是否时间重复
	 */
	public function checkRepeat($data){
		$map['del_flag'] = array('eq', '1');
		$map['teacher_id'] = array('eq', $data['teacher_id']);
		$map['start_time'] = array('elt', date("Y-m-d H:i:s"));
		$map['end_time'] = array('egt', date("Y-m-d H:i:s"));

		$data = M('TestTeacherPermission')
		->where($map)->select();

		if(sizeof($data) == 0){
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
	 * 获取有权限的教师列表
	 * @param $requestPage, $keyword, $rows
	 * @return
	 */
	public function getAllList($requestPage, $keyword, $rows){

		$map['kh_test_teacher_permission.del_flag'] = array('eq', '1');

		if ($keyword != ''){
			$map['kh_sysuser.name'] = array('like', '%'.$keyword.'%');
		}

		if (session('accInfo.role') == 3){
			$map['kh_sysuser.dept_id'] = array('eq', session('accInfo.dept_id'));
		}

		$limit = ($requestPage-1)*$rows.','.$rows;

		$total = M('TestTeacherPermission')
		->field('kh_test_teacher_permission.id, kh_sysuser.name as teacherName, kh_college.name as collegeName, kh_test_teacher_permission.start_time, kh_test_teacher_permission.end_time')
		->join('JOIN kh_sysuser ON kh_sysuser.id = kh_test_teacher_permission.teacher_id')
		->join('JOIN kh_college on kh_college.id = kh_sysuser.dept_id')
		->where($map)->count();
			// dump($total);die();
		$list = M('TestTeacherPermission')
		->field('kh_test_teacher_permission.id, kh_sysuser.name as teacherName, kh_college.name as collegeName, kh_test_teacher_permission.start_time, kh_test_teacher_permission.end_time')
		->join('JOIN kh_sysuser ON kh_sysuser.id = kh_test_teacher_permission.teacher_id')
		->join('JOIN kh_college on kh_college.id = kh_sysuser.dept_id')
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
	 * 根据id获取权限的详细信息
	 * @param $id
	 */
	public function getPerById($id){

		$map['kh_test_teacher_permission.del_flag'] = array('eq', '1');
		$map['kh_test_teacher_permission.id'] = array('eq', $id);

		$data = M('TestTeacherPermission')
		->field('kh_test_teacher_permission.id, kh_test_teacher_permission.teacher_id, kh_college.name as collegeName, kh_test_teacher_permission.start_time, kh_test_teacher_permission.end_time')
		->join('JOIN kh_sysuser ON kh_sysuser.id = kh_test_teacher_permission.teacher_id')
		->join('JOIN kh_college on kh_college.id = kh_sysuser.dept_id')
		->where($map)
		->find();

		return $data;
	}

	/**
	 * 获取教师列表
	 * @param
	 */
	public function getTeacherList(){

		$map['kh_sysuser.del_flag'] = array('eq', '1');
		$map['kh_sysuser.role'] = array('eq', '4');
		$map['kh_sysuser.status'] = array('eq', '1');
		if (session('accInfo.role') == 3){
			$map['kh_sysuser.dept_id'] = array('eq', session('accInfo.dept_id'));
		}

		$list = M('Sysuser')
		->field('kh_sysuser.id, kh_sysuser.name, kh_college.name as collegename')
		->join('JOIN kh_college ON kh_sysuser.dept_id = kh_college.id')
		->where($map)
		->select();

		return $list;
	}

	/**
	 * 添加用户的事件处理
	 */
	public function addTestTeaPer(){

		if (!$this->create()){
			return $this->getError();
		}else{
			$this->add();
			return $this->getret(true);
		}
	}

	/**
	 * 点击删除的事件处理
	 */
	public function deleteTestTeaPer($id){
		$map['id'] = array('eq', $id);
		$form['del_flag'] = '0';
		$form['update_date'] = $this->getTime();
		$form['update_by'] = $this->getAccount();
		$this->where($map)->save($form);
		$data = array('success'=>true, 'msg'=>'删除成功');
		return $data;
	}
	
	private function getret($status){
		$arr['success'] = $status;
		return $arr;
	}

	/**
	 * 公用接口，检查教师是否拥有操作正式考试的权限
	 * @return true拥有权限，false没有权限
	 */
	public function checkPermission(){

		$map['del_flag'] = array('eq', '1');
		$map['teacher_id'] = array('eq', session('accInfo.id'));
		$map['start_time'] = array('elt', date("Y-m-d H:i:s"));
		$map['end_time'] = array('egt', date("Y-m-d H:i:s"));

		$data = M('TestTeacherPermission')
		->where($map)->select();

		if(sizeof($data) == 0){
			return false;
		}else{
			return true;
		}
	}
}