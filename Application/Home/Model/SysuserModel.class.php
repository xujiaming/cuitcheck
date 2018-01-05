<?php
namespace Home\Model;
use Think\Model;

/**
 * 功能：对sysuser表的操作
 * 作者：许加明
 * 日期：2017-2-24 15:45:57
 */
class SysuserModel extends Model{

    /**
     * 自动验证设置
     */
    protected $_validate = array(
        array('id','require',array('success'=>false,'msg'=>'缺少id！')),
        array('account','require',array('success'=>false,'msg'=>'账号不能为空！')),
        array('account','',array('success'=>false,'msg'=>'账号已经使用！'),1,'unique',1),
        array('name','require',array('success'=>false,'msg'=>'姓名不能为空！')),
        array('sex',array(0,1),array('success'=>false,'msg'=>'性别值有误！'),0,'in'),
        array('phone','isMobile',array('success'=>false,'msg'=>'手机号有误！'),0,'callback'),
        array('email','email',array('success'=>false,'msg'=>'邮箱有误！')),
        array('role','checkRole',array('success'=>false,'msg'=>'角色范围有误！'),0,'callback'),
        array('dept_id','checkDept',array('success'=>false,'msg'=>'部门范围有误！'),0,'callback'),
        array('oldPass','checkPass',array('success'=>false,'msg'=>'原密码有误!'),0,'callback'),
        array('newPass','/(.+){6,12}$/',array('success'=>false,'msg'=>'新密码必须6到12位，且不能出现空格!'),0,'regex'),
        array('surePass','newPass',array('success'=>false,'msg'=>'两次密码不一致！'),0,'confirm'),
    );

    /**
     * 使用自动完成来填写部分默认数据项
     */
    protected $_auto = array(
        array('password','setPass',1,'callback') , // 对password字段在新增的时候使setPass函数处理
        array('create_date', 'getTime', 1, 'callback'),		//新增数据时将创建时间设为当前时间
        array('update_date', 'getTime', 2, 'callback'),		//更新数据时将更新时间设为当前时间
        array('create_by', 'getAccount', 1, 'callback'),//新增数据时将创建人设为当前用户account
        array('update_by', 'getAccount', 2, 'callback')	//更新时将更新人设为当前用户account
    );

    /**
     * @function 设置初始密码
     * @Author   许加明
     * @DateTime 2017-3-1 17:32:14
     * @param    null
     * @return   string  加密后的密码
     */
    public function setPass(){
        return md5(sha1('123456'));
    }

    /**
     * @function 获取session中的用户
     * @Author   许加明
     * @DateTime 2017-2-26 16:31:34
     * @param    null
     * @return   string  账号
     */
    public function getAccount(){
        $account = session('account');

        return $account;
    }

    /**
     * @function 验证角色范围
     * @Author   许加明
     * @DateTime 2017-3-1 14:14:19
     * @param    $value 范围的值
     * @return   boolean
     */
    public function checkRole($value){
        $arrStr = '';
        $temp = M('Dict')->field('value')->where(array('type'=>'sysRole','del_flag'=>1))->select();
        foreach($temp as $item){
            $arrStr = $arrStr.$item['value'];
        }
        if(strpos($arrStr,$value) === false){
            return false;
        }
        return true;
    }

    /**
     * @function 验证部门范围
     * @Author   许加明
     * @DateTime 2017-3-1 14:14:19
     * @param    $value 范围的值
     * @return   boolean
     */
    public function checkDept($value){
        $arrStr = '';
        $temp = D('College')->getCollegeIdAndNameList();
//            ->field('value')->where(array('type'=>'dept','del_flag'=>1))->select();
        foreach($temp as $item){
            $arrStr = $arrStr.$item['id'];
        }
        if(strpos($arrStr,$value) === false){
            return false;
        }
        return true;
    }


    /**
     * @function 获取当前时间
     * @Author   许加明
     * @DateTime 2017-2-26 16:32:43
     * @param    null
     * @return   string 时间
     */
    public function getTime(){
        return date('Y-m-d H:i:s', time());
    }

    /**
     * @function 打包需要返回的信息
     * @Author   许加明
     * @DateTime 2017-2-26 16:34:52
     * @param    boolean 状态值
     * @return   array 打包的数据
     */
    private function packResult($status,$msg=''){
        if($msg !== ''){
            $arr['msg'] = $msg;
        }else{
            if($status){
                $arr['msg'] = '操作成功！';
            }else{
                $arr['msg'] = '操作失败！';
            }
        }

        $arr['success'] = $status;
         $arr['code'] = 1;

        return $arr;
    }

