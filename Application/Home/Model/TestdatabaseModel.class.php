<?php 
/**
 * 题库管理的数据模型
 * @author maqingwen
 * @日期 2017/3/23
 */
namespace Home\Model;
use Think\Model;
Class TestdatabaseModel extends Model{

	/**
	 * 自定义验证规则
	 */
	protected $_validate = array(
		array('name', 'require', array('success'=>false, 'msg'=>'请填写题库名称')),	//验证题库名称不能为空
		array('name', '', array('success'=>false, 'msg'=>'题库名称已存在，请重新填写！'), 1, 'unique', 3)
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

	//根据题库id获取题库操作权限
	public function getDBtype($testdatabase_id){
		$dept_id=getdeptId();
		$map['testdb_id']=array('eq',$testdatabase_id);
		$map['college_id']=array('eq',$dept_id);
		// p($dept_id);die();
		//获取字段值
		$types= M('Testdatabase_permission')->where($map)->getField('permiss_level');
		// $type=array_column($types,'permiss_level');
		return $types;

	}
	/**
	 * 获取符合条件的全部list
	 */
	public function getAllList($requestPage, $keyword, $testtype_id, $course_id, $lession_id, $college_id, $status, $rows){

		$map['kh_testdatabase.del_flag'] = array('eq', '1');
		if ($keyword != ''){
			$map['kh_testdatabase.id|kh_testdatabase.name'] = array('like', '%'.$keyword.'%');
		}

		//  根据试卷类型查找
		if ($testtype_id != '') {
			$map['kh_testdatabase.type_id'] = array('eq', $testtype_id);
		}

		// 根据学科查找
		if ($course_id != ''){
			$lessionList = M('lession')->field('id')->where(array('course_id'=>$course_id))->select();
			if(!empty($lessionList)){
				$map['kh_testdatabase.lession_id'] = array('in', $lessionList[0]);
				// var_dump($lessionList);
				// exit();
			}
			// 不存在该学科的试卷
			else{
				$map['kh_testdatabase.lession_id'] = "";
			}
		}
		if ($lession_id != ''){
			$map['kh_testdatabase.lession_id'] = array('eq', $lession_id);
		}
		if ($college_id != ''){
			$map['kh_testdatabase.college_id'] = array('eq', $college_id);
		}
		if ($status != ''){
			$map['kh_testdatabase.status'] = array('eq', $status);
		}
		if (session('accInfo.role') != 1 && session('accInfo.role') != 2){
			$dept_id=getdeptId();
			// p($dept_id);die();
			// 从题库权限表中获取拥有该题库权限的数据
	        $map3['kh_Testdatabase_permission.del_flag'] = array('eq', '1');
	        $map3['kh_Testdatabase_permission.college_id'] = array('eq', $dept_id);
	        $testid = M('Testdatabase_permission')
	        ->field('testdb_id')
	        ->where($map3)->select();
	         
	        $testids=array_column($testid,'testdb_id');
	        if(sizeof($testids)!=0){
	     	   $map['kh_testdatabase.id']=array('in',$testids);
	        }
	        // $map['kh_testdatabase.college_id']=array('eq',$dept_id);
		}

		$limit = ($requestPage-1)*$rows.','.$rows;

		// p($map);die();
		$total = M('Testdatabase')		//获取符合条件的数据条数
		->field('kh_testdatabase.id,kh_testdatabase.name, kh_lession.name as lessionname, kh_college.name as collegename, kh_testdatabase.status, kh_testdatabase.comment, kh_testdatabase.create_date,kh_course.name as coursename, 
			kh_dict.label as testtype')
		->join('JOIN kh_dict ON kh_dict.type = "testType" AND kh_dict.value = kh_testdatabase.type_id')
		->join('JOIN kh_lession ON kh_lession.id = kh_testdatabase.lession_id')
		->join('JOIN kh_course ON kh_course.id = kh_lession.course_id')
		->join('JOIN kh_college ON kh_college.id = kh_testdatabase.college_id')
		->where($map)->count(); 

//		 p($total);die();

		$list = M('Testdatabase')
		->field('kh_testdatabase.id, kh_testdatabase.name, kh_lession.name as lessionname,kh_college.name as collegename,kh_college.id as college_id, kh_testdatabase.status, kh_testdatabase.comment, kh_testdatabase.create_date, kh_course.name as coursename,
			kh_dict.label as testtype')
		->join('JOIN kh_dict ON kh_dict.type = "testType" AND kh_dict.value = kh_testdatabase.type_id')
		->join('JOIN kh_lession ON kh_lession.id = kh_testdatabase.lession_id')
		->join('JOIN kh_course ON kh_course.id = kh_lession.course_id')
		->join('JOIN kh_college ON kh_college.id = kh_testdatabase.college_id')
		->where($map)
		->limit($limit)->select();

//		 p($list);die();
		foreach($list as $key => $value){
			$list[$key]['type'] =  $this->getDBtype($list[$key]['id']);
		}

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
	 * 获取全部学院名称和id,下拉列表用，超级管理员会获取全部学院的名称和id，普通管理员只获取拥有权限的名称和id
	 */
	public function getCollegeList(){

		$map['del_flag'] = array('eq', '1');
		if (session('accInfo.role') != 1 && session('accInfo.role') != 2){
			$dept_id=getdeptId();

			$map['id'] = array('eq', $dept_id);  //根据题库id集合获取对应的创建学院集合;
		}

		$list = M('College')
		->field('id, name')
		->where($map)->select();
		// p($list);die();
		return $list;
	}

	/**
	 * 根据id获得题库
	 * @param id
	 * @return testDB
	 */
	public function getTestDBById($id){
		$map['kh_testdatabase.id'] = array('eq', $id);
		$map['kh_testdatabase.del_flag'] = array('eq', '1');

		$data = M('Testdatabase')
		->field('kh_testdatabase.id, kh_testdatabase.name, kh_testdatabase.type_id, kh_testdatabase.lession_id,
				kh_testdatabase.college_id, kh_testdatabase.status, kh_testdatabase.comment, kh_course.id as course_id')
		->join('JOIN kh_lession ON kh_testdatabase.lession_id = kh_lession.id')
		->join('JOIN kh_course ON kh_course.id = kh_lession.course_id')
		->where($map)
		->find();
		// var_dump($data);exit();
		return $data;
	}

	private function getret($status){
		$arr['success'] = $status;
		return $arr;
	}

	/**
	 * 添加题库信息
	 */
	public function addTestDB(){
		if (session('accInfo.role') != 1){
			$_POST['college_id'] = session('accInfo.dept_id');
		}
		if (!$this->create()){
			return $this->getError();
		}else{
			$testdb_id = $this->add();
			$_POST['testdb_id'] = $testdb_id;
			$_POST['permiss_level'] = 2;
			$data = D('TestdatabasePermission')->addTestDBPer();

			return $this->getret(true);
		}
	}

	/**
	 * 修改题库信息
	 */
	public function updateTestDB(){
		$create_college = $this->getTestDBById($_POST['id']);
		$_POST['college_id'] = $create_college['college_id'];
		if (!$this->checkPermiss($_POST['id'], 2)  && session('accInfo.role') != 1){
			$data['success'] = false;
			$data['msg'] = '没有操作权限';

			return $data;
		}else if (!$this->create()){
			return $this->getError();
		}else{
			$this->save();
			return $this->getret(true);
		}
	}

	/**
	 * 删除题库信息
	 */
	public function daleteTestDB($id){
		if (!$this->checkPermiss($id, 2) && session('accInfo.role') != 1){
			$data['success'] = false;
			$data['msg'] = '没有操作权限';
		}else if ($this->checkisset($id)){
			$data['success'] = false;
			$data['msg'] = '该题库下还有其他信息，暂时无法删除';
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
	 * 检查该题库下方是否有其他信息
	 * @param id
	 * @return boolean//有返回true，无返回false
	 */
	public function checkisset($id){


		return false;
	}

	/**
	 * 功能：检查用户所对应的学院是否拥有题库对应权限
	 * @author maqingwen
	 * @param testdb_id     permiss_level
	 * @return true/false true表示拥有权限，false表示没有权限
	 * 说明：permiss_level：1仅可读，2可读可操作
	 */
	public function checkPermiss($testdb_id, $permiss_level){

		if (session('accInfo.role') == 1){		//判断如果用户为超级管理员，则直接返回true
			return true;
		}
		$map['del_flag'] = array('eq', '1');
		$map['testdb_id'] = array('eq', $testdb_id);
		$map['college_id'] = array('eq', session('accInfo.dept_id'));

		$data = M('Testdatabase_permission')
		->field('permiss_level')
		->where($map)->find();

		if (sizeof($data) == 0){
			return false;
		}else if($permiss_level == $data['permiss_level'] || $permiss_level == 1 && $data['permiss_level'] == 2){
			return true;
		}
	}
	

	/**
	 * 功能：根据当前用户所属学院 获得 拥有权限的题库id列表
	 * @author maqingwen
	 * @return $array 由题库id组成的一维数组
	 * 说明：permiss_level：1仅可读，2可读可操作
	 */
	public function getPermissList(){

		$map['del_flag'] = array('eq', '1');
		$map['permiss_level'] = array('in', '1, 2');
		$map['college_id'] = array('eq', session('accInfo.dept_id'));
		$data = M('Testdatabase_permission')
		->field('testdb_id')
		->where($map)->select();

		$array = array_column($data, 'testdb_id');		//将结果的二维数组转化为一维数组

		return $array;
	}

	/**
	 * @function获得题库每种难度的数目
	 * @Author   许加明
	 * @DateTime 2017-4-15 20:37:00
	 * @param    $testDBId 题库id
	 * @return   array 结果数组
	 */
	public function getQuestionCountByLevel($testDBId){
		$level_1 = 0;
		$level_2 = 0;
		$level_3 = 0;

		$level_1 = D('Question')->where(array('testdb_id'=>$testDBId,'del_flag'=>'1','level'=>1))->count();
		$level_2 = D('Question')->where(array('testdb_id'=>$testDBId,'del_flag'=>'1','level'=>2))->count();
		$level_3 = D('Question')->where(array('testdb_id'=>$testDBId,'del_flag'=>'1','level'=>3))->count();

		$list = array($level_1,$level_2,$level_3);

		return $list;
	}


	/**
	 * @function获得题库每种类型的数目
	 * @Author   许加明
	 * @DateTime 2017-4-15 20:37:00
	 * @param    $testDBId 题库id
	 * @return   array 结果数组
	 */
	public function getQuestionCountByType($testDBId){
		$type_1 = 0;
		$type_2 = 0;
		$type_3 = 0;

		$type_1 = D('Question')->where(array('testdb_id'=>$testDBId,'del_flag'=>'1','type'=>1))->count();
		$type_2 = D('Question')->where(array('testdb_id'=>$testDBId,'del_flag'=>'1','type'=>2))->count();
		$type_3 = D('Question')->where(array('testdb_id'=>$testDBId,'del_flag'=>'1','type'=>3))->count();

		$list = array($type_1,$type_2,$type_3);

		return $list;
	}


}