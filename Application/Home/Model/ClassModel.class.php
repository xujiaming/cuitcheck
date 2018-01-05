<?php 
/**
 * 行政班级管理的数据模型
 */
namespace Home\Model;
use Think\Model;
Class ClassModel extends Model{

	/**
	 * 自定义验证规则
	 */
	protected $_validate = array(
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
		$college_id=M('sysuser')->field('role,dept_id')->where($map2)->find();
		// dump($college_id);die();
		if($college_id['role']!=1){
			$map['kh_class.college_id']= array('eq' , $college_id['dept_id']);
		}
		$map['kh_class.del_flag'] = array('eq', '1');
		if ($keyword != ''){
			$map['kh_class.id|kh_class.name'] = array('like', '%'.$keyword.'%');
		}
		if ($gradekey != ''){
			$map['kh_class.grade_id'] = array('like', '%'.$gradekey.'%');
		}
		if ($beginDate != '' && $endDate != ''){
			$map['kh_class.create_date'] = array(array('egt', $beginDate), array('elt', $endDate));
		}

		$limit = ($requestPage-1)*$rows.','.$rows;

		$total = M('class')		//获取全部符合条件的数据条数
		->field('id, name, create_date')
		->where($map)->count();

		$list = M('class')
		->join('LEFT join kh_college ON kh_college.id = kh_class.college_id
				LEFT join kh_grade ON kh_grade.id = kh_class.grade_id
			')
		->field('kh_class.id,
				 kh_class.name,
		 		 kh_college.name as college_name,
		 	     kh_grade.name as grade_name
		 	     ')
		->where($map)
		->order('college_id asc')
		->limit($limit)->select();

		$pages = 0;
		if ($total%$rows == 0){
			$pages = $total/$rows;
		}else{
			$pages = intval($total/$rows + 1);
		}

//		$pages = 0;
//		if ($total%$rows == 0){
//			$pages = $total/$rows;
//		}else{
//			$pages = $total/$rows + 1;
//		}
		$data = array(
			"pages" => $pages,
			"list" => $list,
		);

		return $data;
	}


	/**
	 * 修改班级信息
	 */
	public function updateclass(){
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
	public function add_class(){
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
	public function daleteclass($id){
			$form['id']= $id;
			$form['del_flag'] = '0';
			$form['update_date'] = $this->getTime();
			$form['update_by'] = $this->getAccount();
			M('class')->save($form);
			$data['success'] = true;
			$data['msg'] = '删除成功';
		return $data;
	}
	public function getClassId($id){

		$map['kh_class.id'] = array('eq', $id);
		$map['kh_class.del_flag'] = array('eq', '1');

		$data = M('class')
		->join('LEFT join kh_college ON kh_college.id = kh_class.college_id
				LEFT join kh_grade ON kh_grade.id = kh_class.grade_id
			')
		->field('kh_class.id,
				 kh_class.name,
		 		 kh_college.name as college_name,
		 	     kh_grade.name as grade_name
		 	     ')
		->where($map)
		->find();

		return $data;
	}

}