<?php 
namespace Home\Controller;
use Common\Controller\HomeBaseController;

/**
 * 功能：题库管理
 * @author maqingwen
 * @date 2017/3/23
 */
class TestDatabaseMgrController extends HomeBaseController{

	/**
	 * 题库展示界面
	 * @param requestPage, keyword, course_id, college_id, status
	 * @return testDatabaseList
	 */
	public function testDatabaseList(){

		$requestPage = I('requestPage', 1);	//获取请求的页码数
		$keyword = I('keyword', '');		//获取查询关键词
		$testtype_id = I('testtype_id', ''); 	//获得试卷类型id
		$course_id = I('course_id', ''); 	// 获得学科id
		$lession_id = I('lession_id', '');	//获取科目id
		$college_id = I('college_id', '');	//获取所属学院id
		$status = I('status', '');				//获取题库状态
		$rows = 10;		//每页展示的数据
		// 获得所有题库
		$data = D('Testdatabase')->getAllList($requestPage, $keyword, $testtype_id, $course_id, $lession_id, $college_id, $status, $rows);
		// 获得试卷类型
		$typeList = D('dict')->getInfoByType('testType');
		// 绑定课程
		if (session('accInfo.role') != 1){
			$college_id = session('accInfo.dept_id');
			$courseList = D('Course')->getCourseListByCollege($college_id);
		}else{
			$courseList = D('Course')->getCourseListByCollege($college_id);
		}
		// 当选择了学科时进行渲染
		if(!empty($course_id)){
			$lessionList = D('lession')->getLessionList($course_id);
		}
		$collegeList = D('Testdatabase')->getCollegeList();

		// dump($data);die();
		
		$this->assign('testDBList', $data['list']);
		$this->assign('typeList',$typeList);
		$this->assign('courseList', $courseList);
		if(!empty($course_id)){
			$this->assign('lessionList', $lessionList);
		}
		$this->assign('collegeList', $collegeList);
		$this->assign('pages', $data['pages']);
		$this->assign('requestPage', $requestPage);
		$this->assign('keyword', $keyword);
		$this->assign('testtype_id',$testtype_id);
		$this->assign('course_id', $course_id);
		$this->assign('lession_id', $lession_id);
		$this->assign('college_id', $college_id);
		$this->assign('status', $status);

		$this->display('testDatabase');
	}

	/**
	 * 功能：初始化编辑窗
	 * @param type, id
	 * @return type, testDB
	 */
	public function editTestDB(){
		$type = I('type');
		if ($type == 'update'){
			$id = I('id');
			$data = D('Testdatabase')->getTestDBById($id);
			$lessionList = D('lession')->getLessionList($data['course_id']);
			$this->assign('lessionList', $lessionList);
			$this->assign('testdb', $data);
			// 获得创建学院下的学科列表
			$courseList = D('Course')->getCourseListByCollege($data['college_id']);
		}
		// 添加时:
		// 1. 当前用户为超级用户 : 根据选择的学院进行动态添加课程列表
		// 2. 当前用户为学院管理员 : 根据学院id获得课程列表
		else{
			if (session('accInfo.role') != 1){
				$college_id = session('accInfo.dept_id');
				$courseList = D('Course')->getCourseListByCollege($college_id);
			}
		}
		$typeList = D('dict')->getInfoByType('testType');
		// $courseList = D('Course')->getAllList();
		$collegeList = D('Testdatabase')->getCollegeList();
		$this->assign('typeList',$typeList);
		$this->assign('courseList', $courseList);
		$this->assign('collegeList', $collegeList);
		$this->assign('type', $type);

		$this->display('editTestDB');
	}

	/**
	 * 添加信息的事件处理
	 * @param testDB
	 * @return
	 */
	public function addTestDB(){
		if (!IS_POST){
			$data = array('success'=>false, 'msg'=>'提交方式不正确');
		}else{
			$data = D('Testdatabase')->addTestDB();
		}

		$this->ajaxReturn($data, 'json');
	}

	/**
	 * 修改信息的事件处理
	 * @param testDB
	 * @return 
	 */
	public function updateTestDB(){

		if (!IS_POST){
			$data = array('success'=>false, 'msg'=>'提交方式不正确');
		}else{
			$data = D('Testdatabase')->updateTestDB();
		}

		$this->ajaxReturn($data, 'json');
	}

	/**
	 * 删除信息的事件处理
	 * @param id
	 * @return
	 */
	public function deleteTestDB(){
		$id = I('id');

		$data = D('Testdatabase')->daleteTestDB($id);


		$this->ajaxReturn($data, 'json');
	}

	/**
	 * 初始化详情窗口
	 * @param id
	 * @return 
	 */
	public function detailTestDB(){
		$requestPage = I('requestPage', 1);	//获取请求的页码数
        $type_l = I('type', '');			//获取题目类型
        $level_l = I('level', '');		//获取结束时间
        $keyword = I('keyword', '');		//获取查询关键词
        $rows = 10;		//每页展示的数据
        
		$testDBId = I('id');
		$string = '';
		$string1 = '';
		// p($type_id);die();
		// 获得图表信息
		$level = D('Testdatabase')->getQuestionCountByLevel($testDBId);
		$type = D('Testdatabase')->getQuestionCountByType($testDBId);
		// 获得该题库下的题目列表
		$data = D('Question')->getQuestionList($testDBId,$type_l,$level_l,$keyword,$requestPage,$rows);
		// var_dump($data['list']);
		// exit();

		$string = implode(',',$level);
		$string1 = implode(',',$type);

		$this->assign('pages',$data['pages']);
		$this->assign('questionlist',$data['list']);
		$this->assign('requestPage', $requestPage);
        $this->assign('level', $level_l);
        $this->assign('type', $type_l);
        $this->assign('keyword', $keyword);
        $this->assign('testDBId', $testDBId);

		$this->assign('str1',$string);
		$this->assign('str2',$string1);

		$this->display('detailTestDB');

	}


	/**
	 * 根据学科id获得下面的课程信息
	 * @function
	 * @AuthorPJY
	 * @DateTime  2017-10-11T12:03:00+0800
	 * @return    [type]                   [description]
	 */
	public function getLessionByCourse(){
		$course_id = $_POST['course_id'];
		$LessionList = M("lession")->where(array('course_id'=>$course_id))->field(array('id', 'name'))->select();
		// var_dump($LessionList);exit();
		$this->ajaxReturn($LessionList);
	}

	/**
	 * 根据学院id获得下面的学科信息
	 * @function
	 * @AuthorPJY
	 * @DateTime  2017-10-15T15:12:41+0800
	 * @return    [type]                   [description]
	 */
	public function getCourseByCollege(){
		$college_id = $_POST['college_id'];
		$CourseList = M("Course")->where(array('college_id'=>$college_id))->field(array('id', 'name'))->select();
		// var_dump($LessionList);exit();
		$this->ajaxReturn($CourseList);

	}
	
}