    /**
     * @function 验证手机号是否正确
     * @Author   许加明
     * @DateTime 2017-2-24 19:29:33
     * @param   number $mobile
     * @return   boolean
     */
    function isMobile($mobile) {
        if (!is_numeric($mobile)) {
            return false;
        }
        return preg_match('#^13[\d]{9}$|^14[5,7]{1}\d{8}$|^15[^4]{1}\d{8}$|^17[0,6,7,8]{1}\d{8}$|^18[\d]{9}$#', $mobile) ? true : false;
    }
    /**
     * @function 密码验证
     * @Author   许加明
     * @DateTime 2017-2-25 12:20:38
     * @return   boolean
     */
    public function checkPass($pass){
        $user = $this->where(array('id'=>I('post.id'),'password'=>md5(sha1($pass))))->select();
        if(sizeof($user) == 1){
            return true;
        }else{
            return false;
        }
    }

    /**
     * @function 本人获得自己的信息
     * @Author   许加明
     * @DateTime 2017-2-24 15:48:55
     * @param    $account  账号
     * @return   array 返回查寻的数组结果
     */
    public function getMyselfInfo($account)
    {
        $sysUser = $this->field(array('id','account','name','sex','photo','phone','email','role','status','dept_id'))
            ->where(array('account'=>$account))->find();
        return $sysUser;
    }

    /**
     * @function 本人修改信息
     * @Author   许加明
     * @DateTime 2017-2-24 19:43:39
     * @param   null
     * @return   array 结果信息
     */
    public function insertMyselfInfo(){
        $info = null;
        if(!$this->create()){
            $info = $this->packResult(false,$this->getError());
        }else{
            if($this->where(array('id'=>I('post.id')))->save() === false){
                $info = $this->packResult(false);
            }else{
                $info = $this->packResult(true);
            }
        }
        return $info;
    }
    /**
     * @function 管理员更新信息
     * @Author   许加明
     * @DateTime 2017-3-1 15:16:55
     * @param    null 
     * @return   array $info 结果信息
     */
    public function updateInfoByManage(){
        //获得当前用户信息
        $user = D('Sysuser')->getMyselfInfo(session('account'));
        $info = null;
        if(!$this->create()){
            $info = $this->packResult(false,$this->getError());
        }else{
            if($user['role'] > I('role') ){
                $info = $this->packResult(false,'权限不够！');
            }else{
                if($this->save() === false){
                    $info = $this->packResult(false);
                }else{
                    $info = $this->packResult(true);
                }
            }
        }
        return $info;
    }

    /**
     * @function 管理员添加信息
     * @Author   许加明
     * @DateTime 2017-3-1 15:16:55
     * @param    null
     * @return   array $info 结果信息
     */
    public function addInfoByManage($temp){
        $info = null;
//        $this->password = md5(sha1('123456'));
        if(!$this->create()){
            $info = $this->packResult(false,$this->getError());
        }else{
            if($this->add() === false){
                $info = $this->packResult(false);
            }else{
                $info = $this->packResult(true);
                if ($info['code']==1) {
                    $map['account']=$temp['account'];
                    $sysUser=M('Sysuser')->field(array('id'))->where($map)->find();
                    $groupinfo['uid']=$sysUser['id'];
                    $groupinfo['role']=$temp['role'];
                    $this->addacess($groupinfo);
                }
            }
        }
        return $info;
    }
public function addacess($groupinfo){
        $data['uid']=$groupinfo['uid'];
        $data['group_id']=$groupinfo['role'];
        // dump($data);die();
        $groupAcess=M('auth_group_access');
        $groupAcess->add($data);
}
    /**
     * @function 管理员删除系统用户信息
     * @Author   许加明
     * @DateTime 2017-3-1 15:16:55
     * @param    null
     * @return   array $info 结果信息
     */
    public function deleteSysUserByManage(){
        $info = null;
        $id = I('post.id');
        if($this->where(array('id'=>$id))->setField('del_flag',0) === false){
            $info = $this->packResult(false);
        }else{
            $info = $this->packResult(true);
        }
        return $info;
    }
    
    /**
     * @function 通过id获取单个用户信息
     * @Author   许加明
     * @DateTime 2017-2-28 16:42:06
     * @param     id
     * @return    array
     */
    public function getSysUserById($id){
        $sysUser = $this->field(array('id','account','name','sex','photo','phone','email','role','status','dept_id','remarks'))
            ->where(array('id'=>$id))->find();
        return $sysUser;
    }

