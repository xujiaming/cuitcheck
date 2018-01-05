<?php 
namespace Home\Controller;
use Common\Controller\HomeBaseController;

/**
 * 功能：课程管理
 * 作者：罗钞
 * 时间：2017/9/25
 */

class LessionMgrController extends HomeBaseController{

	/**
	 * 功能：展示课程管理首页
	 * @param 
	 * @return lessionList
	 */
	public function lessionList(){
		$requestPage = I('requestPage', 1);	//获取请求的页码数
		$keyword = I('keyword', '');		//获取查询关键词
		$rows = 7;							//每页展示的数据
		$course=I('course','');
		$college=I('college','');
		$data = D('Lession')->getAllList($requestPage, $keyword, $rows,$course,$college);
		$dept_id=getdeptId();
			///如果是超级管理员则添加学院时可以选择其他学院
			if ($dept_id==0){
					$collegelist=getcollege($dept_id);
			}else{
				$collegelist=getcollege($dept_id);
		}
		$this->assign('collegelist',$collegelist);
		$this->assign('lessionlst', $data['list']);
		$this->assign('pages', $data['pages']);
		$this->assign('requestPage', $requestPage);
		$this->assign('keyword', $keyword);
		$this->display();
	}

	/**
	 * 功能：初始化编辑窗
	 * @param id
	 * @return course
	 */
	public function editlession(){

		$type = I('type');
		if ($type == 'update'){
			$id = I('id');
			$data = D('Lession')->getlessionByid($id);
			// dump($data);
			$college_id=$data['college_id'];
			// dump($college_id);die();
			$this->assign('lession', $data);
			$this->assign('college_id', $college_id);
		}
			$dept_id=getdeptId();
			///如果是超级管理员则添加学院时可以选择其他学院
			if ($dept_id==0){
					$collegelist=getcollege($dept_id);
			}else{
				// dump($dept_id);die();
				// $courselist=getcourse($dept_id);
				$collegelist=getcollege($dept_id);
				// dump($courselist);die();
			}
			// dump($courselist);die();
		$this->assign('collegelist',$collegelist);
		$this->assign('courselist',$courselist);


		$this->assign('type', $type);

		$this->display();
	}
	//联动查询获取课程信息
	public function getcourselist(){
		$college_id=I('college_id');
		$courselist=getcourse($college_id);
		$data = array('data'=>$courselist);
		// dump($list);die();
		$this->ajaxReturn($data,'json');
	}
	/**
	 * 功能：修改信息的事件处理
	 * @param course
	 * @return 
	 */
	public function updatelession(){

		if (!IS_POST){
			$data = array('success'=>false, 'msg'=>'提交方式不正确');
		}else{
			$data = D('Lession')->updatelession();
		}

		$this->ajaxReturn($data, 'json');
	}

	/**
	 * 添加信息的事件处理
	 * @param course
	 * @return
	 */
	public function add_lession(){
		// dump($_POST);die();
		if (!IS_POST){
			$data = array('success'=>false, 'msg'=>'提交方式不正确');
		}else{
			$data = D('Lession')->add_lession();
		}

		$this->ajaxReturn($data, 'json');
	}

	/**
	 * 点击删除的事件处理
	 * @param id
	 * @return
	 */
	public function deletelession(){

		$id = I('id');

		$data = D('Lession')->deletelession($id);

		$this->ajaxReturn($data, 'json');
	}
	
}