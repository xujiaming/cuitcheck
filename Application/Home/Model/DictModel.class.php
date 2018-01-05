<?php
namespace Home\Model;
use Think\Model;

/**
 * 功能：对dict表的操作
 * 作者：pujinyao
 * 日期：2017-3-17
 */
class DictModel extends Model{

    /**
     * 自动验证设置
     */
    protected $_validate = array(
        array('type','require',array('success'=>false,'msg'=>'缺少类型！')),
        array('value','require',array('success'=>false,'msg'=>'缺少值！')),
        array('label','require',array('success'=>false,'msg'=>'缺少标签！')),
        array('description','require',array('success'=>false,'msg'=>'缺少描述！')),
    );


    protected $_auto = array (
        array('create_date', 'getTime', 1, 'callback'),         //新增数据时将创建时间设为当前时间
        array('update_date', 'getTime', 2, 'callback'),         //更新数据时将更新时间设为当前时间
        array('create_by', 'getAccount', 1, 'callback'),        //新增数据时将创建人设为当前用户account
        array('update_by', 'getAccount', 2, 'callback'),        //更新时将更新人设为当前用户account
    );




    /**
     * @param  [type]
     * @return [type]
     * 打包返回的json信息
     */
    private function packResult($status){
        $arr['msg'] = '操作失败！';
        $arr['success'] = $status;
        if($status){
            $arr['msg'] = '操作成功！';
        }
        return $arr;
    }


    /**
     * 获取当前时间
     * @param    null
     * @return   string 时间
     */
    public function getTime(){
        return date('Y-m-d H:i:s', time());
    }


    /**
     * @function 获取session中的用户
     * @param    null
     * @return   string  账号
     */
    public function getAccount(){
        $account = session('account');

        return $account;
    }





	/**
	 * @param int $requestPage 请求页 , string $beginDate 开始时间, string $endDate 结束时间, string $keyword 关键词, int $rows 行数
	 * 返回字典所有信息
	 */
	public function getAllList($requestPage, $beginDate, $endDate, $keyword, $rows){
		// 确保查询存在的数据
		$map['del_flag'] = array('eq', '1');
		if ($keyword != ''){
            $map['id|type|value|label|description'] = array('like', '%'.$keyword.'%');
        }
        if ($beginDate != '' && $endDate != ''){
            $map['create_date'] = array(array('egt', $beginDate), array('elt', $endDate));
        }

        $limit = ($requestPage-1)*$rows.','.$rows;

        $total = $this		//获取全部符合条件的数据条数
        	->where($map)->count();

        $list = $this->where($map)->limit($limit)->order('id')->select();
      

        $data = array(
        	'pages' =>	intval($total/$rows + 1),
        	'list'	=>	$list,
		);
        return $data;

	}


    /**
     * 根据传入的id值获得信息
     * @return [type]
     */
    public function getInfoById($id) {
        $data = $this
                ->where(array('id'=>$id))
                ->field(array('id','type','value','label','description'))
                ->find();
        return $data;
    }


    /**
     *添加信息
     */
    public function addInfo($data) {
         if(!$this->create($data)){
            $info = $this->getError();
        } else {
            // 无法自动完成
            $data['create_date'] = $this->getTime();
            $data['create_by'] = $this->getAccount();
            $result = $this->add($data);
            if($result == false){
                $info = $this->packResult(false);
            }else{
                $info = $this->packResult(true);
            }
        }
        return $info;
    }



    /**
     * 更新字典信息
     */
    public function updateInfo($data) {
        if(!$this->create($data)){
            $info = $this->getError();
        } else {
            $data['update_date'] = $this->getTime();
            $data['update_by'] = $this->getAccount();
            //增加
            $result = $this->save($data);
            if($result == false){
                $info = $this->packResult(false);
            }else{
                $info = $this->packResult(true);
            }
        }
        return $info;
    }


    /**
     * 根据传入的id值删除信息
     */
    public function deleteById() {
        $id = I('post.id');
        $data = $this->checkisset($id);
        if(!$data['success']) {
            return $data;
        }else{
            if($this->where(array('id'=>$id))->setField('del_flag',0) === false){
                $info = $this->packResult(false);
            }else{
                $info = $this->packResult(true);
            }
            return $info;       
        }
    }



    /**
     * 检验数据字典下方是否存在内容
     * @function
     * @AuthorPJY
     * @DateTime  2017-07-26T08:05:02+0800
     * @param     [type]                   $id [description]
     * @return    [type]                       [description]
     */
    public function checkisset($id){
        
        // 获得类型
        $map = current($this->field('type,value')->where(array('id'=>$id))->select());

        // 系统管理员信息
        if($map['type'] == 'sysRole') {
            $Role = M("sysuser")->where(array('role'=>$map['value'], 'del_flag'=>1))->find();
        }
        // 题库权限信息
        if($map['type'] == 'testDBPermiss') {
            $testPermiss = M('testdatabase_permission')->where(array('permiss_level'=>$map['value'], 'del_flag'=>1))->find();
        }
        if($map['type'] == 'testType') {
            $testType = M('testpaper')->where(array('type_id'=>$map['value'], 'del_flag'=>1))->find();
        }
        // var_dump(sizeof($Role));
        // var_dump(sizeof($testPermiss));
        // var_dump(sizeof($testType));
        // exit();
        if(sizeof($Role) != 0 || sizeof($testPermiss) != 0 || sizeof($testType) != 0) {
            $info['success'] = false;
            $info['msg'] = "该类型信息下存在信息,不可删除";
        }else{
            $info['success'] = true;
        }

        return $info;
    }



    /**
     * 根据传入的类型获得value及label
     * @function
     * @AuthorPJY
     * @DateTime  2017-10-11T14:07:05+0800
     * @return    [type]                   [description]
     */
    public function getInfoByType($type){
       $map['type'] = $type;
       $list = $this->where($map)->field(array('value', 'label'))->select();
       return $list;
    }


}   