<?php 
/**
 * 课程管理的数据模型
 * 作者：luochao
 */
namespace Home\Model;
use Think\Model;
Class lessionModel extends Model{

	/**
	 * 自定义验证规则
	 */
	protected $_validate = array(
		array('college_id', 'require', array('success'=>false, 'msg'=>'请选择学院')),
		array('course_id', 'require', array('success'=>false, 'msg'=>'请选择学科')),
		array('name', 'require', array('success'=>false, 'msg'=>'请填写班级名称')),

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
	public function getAllList($requestPage,$keyword, $rows,$college,$course){
		$dept_id=getdeptId();
			///如果是超级管理员则添加学院时可以选择其他学院
			if ($dept_id==0){
				$map['kh_lession.del_flag'] = array('eq', '1');
			}else{
				$map['kh_lession.del_flag'] = array('eq', '1');
				$map['kh_lession.college_id']= array('eq' , $dept_id);
			}
		// dump($map);die();
		if ($keyword != ''){
			$map['kh_lession.id|kh_lession.name'] = array('like', '%'.$keyword.'%');
		}
		if (($college != '')||($course != '')){
			$map['kh_lession.course_id'] = array('like', '%'.$course.'%');
			$map['kh_lession.college_id'] = array('like', '%'.$college.'%');
			// dump($course);die();
		}

		$limit = ($requestPage-1)*$rows.','.$rows;

		$total = M('lession')		//获取全部符合条件的数据条数
		->field('id, name')
		->where($map)->count();

		$list = M('lession') //多表联合查询
		->join('
				LEFT join kh_college ON kh_lession.college_id = kh_college.id 
 				LEFT join kh_course ON kh_lession.course_id = kh_course.id
			')
		->field('kh_lession.id,
				 kh_lession.name,
		 		 kh_college.name as college_name,
		 		 kh_college.id as college_id,
		 	     kh_course.name as course_name,
		 	     kh_lession.remarks
		 	     ')
		->where($map)
		->order('kh_lession.college_id asc')
		->limit($limit)->select();
		// dump($list);die();
		$pages = 0;
		if ($total%$rows == 0){
			$pages = $total/$rows;
		}else{
			$pages = intval($total/$rows + 1);
		}

		$data = array(
			"pages" => $pages,
			"list" => $list,
		);
		return $data;
	}


	/**
	 * 修课程信息
	 */
	public function updatelession(){
		if (!$this->create()){
			return $this->getError();
		}else{
			// dump($this->save());die();
			$this->save();
			return $this->getret(true);
		}
	}

	/**
	 * 添加课程信息
	 */
	public function add_lession(){
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
	public function deletelession($id){

		//注意这里要判断下班级
		$data1['lession_id'] = $id;
		// dump($data1);die();
		$map['name'] = M('courseclass') 
		->field('name')
		->where($data1)
		->select();
		// dump(sizeof($map['name']));die();
		if(sizeof($map['name'])==0){
			$form['id'] = $id;
			$form['del_flag'] = 0;
			$form['update_date'] = $this->getTime();
			$form['update_by'] = $this->getAccount();
			M('lession')->save($form);
			$data['success'] = true;
			$data['msg'] = '删除成功';
		}else{
			$data['msg'] = '删除失败，该课程下还有班级';
		}
		return $data;
	}
	public function getlessionByid($id){

		$map['kh_lession.id'] = array('eq', $id);
		$map['kh_lession.del_flag'] = array('eq', '1');

		$data = M('lession') //多表联合查询
		->join('LEFT join kh_college ON kh_lession.college_id = kh_college.id 
 				LEFT join kh_course ON kh_lession.course_id = kh_course.id
			')
		->field('kh_lession.id,
				 kh_lession.name,
		 		 kh_college.name as college_name,
		 	     kh_course.name as course_name,
		 		 kh_course.id as course_id,
		 		 kh_college.id as college_id,
		 	     kh_lession.remarks
		 	     ')
		->where($map)
		->find();

		return $data;
	}
	/**
	 * 行课班级详细展示
	 */
	public function getallcourseclass($id,$keyword){
		$data1['courseclass_id'] = $id;
		// dump($map);die();
		// if ($keyword != ''){
		// 	$map['name'] = array('like', '%'.$keyword.'%');
		// }
		$map['account'] = M('class_student') 
		->field('account')
		->where($data1)
		->select();
		// p($map);die();
		$data=array();
		if(sizeof($map['account'])!=0){
		if ($keyword != ''){
			$map2['name'] = array('like', '%'.$keyword.'%');
			for($i=0;$i<sizeof($map['account']);++$i){ //遍历条件查询
				$where3=array($map['account'][$i],$map2,'_logic'=>'and');
				$rowdata=M('student')
				->field('account,name,sex')
				->where($where3)
				->find();
				if(sizeof($rowdata)!=0){
				array_push($data,$rowdata);
			}
			}
			// dump($data);die();
		}else{
			for($i=0;$i<sizeof($map['account']);++$i){ //遍历条件查询
				$rowdata=M('student')
				->field('account,name,sex')
				->where($map['account'][$i])
				->find();
				array_push($data,$rowdata);
			}
		}
		}
		// dump($data);die();
		return $data;

	}
	/**
	 * 获取添加班级时获取的数据
	 */
	
	public function getcollgelist(){

		$map['del_flag'] = array('eq', '1');
		$data = M('College')
		->field('id, name')
		->where($map)
		->select();

		return $data;
	}
	/**
	 * 获取添加班级时获取的数据
	 */
	
	public function getgradelist(){

		$map['del_flag'] = array('eq', '1');
		$data = M('Grade')
		->field('id, name')
		->where($map)
		->limit($limit)->select();

		return $data;
	}

	/**
	 * 获得课程id和名称
	 * @function
	 * @AuthorPJY
	 * @DateTime  2017-10-10T20:34:25+0800
	 * @return    [type]                   [description]
	 */
	public function getLessionList($course_id){
		$map['del_flag'] = array('eq', '1');
		$map['course_id'] = $course_id;
		$limit = M('Lession')
		->field('id, name')
		->where($map)->select();

		return $limit;
	}


}