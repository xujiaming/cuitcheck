<?php 
/**
 * 科目管理的数据模型
 * @arthur 马庆文
 */
namespace Home\Model;
use Think\Model;

class CourseModel extends Model{


	/**
	 * 自定义验证规则
	 */
	protected $_validate = array(
		array('name', 'require', array('success'=>false, 'msg'=>'请填写科目名称'))	//验证学院名称不能为空
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
	 * 获取全部科目信息
     * 超级管理员可以查看所有的学院的学科，学院管理员只可以查看本学院的
	 */
	public function getAllList(){

		$map['del_flag'] = array('eq', '1');
        $dept = getdeptId();
        if($dept == 0) {
            $college = M('college')->field('id,name')->select();            //获取所有的学院
            foreach ($college as &$value1) {
                $map['college_id'] = $value1['id'];
                $course = M('course')->field('id,name')->where($map)->select();
                $value1['course'] = $course;
            }
        }else {
            $college = M('college')->field('id,name')->where(array('del_flag'=>1,'id'=>$dept))->select();
            $map['college_id'] = $dept;
            $course = M('course')->field('id,name')->where($map)->select();
            $college[0]['course'] = $course;
        }
		return $college;
	}

	/**
	 * 根据id获取单个科目信息
	 */
	public function getCourseByid($id){
		$map['id'] = array('eq', $id);
		$map['del_flag'] = array('eq', '1');

		$data = M('Course')
		->field('id, name, remarks,college_id')
		->where($map)->find();

		return $data;
	}

	private function getret($status){
		$arr['success'] = $status;
		return $arr;
	}

	/**
	 * 修改科目信息
	 */
	public function updateCourse(){
		if (!$this->create()){
			return $this->getError();
		}else{
			$this->save();
			return $this->getret(true);
		}
	}

	/**
	 * 添加科目信息
	 */
	public function addCourse(){
		if (!$this->create()){
			return $this->getError();
		}else{
			$this->add();
			return $this->getret(true);
		}
	}

	/**
	 * 删除科目信息
	 */
	public function deleteCourse($id){
		if ($this->checkisset($id)){
			$data['success'] = false;
			$data['msg'] = '该科目下还有其他信息，暂时无法删除';
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
	 * 检查该科目下方是否有其他信息
	 * @param id
	 * @return boolean//有返回true，无返回false
	 */
	public function checkisset($id){


		return false;
	}


	/**
	 * 根据学院id获得学科列表
	 * @function
	 * @AuthorPJY
	 * @DateTime  2017-10-15T15:37:11+0800
	 * @param     [type]                   $college_id [description]
	 * @return    [type]                               [description]
	 */
	public function getCourseListByCollege($college_id){
		$map['del_flag'] = array('eq', '1');

		if(!empty($college_id)){
			$map['college_id'] = $college_id;
		}
		$limit = M('Course')
		->field('id, name')
		->where($map)->select();

		return $limit;
	}

}