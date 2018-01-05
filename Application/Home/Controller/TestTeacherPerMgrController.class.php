<?php 
namespace Home\Controller;
use Common\Controller\HomeBaseController;

/**
 * 功能：正式考试出题授权
 * @author maqingwen
 */
class TestTeacherPerMgrController extends HomeBaseController{

	/**
	 * 功能：展示拥有出题权限的教师列表
	 * @param $requestPage, $keyword
	 */
	public function testTeacherList(){

		$requestPage = I('requestPage', 1);	//获取请求的页码数
		$keyword = I('keyword', '');		//获取查询题库关键词
		$rows = 10;		//每页展示的数据
		$ptime = time();

		$data = D('TestTeacherPermission')->getAllList($requestPage, $keyword, $rows);

		$this->assign('testTeacherList', $data['list']);
		$this->assign('pages', $data['pages']);
		$this->assign('keyword', $keyword);
		$this->assign('requestPage', $requestPage);
		$this->assign('ptime', $ptime);

		$this->display('testTeacherList');
	}

	/**
	 * 功能：初始化编辑窗
	 * @param
	 */
	public function editTestTeaPer(){

		$type = I('type');

		if ($type == 'update'){
			$id = I('id', '');
			$data = D('TestTeacherPermission')->getPerById($id);
			$this->assign('testTeaPer', $data);
		}

		$teacherList = D('TestTeacherPermission')->getTeacherList();
		$this->assign('teacherList', $teacherList);
		$this->assign('type', $type);

		$this->display('editTestTeaPer');
	}

	/**
	 * 添加权限的事件处理
	 * @param
	 */
	public function addTestTeaPer(){
		if (!IS_POST){
			$data = array('success'=>false, 'msg'=>'提交方式不正确');
		}else{
			$temp_user = D('sysuser')->getSysUserById($_POST['teacher_id']);

			if(session('accInfo.role') != 1 && session('accInfo.role') != 3){
				$data = array('success'=>false, 'msg'=>'没有操作权限');
			}else if (session('accInfo.role') == 3 && session('accInfo.dept_id') != $temp_user['dept_id']){
				$data = array('success'=>false, 'msg'=>'没有操作权限');
			}else{
				$data = D('TestTeacherPermission')->addTestTeaPer();
			}
		}

		$this->ajaxReturn($data, 'json');
	}

	/**
	 * [deleteTestTeaPer description]
	 * 删除权限的事件处理
	 * @return [type] [description]
	 */
	public function deleteTestTeaPer(){

		$id = I('id', '');

		if(session('accInfo.role') != 1 && session('accInfo.role') != 3){
			$data = array('success'=>false, 'msg'=>'没有操作权限1');
		}else{
			$data = D('TestTeacherPermission')->deleteTestTeaPer($id);
		}
		$this->ajaxReturn($data, 'json');
	}
}