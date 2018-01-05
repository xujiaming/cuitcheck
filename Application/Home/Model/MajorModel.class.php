<?php 
namespace Home\Model;
use Think\Model;

/**
 * 专业管理数据模型
 */
Class MajorModel extends Model{


	/**
	 * 自定义验证规则
	 */
	protected $_validate = array(
		array('name', 'require', array('success'=>false, 'msg'=>'请填写专业名称')),	//验证专业名称不能为空
		array('name', '', array('success'=>false, 'msg'=>'专业名称已存在，请重新填写！'), 1, 'unique', 3)
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
	public function getAllList($requestPage, $college_id, $beginDate, $endDate, $keyword, $rows){

		$map['kh_major.del_flag'] = array('eq', '1');
		if ($keyword != ''){
			$map['kh_major.id|Major.name'] = array('like', '%'.$keyword.'%');
		}
		if($college_id != "") {
			$map['kh_major.dept_id'] = array('eq', $college_id);
		}
		if ($beginDate != '' && $endDate != ''){
			$map['kh_major.create_date'] = array(array('egt', $beginDate), array('elt', $endDate));
		}

		$limit = ($requestPage-1)*$rows.','.$rows;

		$total = M('Major')		//获取全部符合条件的数据条数
				->field('kh_major.id, kh_major.name, kh_major.create_date, kh_major.comment, kh_college.name as college_name')
				->join('JOIN kh_college ON kh_college.id = kh_major.dept_id')
				->where($map)->count();

		$list = M('Major')
				->field('kh_major.id, kh_major.name, kh_major.create_date, kh_major.comment, kh_college.name as college_name')
				->join('JOIN kh_college ON kh_college.id = kh_major.dept_id')
				->where($map)
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
	 * 根据id获取专业信息
	 */
	public function getMajorById($id){

		$map['kh_major.id'] = array('eq', $id);
		$map['kh_major.del_flag'] = array('eq', '1');
        $limit = 1;
		$data = M('Major')
		// ->field('id, name, create_date, comment')
				->field('kh_major.id, kh_major.name, kh_major.create_date, kh_major.comment, kh_college.id as college_id')
				->join('JOIN kh_college ON kh_college.id = kh_major.dept_id')
				->where($map)
				->limit($limit)->select();
		// ->where($map)
		// ->find();
		// var_dump($data);exit();
		return $data;
	}

	private function getret($status){
		$arr['success'] = $status;
		return $arr;
	}

	/**
	 * 修改专业信息
	 */
	public function updateMajor(){
		if (!$this->create()){
			return $this->getError();
		}else{
			$this->save();
			return $this->getret(true);
		}
	}

	/**
	 * 添加专业信息
	 */
	public function addMajor(){
		if (!$this->create()){
			return $this->getError();
		}else{
			$this->add();
			return $this->getret(true);
		}
	}

	/**
	 * 删除专业信息
	 */
	public function daleteMajor($id){
		if ($this->checkisset($id)){
			$data['success'] = false;
			$data['msg'] = '该专业下还有其他信息，暂时无法删除';
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
	 * 检查该专业下方是否有其他信息
	 * @param id
	 * @return boolean//有返回true，无返回false
	 */
	public function checkisset($id){
        $map['major_id'] = $id;
        $map['del_flag'] = 1;
        $student_is_exist = M('student')->where($map)->find();	// 学生
        if($student_is_exist != NULL) {
            $msg = true;
        }
        else{
            $msg = false;
        }
        return $msg;
	}

}