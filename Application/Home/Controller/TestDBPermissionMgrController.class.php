<?php 
namespace Home\Controller;
use Common\Controller\HomeBaseController;

/**
 * 功能：题库授权
 * @author maqingwen
 * @data 2017/4/16
 */
class TestDBPermissionMgrController extends HomeBaseController{

	/**
	 * 功能: 获取全部题库列表
	 * 注意：超级管理员拥有全部题库的查看和授权功能，学院管理员和老师只拥有管理本学院题库授权权限
	 * @param requestPage, college_id, course_id, status, keyword
	 * @return testDatabaseList
	 */
	public function testDBList(){

		$requestPage = I('requestPage', 1);	//获取请求的页码数
		$course_id = I('course_id', '');	//获取学科id
		$college_id = I('college_id', '');	//获取学院id,如果登录用户是超级管理员，则显示全部题库
		$keyword = I('keyword', '');		//获取查询题库关键词
		$status = I('status', '');			//获取题库状态
		$rows = 10;		//每页展示的数据

		if (session('accInfo.role') != 1 && session('accInfo.role') != 2){	//如果当前用户为普通管理员，则
			$college_id = session('accInfo.dept_id');
			$courseList = D('Course')->getCourseListByCollege($college_id);
			$this->assign('courseList', $courseList);
		}

		$data = D('TestdatabasePermission')->getAllList($requestPage, $course_id, $college_id, $keyword, $status, $rows);

		// 普通管理员默认加载本学院学科, 超级管理员不需要加载
		if (session('accInfo.role') == 1 || session('accInfo.role') == 2){
			$collegeList = D('TestdatabasePermission')->getCollegeList();
			$this->assign('collegeList', $collegeList);
		}

		// 查询该学科所属学院的所有学科
		if($course_id != ''){
			$courseList = D('Course')->getCourseListByCollege($college_id);
			$this->assign('courseList', $courseList);
		}
		// p($data);die();
		$this->assign('testDBList', $data['list']);
		$this->assign('pages', $data['pages']);
		$this->assign('requestPage', $requestPage);
		$this->assign('keyword', $keyword);
		$this->assign('course_id', $course_id);
		$this->assign('college_id', $college_id);
		$this->assign('status', $status);

		$this->display('testDBPermissionList');
	}

	/**
	 * 功能：题库权限管理页面
	 * 注意：只有超级管理员和学院管理员拥有授予题库权限的权限，学院管理员只能管理本学院权限
	 * @param testdb_id
	 * @return collegePerList, testdb_id, keyword
	 */
	public function detailTestDBPer(){

		$testdb_id = I('id');			//题库id
		$keyword = I('keyword', '');

		$testDB = D('testdatabase')->getTestDBById($testdb_id);
		// dump($testDB);die();
		if (session('accInfo.role') != 1 && session('accInfo.role') != 3 ){
			$this->error("没有操作权限");
		}else if(session('accInfo.role') == 3 && session('accInfo.dept_id') != $testDB['college_id']){
			$this->error("没有操作权限");
		}else{
			$data = D('TestdatabasePermission')->getPermissCollege($testdb_id, $keyword);
		}
		$create_college = D('Testdatabase')->getTestDBById($testdb_id);//获得创建学院id
		$this->assign('collegePerList', $data);
		$this->assign('testdb_id', $testdb_id);
		$this->assign('keyword', $keyword);
		$this->assign('create_id', $create_college['college_id']);

		$this->display('detailTestDBPer');
	}

	/**
	 * 功能：初始化编辑窗
	 * @param type, college_id, testdb_id
	 * @return testDBPer
	 */
	public function editTestDBPer(){
		$type = I('type');
		$testdb_id = I('testdb_id');
		if ($type == 'update'){
			$college_id = I('college_id');
			$data = D('TestdatabasePermission')->getTestDBPer($college_id, $testdb_id);
			$this->assign('testDBPer', $data);
		}
		$collegeList = D('TestdatabasePermission')->getCollegeList();
		$this->assign('collegeList', $collegeList);
		$this->assign('testdb_id', $testdb_id);
		$this->assign('type', $type);

		$this->display('editTestDBPer');
	}

	/**
	 * 功能：点击修改权限的事件处理
	 * @param college_id, testdb_id, permiss_level
	 */
	public function updateTestDBPer(){

		if (!IS_POST){
			$data = array('success'=>false, 'msg'=>'提交方式不正确');
		}else{
			//$_POST['permiss_level']='1';
			$create_college = D('Testdatabase')->getTestDBById($_POST['testdb_id']);//获得创建学院id
			if ($create_college['college_id'] == $_POST['college_id']){
				$data = array('success'=>false, 'msg'=>'不可修改创建学院的权限。（你想搞事情？）');
			}else if (session('accInfo.role') != 1 && session('accInfo.role') != 3 ){
				$data = array('success'=>false, 'msg'=>'没有操作权限');
			}else if(session('accInfo.role') == 3 && session('accInfo.dept_id') != $create_college['college_id']){
				$data = array('success'=>false, 'msg'=>'没有操作权限');
			}else{
				$data = D('TestdatabasePermission')->updateTestDBPer();
			}
		}

		$this->ajaxReturn($data, 'json');
	}

	/**
	 * 功能：点击添加的事件处理
	 * @param college_id, testdb_id, permiss_level
	 */
	public function addTestDBPer(){
		if (!IS_POST){
			$data = array('success'=>false, 'msg'=>'提交方式不正确');
		}else{
			$create_college = D('Testdatabase')->getTestDBById($_POST['testdb_id']);//获得创建学院id
			
			if (session('accInfo.role') != 1 && session('accInfo.role') != 3 ){
				$data = array('success'=>false, 'msg'=>'没有操作权限');
			}else if(session('accInfo.role') == 3 && session('accInfo.dept_id') != $create_college['college_id']){
				$data = array('success'=>false, 'msg'=>'没有操作权限');
			}else{
				$data = D('TestdatabasePermission')->addTestDBPer();
			}
		}

		$this->ajaxReturn($data, 'json');
	}

	/**
	 * 功能：点击删除的事件处理
	 */
	public function deleteTestDBPer(){

		$college_id = I('college_id');
		$testdb_id = I('testdb_id');

		$create_college = D('Testdatabase')->getTestDBById($testdb_id);//获得创建学院id
		if ($create_college['college_id'] == $college_id){
			$data = array('success'=>false, 'msg'=>'不可修改创建学院的权限');
		}else if (session('accInfo.role') != 1 && session('accInfo.role') != 3 ){
			$data = array('success'=>false, 'msg'=>'没有操作权限');
		}else if(session('accInfo.role') == 3 && session('accInfo.dept_id') != $create_college['college_id']){
			$data = array('success'=>false, 'msg'=>'没有操作权限');
		}else{
			$data = D('TestdatabasePermission')->deleteTestDBPer($testdb_id, $college_id);
		}

		$this->ajaxReturn($data, 'json');
	}


	/**
	 * 得到专业对应的学科
	 * @function
	 * @AuthorPJY
	 * @DateTime  2017-10-25T12:30:11+0800
	 * @return    [type]                   [description]
	 */
	public function getCourse(){
		$course_id = $_POST['college_id'];
		$courseList = M("course")->field('id,name')->where(array('college_id'=>$course_id))->select();
		$this->ajaxReturn($courseList);
	}
}