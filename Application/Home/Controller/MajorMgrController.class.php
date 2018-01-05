<?php 
namespace Home\Controller;
use Common\Controller\HomeBaseController;

/**
 * 功能：专业管理
 * 作者：maqingwen
 * 日期： 2017/3/1
 */
class MajorMgrController extends HomeBaseController{

	/**
	 * 功能：专业列表展示界面
	 * @param requestPage, beginDate, endDate, keyword
	 * @return majorList
	 */
	public function majorList(){
		$requestPage = I('requestPage', 1);	//获取请求的页码数

	 	if(session('accInfo.role') != 1){
            $colle_id = session('accInfo.dept_id');		// 不是超管则获取该学院专业
        }else{
			$colle_id = M('college')->getField('id');	// 超管默认读取第一个学院	
        }
		$college_id = I('college_id', $colle_id);	//获取请求的学院
		$beginDate = I('beginDate', '');	//获取开始时间
		$endDate = I('endDate', '');		//获取结束时间
		$keyword = I('keyword', '');		//获取查询关键词
		$rows = 10;		//每页展示的数据

		// 查看全部专业
		if($college_id == 'All'){
			if(session('accInfo.role') == 1){
            $college_id = "";
        	}else{
        		$college_id = session('accInfo.dept_id');
        	}	
		}
		$data = D('Major')->getAllList($requestPage, $college_id, $beginDate, $endDate, $keyword, $rows);
		$college_List = D('college')->getcollgelistByrole();
		$this->assign('majorList', $data['list']);
		$this->assign('collegeList', $college_List);
		$this->assign('pages', $data['pages']);
		$this->assign('college_id', $college_id);
		$this->assign('requestPage', $requestPage);
		$this->assign('beginDate', $beginDate);
		$this->assign('endDate', $endDate);
		$this->assign('keyword', $keyword);

		$this->display('majorList');
	}

	/**
	 * 功能：初始化编辑窗
	 * @param type, id
	 * @return type, major
	 */
	public function editMajor(){

		$type = I('type');
		if ($type == 'update'){
			$id = I('id');
			$data = D('Major')->getMajorById($id);
			$this->assign('college_id', $data[0]['college_id']);
			$this->assign('option', true);		// 不能编辑学院
			$this->assign('major', $data[0]);
		}
		$college_List = D('college')->getcollgelistByrole();
		$this->assign('collegeList', $college_List);
		$this->assign('type', $type);

		$this->display('editMajor');
	}

	/**
	 * 修改信息的事件处理
	 * @param major
	 * @return 
	 */
	public function updateMajor(){

		if (!IS_POST){
			$data = array('success'=>false, 'msg'=>'提交方式不正确');
		}else{
			$data = D('Major')->updateMajor();
		}

		$this->ajaxReturn($data, 'json');
	}

	/**
	 * 添加信息的事件处理
	 * @param major
	 * @return
	 */
	public function addMajor(){

		if (!IS_POST){
			$data = array('success'=>false, 'msg'=>'提交方式不正确');
		}else{
			$data = D('Major')->addMajor();
		}

		$this->ajaxReturn($data, 'json');
	}

	/**
	 * 删除信息的事件处理
	 * @param id
	 * @return
	 */
	public function deleteMajor(){
		$id = I('id');

		$data = D('Major')->daleteMajor($id);

		$this->ajaxReturn($data, 'json');
	}
}