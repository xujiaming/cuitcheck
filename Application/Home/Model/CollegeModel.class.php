<?php 
/**
 * 学院管理的数据模型
 */
namespace Home\Model;
use Think\Model;
Class CollegeModel extends Model{

	/**
	 * 自定义验证规则
	 */
	protected $_validate = array(
		array('name', 'require', array('success'=>false, 'msg'=>'请填写学院名称')),	//验证学院名称不能为空
		array('leadername', 'require', array('success'=>false, 'msg'=>'请填写负责人姓名')),
		array('name', '', array('success'=>false, 'msg'=>'学院名称已存在，请重新填写！'), 1, 'unique', 3),
		array('leaderphone', 'checkPhone', array('success'=>false, 'msg'=>'电话号码格式不正确'), 1, 'callback', 3)
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
	 * 验证电话号码格式
	 */
	public function checkPhone($data){
		$rule = '/^1[3|4|5|8][0-9]\d{4,8}$/';

		if (preg_match($rule, $data)){
			return true;
		}else{
			return false;
		}
	}

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
	public function getAllList($requestPage, $beginDate, $endDate, $keyword, $rows){

		$map['del_flag'] = array('eq', '1');
		if ($keyword != ''){
			$map['id|name|leadername'] = array('like', '%'.$keyword.'%');
		}
		if ($beginDate != '' && $endDate != ''){
			$map['create_date'] = array(array('egt', $beginDate), array('elt', $endDate));
		}

		$limit = ($requestPage-1)*$rows.','.$rows;

		$total = M('College')		//获取全部符合条件的数据条数
		->field('id, name, leadername, leaderphone, create_date, comment')
		->where($map)->count();

		$list = M('College')
		->field('id, name, leadername, leaderphone, create_date, comment')
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
	 * 获取添加班级时获取的数据
	 */
	
	public function getcollgelist(){

		$map['del_flag'] = array('eq', '1');
		$data = M('College')
		->field('id, name')
		->where($map)
		->limit($limit)->select();

		return $data;
	}

	/**
	 * 根据id获取学院信息
	 */
	public function getCollegeById($id){

		$map['id'] = array('eq', $id);
		$map['del_flag'] = array('eq', '1');

		$data = M('College')
		->field('id, name, leadername, leaderphone, create_date, comment')
		->where($map)
		->find();

		return $data;
	}

	private function getret($status){
		$arr['success'] = $status;
		return $arr;
	}

	/**
	 * 修改学院信息
	 */
	public function updateCollege(){
		if (!$this->create()){
			return $this->getError();
		}else{
			$this->save();
			return $this->getret(true);
		}
	}

	/**
	 * 添加学院信息
	 */
	public function addCollege(){
		if (!$this->create()){
			return $this->getError();
		}else{
			$this->add();
			return $this->getret(true);
		}
	}

	/**
	 * 删除学院信息
	 */
	public function daleteCollege($id){
		$info = $this->checkisset($id);
		if ($info['status']){
			$data['success'] = false;
			$data['msg'] = $info['msg'];
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
	 * 检查该学院下方是否有其他信息
	 * @param id
	 * @return boolean//有返回true，无返回false
	 */
	public function checkisset($id){
		$map['college_id'] = $id;
		$map['del_flag'] = 1;
        $major_is_exist = M('major')->where(array('dept_id'=>$id,'del_flag'=>1))->find();     //专业
		$class_is_exist = M('class')->where($map)->find();	// 行政班级
		$courseclass_is_exist = M('courseclass')->where($map)->find();	// 行课班级
        if($major_is_exist != NULL) {
            $msg['status'] = true;
            $msg['msg'] = "该学院下还有专业信息,暂时无法删除";
        }else if($class_is_exist != NULL) {
			$msg['status'] = true;
			$msg['msg'] = "该学院下还有行政班级信息,暂时无法删除";
		}
		else if ($courseclass_is_exist != NULL) {
			$msg['status'] = true;
			$msg['msg'] = "该学院下还有行课班级信息,暂时无法删除";
		}
		else{
			$msg['status'] = false;
		}
		return $msg;
	}
	
	/**
	 * @function 获得学院id和name列表
	 * @Author   许加明
	 * @DateTime 2017/3/9 0009
	 * @param     
	 * @return      
	 */
	public function getCollegeIdAndNameList($id=''){
		if($id !== ''){
			return  $this->field('id,name')->where(array('id'=>$id))->select();
		}
		return $this->field('id,name')->select();
	}

	public function getcollgelistByrole(){
		if(session('accInfo.role') != 1){
            $map['id'] = session('accInfo.dept_id');
        }
		$map['del_flag'] = array('eq', '1');
		$data = M('College')
		->field('id, name')
		->where($map)
		->limit($limit)->select();

		return $data;
	}

}