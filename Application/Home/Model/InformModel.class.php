<?php 
/**
 * 通知公告的数据模型
 * @arthur luochao
 */
namespace Home\Model;
use Think\Model;

class InformModel extends Model{	
	/**
	 * 自定义验证规则
	 */
	protected $_validate = array(
		array('title', 'require', array('success'=>false, 'msg'=>'标题不能为空')),
		array('content','require', array('success'=>false, 'msg'=>'通知内容不能为空')),
		array('sendtype','require', array('success'=>false, 'msg'=>'请选择发布对象!')),
		array('title', '', array('success'=>false, 'msg'=>'通知名称已存在，请重新填写！'), 1, 'unique', 3),
	);	
	/**
	 * 使用自动完成来填写部分默认数据项
	 */
	protected $_auto = array(
		array('greatedate', 'getTime', 1, 'callback'),		//新增数据时将创建时间设为当前时间
		array('greateby', 'getAccount', 1, 'callback'),//新增数据时将创建人设为当前用户account
		// array('dept_id', 'getdeptid', 1, 'callback'),//新增数据时将创建人设为当前用户account
		array('upby', 'getAccount', 2, 'callback')	//更新时将更新人设为当前用户account
	);

	public function getInformList($requestPage,$endDate,$beginDate,$rows,$targetkey,$collegekey){
		// $dept_id=getdept();
		// dump($dept_id);die();
		$map['kh_inform.del_flag']=array('eq','1');
		if ($targetkey != ''){
			$map['kh_inform.sendtype'] = array('like', '%'.$targetkey.'%');
		}
		if ($collegekey != ''){
			$map['kh_inform.dept_id'] = array('like', '%'.$collegekey.'%');
		}
		if ($beginDate !='' && $endDate !='') {
			$map['kh_inform.greatedate']=array(array('egt',$beginDate),array('elt',$endDate));
		}
		if (session('accInfo.role') == 3){
			$dept_id=getdeptId();
			$map['kh_inform.dept_id']=array('eq',$dept_id);
		}

		$limit=($requestPage-1)*$rows.','.$rows;

		$total=M('inform')
		->field('id,title')
		->where($map)
		->count();

		$list=M('inform')
		->join('LEFT join kh_college ON kh_college.id=kh_inform.dept_id')		
		->field('kh_inform.id,
			kh_college.name as dept_name,kh_inform.title,kh_inform.greatedate,kh_inform.sendtype,kh_inform.content')
		->where($map)
		->order('kh_inform.greatedate desc')
		->limit($limit)
		->select();

		foreach($list as $key=>$vol){
			if($list[$key]['dept_name']==''){
				$list[$key]['dept_name']="超级管理员";
			}
		}

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
		// p($data['pages']);die();
		return $data;

	}
	/**
	 * 修改通知公告信息
	 */
	public function updateinform(){
		if(!$this->create()){
			return $this->getError();
		}else{ 
			$this->save();
			return $this->getret(true);
		}
	}
	/**
	 * 获取要修改通知信息
	 */
	public function getinformId($id){
		$map['id']=array('eq',$id);
		$map['del_flag']=array('eq','1');

		$data=M('inform')
		->field('id,title,sendtype,content
			')
		->where($map)
		->find();
		// p($data);die();
		return $data;

	}
	/**
	 * 删除通知信息
	 */
	public function informdelete($id){
		$form['id']=$id;
		$form['del_flag']='0';
		M('inform')->save($form);
		$data['success']=true;
		$data['msg']="删除成功";
		return $data;
	}
	/**
	 * 获取session中的用户
	 */
	public function getAccount(){
		$account = session('account');

		return $account;
	}
	public function getdeptid(){
		$account=$this->getAccount();
		$map['account']=array('eq',$account);
		$dept_id=M('Sysuser')->field('dept_id')->where($map)->find();
		// p($dept_id);die();
		return $dept_id;
	}
	// public function getdept(){
	// 	$ac=getAccount();
	// 	$map['account']=$ac;
	// 	$data['dept_id']=M('Sysuser')->field('dept_id')->where($map)->find();
	// 	// $dept_id=session('dept_id');
	// 	return $data['dept_id'];
	// }

	/**
	 * 获取当前时间
	 * 
	 */
	public function getTime(){
		return date('Y-m-d H:i:s', time());
	}
	/**
	 * 发布通知公告
	 */
	public function add_inform(){
		if (!$this->create()){
			return $this->getError();
		}else{
			$this->add(); //数据表中不能出现为空的数据
			return $this->getret(true);
		}
	}
	/**
	 * 预览通知公告
	 */
	public function informPreShow($id){
		$map['kh_inform.id']=$id;
		$data=M('inform')
		->join('LEFT join kh_college ON kh_college.id=kh_inform.dept_id')
		->field('kh_inform.id,kh_college.name as dept_name,kh_inform.title,kh_inform.greatedate,kh_inform.sendtype,kh_inform.content,kh_inform.file_url,kh_inform.file_name,kh_inform.greateby')
		->where($map)
		->find();
		if($data['dept_name']==''){
			$data['dept_name']="超级管理员";
		}
		// p($data);die();
		return $data;
	}
	private function getret($status){
		$arr['success'] = $status;
		return $arr;
	}
    public function maingetInfo(){
        $limit=5;
        $map['kh_inform.del_flag']=array('eq','1');
        $map['sendtype']=array('neq' , '1' );
        $list=M('inform')
            ->field('id,title,greatedate')
            ->where($map)
            ->order('kh_inform.greatedate desc')
            ->limit($limit)
            ->select();
        return $list;

    }

}