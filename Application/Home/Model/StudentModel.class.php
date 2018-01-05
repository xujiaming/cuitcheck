<?php 
namespace Home\Model;
use Think\Model;
	/**
	 * @function: 学生表模型
	 * @Author: 梁轩豪
	 * @DateTime:  2017-03-11 10:37:51
	 */
class StudentModel extends Model {

	/**
	 * 自定义验证规则
	 */
	protected $_validate = array(
		array('account','',array('success'=>false,'msg'=>'学生账号已经存在！'),0,'unique',3),
		array('phone','checkPhone',array('success' => false,'msg'=>'手机格式不正确！'),2,'callback'),//手机号，非必需字段，有值时进行验证
	 	 
		);
	/*
	自动填充部分数据
	 */
	protected $_auto = array(
		array('create_date', 'getTime', 1, 'callback'),		//新增数据时将创建时间设为当前时间
		array('update_date', 'getTime', 2, 'callback'),		//更新数据时将更新时间设为当前时间
		array('create_by', 'getAccount', 1, 'callback'),//新增数据时将创建人设为当前用户account
		array('update_by', 'getAccount', 2, 'callback'),	//更新时将更新人设为当前用户account
		array('password','setPass',1,'callback'),
		);
	/*
	验证手机号格式是否正确
	 */
	public function checkPhone($phoneNum) {
		if(!is_numeric($phoneNum)) {
			return false;
		}
		return preg_match('#^13[\d]{9}$|^14[5,7]{1}\d{8}$|^15[^4]{1}\d{8}$|^17[0,6,7,8]{1}\d{8}$|^18[\d]{9}$#', $phoneNum) ? true : false;
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
	/*
	设置初始密码为学号
	 */
	 public function setPass(){
        return md5(sha1($_POST['account']));
    }

	/*
	返回信息预处理
	 */
	public function packResult($flag) {
		$data = array(
			'success' => $flag,
			'msg' => '操作成功！',
			);
		if(!$flag) {
			$data['msg'] = '操作失败！';
		}
		return $data;
	}
	/**
	 * @function: 根据条件查询学生信息
	 * @Author:梁轩豪
	 * @DateTime:  2017-03-12 10:56:53
	 * @return     [type]          
	 */
	public function getAllList($requestPage, $keyword, $rows,$stuArray, $dept='') {
		//如果是超级管理员则不需要进行判断
		$accInfo = session('accInfo');
		$map = array();
		//获取学院班级等信息
		if($stuArray['college_id'] != '') {
			$map['kh_student.dept_id'] = $stuArray['college_id'];
		}
		if($stuArray['major_id'] != '') {
			$map['kh_student.major_id'] = $stuArray['major_id'];
		}
		if($stuArray['grade_id'] != '') {
			$map['kh_student.grade_id'] = $stuArray['grade_id'];
		}
		if($stuArray['class_id'] != '') {
			$map['kh_student.class_id'] = $stuArray['class_id'];
		}
		

		//获取关键词信息
		if($keyword != '') {
			$map['kh_student.id|kh_student.account|kh_student.name'] = array('like', '%'.$keyword.'%');
		}
		if($dept != '') {
			$map['kh_student.dept_id'] = $dept;
		}
		$map['kh_student.del_flag'] = array('eq', '1');
		$limit = ($requestPage-1)*$rows.','.$rows;
		//查询符合条件的条数
		$total = M('Student')->where($map)->count();

		$list = M('Student')->join(
			'LEFT join kh_college ON kh_college.id = kh_student.dept_id
			 LEFT join kh_major ON kh_major.id = kh_student.major_id 
			 LEFT join kh_grade ON kh_grade.id = kh_student.grade_id 
			 LEFT join kh_class ON kh_class.id = kh_student.class_id
			')
		->field(array('kh_student.id'=>'stu_id','account','kh_student.name'=>'stu_name','sex','photo','email','phone','kh_student.create_date'=>'stu_create_date','kh_student.remarks'=>'stu_remarks','kh_student.status'=>'stu_status','kh_student.del_flag'=>'stu_del_flag',
			'kh_college.name'=>'col_name','kh_major.name'=>'maj_name','kh_grade.name'=>'gra_name','kh_class.name'=>'cla_name'))
		->where($map)->order('account asc')
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
	 * @function: 添加学生
	 * @Author:梁轩豪
	 * @DateTime:  2017-03-12 15:05:58
	 */
	public function addStu() {
		if(!$this->create()) {
			return $this->getError();
		}else{
			if($this->add() === false) {
				$info = $this->packResult(false);
			}else {
				$info = $this->packResult(true);
			}
			return $info;
		}
	}
	/**
	 * @function: 通过id获取学生信息
	 * @Author:梁轩豪
	 * @DateTime:  2017-03-12T15:26:37+0800
	 */
	public function getStudentById($id) {
		$map['kh_student.id'] = array('eq', $id);
		$map['kh_student.del_flag'] = array('eq', '1');
		$data = $list = M('Student')->join(
			'LEFT join kh_college ON kh_college.id = kh_student.dept_id
			 LEFT join kh_major ON kh_major.id = kh_student.major_id 
			 LEFT join kh_grade ON kh_grade.id = kh_student.grade_id 
			 LEFT join kh_class ON kh_class.id = kh_student.class_id
			')
		->field(array('kh_student.id'=>'stu_id','account','kh_student.name'=>'stu_name','sex','photo','email','phone','kh_student.create_date'=>'stu_create_date','kh_student.remarks'=>'stu_remarks','kh_student.status'=>'stu_status','kh_student.del_flag'=>'stu_del_flag',
			'kh_college.id'=>'col_id','kh_college.name'=>'col_name','kh_major.id'=>'maj_id','kh_major.name'=>'maj_name','kh_grade.id'=>'gra_id','kh_grade.name'=>'gra_name','kh_class.id'=>'cla_id','kh_class.name'=>'cla_name'))
		->where($map)->find();
		return $data;
	}
	/**
	 * @function: 编辑学生信息
	 * @Author:梁轩豪
	 * @DateTime:  2017-03-13T11:17:37+0800
	 * @return    json
	 */
	public function editStu() {
		if(!$this->create()) {
			return $this->getError();
		}else{
			if($this->save() === false) {
				$info = $this->packResult(false);
			}else {
				$info = $this->packResult(true);
			}
			return $info;
		}
	}
	/**
	 * @function:删除学生，PS：在这里只是将del_flag改为0不	显示
	 * @Author:梁轩豪
	 * @DateTime:  2017-03-13T11:26:28+0800
	 * @return    
	 */
	public function deleteStudent($id) {
		$map['id'] = $id;
		$map['del_flag'] = '0';
		$map['update_by'] = $this->getAccount();
		$map['update_date'] = $this->getTime();
		$this->save($map);
		$info = $this->packResult(true);
		return $info;
	}
	/**
	 * @function: 重置密码，默认为学生学号
	 * @Author:梁轩豪
	 * @DateTime:  2017-03-13T12:22:49+0800
	 * @return    
	 */
	public function reset() {
		$stu_info = M('Student')->where(array('id' => I('id')))->field('account')->find();
		$map['password'] = md5(sha1($stu_info['account']));
		if($this->where(array('id' => I('id')))->save($map) === false) {
			$info = $this->packResult(false);
		}else {
			$info = $this->packResult(true);
		}
		return $info;
	}
}
?>