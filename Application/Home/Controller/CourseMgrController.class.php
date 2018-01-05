<?php 
namespace Home\Controller;
use Common\Controller\HomeBaseController;

/**
 * 功能：学科管理
 * 作者：马庆文
 * 时间：2017/3/13
 */

class CourseMgrController extends HomeBaseController{

	/**
	 * 功能：展示学科管理首页
	 * @param 
	 * @return courseList
	 */
	public function courseList(){

		$data = D('Course')->getAllList();
        //p($data);
		$this->assign('courseList', $data);

		$this->display('courseList');
	}

	/**
	 * 功能：初始化编辑窗
	 * @param id
	 * @return course
	 */
	public function editCourse(){

		$type = I('type');
		if ($type == 'update'){
			$id = I('id');
			$data = D('Course')->getCourseByid($id);
			$this->assign('course', $data);
		}
		$this->assign('type', $type);
        $dept = getdeptId();
        if($dept == 0) {
            $college_list = M('college')->field('id,name')->where(array('del_flag'=>1))->select();
        }else {
            $college_list = M('college')->field('id,name')->where(array('del_flag'=>1,'id'=>$dept))->select();
        }
        $this->assign('college_list',$college_list);
		$this->display('editCourse');
	}

	/**
	 * 功能：修改信息的事件处理
	 * @param course
	 * @return 
	 */
	public function updateCourse(){

		if (!IS_POST){
			$data = array('success'=>false, 'msg'=>'提交方式不正确');
		}else{
			$data = D('Course')->updateCourse();
		}

		$this->ajaxReturn($data, 'json');
	}

	/**
	 * 添加信息的事件处理
	 * @param course
	 * @return
	 */
	public function addCourse(){

		if (!IS_POST){
			$data = array('success'=>false, 'msg'=>'提交方式不正确');
		}else{
			$data = D('Course')->addCourse();
		}

		$this->ajaxReturn($data, 'json');
	}

	/**
	 * 点击删除的事件处理
	 * @param id
	 * @return
	 */
	public function deleteCourse(){

		$id = I('id');

		$data = D('Course')->deleteCourse($id);

		$this->ajaxReturn($data, 'json');
	}
	
}