    /**
     * @function 本人修改密码
     * @Author   许加明
     * @DateTime 2017-2-25 12:30:14
     * @param null
     * @return   array 结果信息
     */
    public function fixPass(){
        $info = null;
        if(!$this->create()){
            $info = $this->packResult(false,$this->getError());
        }else{
            $this->password = md5(sha1(I('post.newPass')));
            if($this->where(array('id'=>I('post.id')))->save() === false){
                $info = $this->packResult(false);
            }else{
                $info = $this->packResult(true);
            }
        }
        return $info;
    }

    /**
     * @function 根据账号获得用户id
     * @Author   许加明
     * @DateTime 2017-2-26 16:50:05
     * @param null
     * @return   int 用户id
     */
    public function getId(){
        $account = session('account');
        $User = $this->field(array('id'))->where(array('account'=>$account))->find();
        return $User['id'];
    }

    /**
     * @function 将头像路径存入数据库
     * @Author   许加明
     * @DateTime 2017-2-26 16:51:04
     * @param string 头像路径
     * @return   int 用户id
     */
    public function savePhoto($path){
        $info = null;
        if($this->where(array('id'=>$this->getId()))->setField('photo',$path) !== false){
            $info = true;
        }else{
            $info = false;
        }
        return $info;
    }
    public function reset() {
        $pwd =123456;
        $map['password'] = md5(sha1($pwd));
        if($this->where(array('id' => I('id')))->save($map) === false) {
            $info = $this->packResult(false);
        }else {
            $info = $this->packResult(true);
        }
        return $info;
    }
    /**
     * @function 根据筛选条件获取全部数据
     * @Author   许加明
     * @DateTime 2017-2-27 15:19:15
     * @param int $requestPage 请求页 , string $beginDate 开始时间, string $endDate 结束时间, string $keyword 关键词, int $rows 行数, int $dept 查询的学院
     * @return  array 用户数据
     */
    public function getAllList($requestPage, $beginDate, $endDate, $keyword,$rows,$dept=''){
        $user = D('Sysuser')->getMyselfInfo(session('account'));

        $map['kh_sysuser.del_flag'] = array('eq', '1');
        if($dept !== ''){
            $map['kh_college.id'] = array('eq',$dept);
        }
        if ($keyword != ''){
            $map['kh_sysuser.id|account|kh_sysuser.name|phone|email|kh_sysuser.remarks|kh_college.name'] = array('like', '%'.$keyword.'%');
        }
        if ($beginDate != '' && $endDate != ''){
            $map['kh_sysuser.create_date'] = array(array('egt', $beginDate), array('elt', $endDate));
        }
        //设置权限小于当前用户角色的筛选条件
        $map['kh_sysuser.role'] = array('egt',$user['role']);

        $limit = ($requestPage-1)*$rows.','.$rows;      //查找行数

        $total = $this		//获取全部符合条件的数据条数
            ->join('LEFT join kh_college ON kh_college.id = kh_sysuser.dept_id')->where($map)->count();

        unset($map['kh_sysuser.id|account|kh_sysuser.name|phone|email|kh_sysuser.remarks|kh_college.name']);//删除条件
        $map['kh_dict.type'] = array('eq','sysRole');
        $map['kh_dict.del_flag'] =  array('eq', '1');
//        $map2['_complex'] = $map;   //重置条件
        if ($keyword != ''){
            $map['kh_dict.label|kh_sysuser.id|account|kh_sysuser.name|phone|email|kh_sysuser.remarks|kh_college.name'] = array('like','%'.$keyword.'%');
        }
        $list = $this->join('LEFT join kh_dict ON kh_sysuser.role = kh_dict.value')
            ->join('LEFT join kh_college ON kh_college.id = kh_sysuser.dept_id')
            ->field(array('kh_sysuser.id','kh_sysuser.account','kh_sysuser.name','kh_sysuser.sex','kh_sysuser.photo','kh_sysuser.phone','kh_sysuser.email','kh_dict.label'=>'role','kh_sysuser.status','kh_college.name'=>'dept_id','kh_sysuser.remarks'))
            ->where($map)->order('kh_sysuser.role asc,id')
            ->limit($limit)->select();
        $pages = ($total % $rows === 0 ? intval(floor($total/$rows)) : intval(floor($total/$rows+1)));     //获取页数

        $data = array(
            "pages" => $pages,
            "list" => $list,
        );

        return $data;
    }
}