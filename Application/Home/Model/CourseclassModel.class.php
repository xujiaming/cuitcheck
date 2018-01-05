<?php 
/**
 * 行政班级管理的数据模型
 * 作者：luochao
 */
namespace Home\Model;
use Think\Model;
Class courseclassModel extends Model{

	/**
	 * 自定义验证规则
	 */
	protected $_validate = array(
		array('stat_time', 'require', array('success'=>false, 'msg'=>'请填写开始日期')),
		array('end_time', 'require', array('success'=>false, 'msg'=>'请填写结束日期')),
		array('name', 'require', array('success'=>false, 'msg'=>'请填写班级名称')),
		array('name', '', array('success'=>false, 'msg'=>'班级名称已存在，请重新填写！'), 1, 'unique', 3),
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
	public function getAllList($requestPage, $beginDate, $endDate, $keyword, $rows,$gradekey){

		$account=session('account');
		// p($account);die();
		$map2['account']=array('eq',$account);
		// dump($college_id);die();
		$college_id=M('sysuser')->field('id,role,dept_id')->where($map2)->find();
		// dump($college_id);die();
		if($college_id['role']==1){
			$map['kh_courseclass.del_flag'] = array('eq', '1');
		}elseif ($college_id['role']==4) {
			$map['kh_courseclass.teacher_id']= array('eq' , $college_id['id']);
		}else{
			$map['kh_courseclass.college_id']= array('eq' , $college_id['dept_id']);
		}
		// dump($map);die();
		
		if ($keyword != ''){
			$map['kh_courseclass.id|kh_courseclass.name'] = array('like', '%'.$keyword.'%');
		}
		if ($gradekey != ''){
			$map['kh_courseclass.grade_id'] = array('like', '%'.$gradekey.'%');
		}
		if ($beginDate != '' && $endDate != ''){
			$map['create_date'] = array(array('egt', $beginDate), array('elt', $endDate));
		}

		$limit = ($requestPage-1)*$rows.','.$rows;

		$total = M('courseclass')		//获取全部符合条件的数据条数
		->field('id, name, create_date')
		->where($map)->count();

		$list = M('courseclass') //多表联合查询
		->join('LEFT join kh_college ON kh_college.id = kh_courseclass.college_id
				LEFT join kh_grade ON kh_grade.id = kh_courseclass.grade_id
				LEFT join kh_lession ON kh_lession.id = kh_courseclass.lession_id
				LEFT join kh_sysuser ON kh_sysuser.id = kh_courseclass.teacher_id
			')
		->field('kh_courseclass.id,
				 kh_courseclass.name,
				 kh_courseclass.start_time,
				 kh_courseclass.end_time,
		 		 kh_college.name as college_name,
		 		 kh_lession.name as lession_name,
		 		 kh_lession.id as lession_id,
		 		 kh_college.id as college_id,
		 	     kh_grade.name as grade_name,
		 	     kh_sysuser.name as teacher_name
		 	     ')
		->where($map)
		->order('college_id asc')
		->limit($limit)->select();
		// dump($list);die();
		$pages = 0;
		if ($total%$rows == 0){
			$pages = $total/$rows;
		}else{
			$pages = intval($total/$rows + 1);
		}

		$data = array(
			"pages" => $pages,
			"list" => $list,
		);

		return $data;
	}


	/**
	 * 修改班级信息
	 */
	public function updatecourseclass(){
		if (!$this->create()){
			return $this->getError();
		}else{
			$this->save();
			return $this->getret(true);
		}
	}
	/**
	 * 添加班级信息
	 */
	public function add_courseclass(){
		if (!$this->create()){
			return $this->getError();
		}else{
			$this->add();
			return $this->getret(true);
		}
	}

	private function getret($status){
		$arr['success'] = $status;
		return $arr;
	}

	/**
	 * 删除班级信息
	 */
	public function daletecourseclass($id){
		$data1['courseclass_id'] = $id;
		$map['account'] = M('class_student') 
		->field('account')
		->where($data1)
		->select();
		if(sizeof($map['account'])==0){
			$form['id'] = $id;
			$form['del_flag'] = '0';
			$form['update_date'] = $this->getTime();
			$form['update_by'] = $this->getAccount();
			M('courseclass')->save($form);
			$data['success'] = true;
			$data['msg'] = '删除成功';
		}else{
			$data['msg'] = '删除失败，该班还有学生';
		}
		return $data;
	}
	public function getcourseClassId($id){

		$map['kh_courseclass.id'] = array('eq', $id);
		$map['kh_courseclass.del_flag'] = array('eq', '1');

		$data = M('courseclass') //多表联合查询
		->join('LEFT join kh_college ON kh_college.id = kh_courseclass.college_id
				LEFT join kh_grade ON kh_grade.id = kh_courseclass.grade_id
				LEFT join kh_lession ON kh_lession.id = kh_courseclass.lession_id
				LEFT join kh_sysuser ON kh_sysuser.id = kh_courseclass.teacher_id
			')
		->field('kh_courseclass.id,
				 kh_courseclass.name,
				 kh_courseclass.start_time,
				 kh_courseclass.end_time,
		 		 kh_college.name as college_name,
		 	     kh_grade.name as grade_name,
		 	     kh_lession.id as lession_id,
		 	     kh_lession.name as lession_name,
				 kh_sysuser.name as teacher_name
		 	     ')
		->where($map)
		->find();

		return $data;
	}
	/**
	 * 行课班级详细展示
	 */
	public function getallcourseclass($id,$keyword){
		$data1['courseclass_id'] = $id;
		// dump($map);die();
		// if ($keyword != ''){
		// 	$map['name'] = array('like', '%'.$keyword.'%');
		// }
		$map['account'] = M('class_student') 
		->field('account')
		->where($data1)
		->select();
		// p($map);die();
		$data=array();
		if(sizeof($map['account'])!=0){
		if ($keyword != ''){
			$map2['name'] = array('like', '%'.$keyword.'%');
			for($i=0;$i<sizeof($map['account']);++$i){ //遍历条件查询
				$where3=array($map['account'][$i],$map2,'_logic'=>'and');
				$rowdata=M('student')
				->field('account,name,sex')
				->where($where3)
				->find();
				if(sizeof($rowdata)!=0){
				array_push($data,$rowdata);
			}
			}
			// dump($data);die();
		}else{
			for($i=0;$i<sizeof($map['account']);++$i){ //遍历条件查询
				$rowdata=M('student')
				->field('account,name,sex')
				->where($map['account'][$i])
				->find();
				array_push($data,$rowdata);
			}
		}
		}
		// dump($data);die();
		return $data;

	}
	/**
	 * 获取添加班级时获取的数据
	 */
	
	public function getcollgelist(){

		$map['del_flag'] = array('eq', '1');
		$data = M('College')
		->field('id, name')
		->where($map)
		->select();

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


}