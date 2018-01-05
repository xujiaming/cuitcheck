<?php
namespace Home\Model;
use Think\Model;

class ChapterModel extends Model{

    /**
     * 自定义验证规则
     */
    protected $_validate = array(
        array('id','require',array('success'=>false,'msg'=>'缺少id！')),
        array('name','require',array('success'=>false,'msg'=>'章节名不能为空！')),
        array('sortnumber','require',array('success'=>false,'msg'=>'排序数不能为空！')),
//        array('sortnumber','checkSortNumber',array('success'=>false,'msg'=>'排序数已经存在！'),0,'callback'),
        array('course_id','checkCourseId',array('success'=>false,'msg'=>'课程范围有误！'),0,'callback'),
        array('course_id','require',array('success'=>false,'msg'=>'课程id不能为空！'))
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
     * @function 检查课程范围是否有误
     * @Author   许加明
     * @DateTime 2017-3-17 20:24:14
     * @param    int 课程id
     * @return   boolean 检查结果
     */
    public function checkCourseId($value){
        $course = M('Course')->field('id')->where(array('id'=>$value,'del_flag'=>1))->select();
        if(sizeof($course) == 1){
            return true;
        }else{
            return false;
        }
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

        return $arr;
    }

    /**
     * @function 管理员更新章节信息
     * @Author   许加明
     * @DateTime 2017-3-20 18:09:52
     * @param    null
     * @return   array $info 结果信息
     */
    public function updateChapter(){
        //获得当前用户信息
        $user = D('Sysuser')->getMyselfInfo(session('account'));
        $info = null;
        if(!$this->create()){
            $info = $this->packResult(false,$this->getError());
        }else{
            if($user['role'] != 1 && $user['role'] != 3 ){
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
     * @function 管理员删除系统章节信息
     * @Author   许加明
     * @DateTime 2017-3-20 18:10:20
     * @param    null
     * @return   array $info 结果信息
     */
    public function deleteChapter(){
        $info = null;
        $user = D('Sysuser')->getMyselfInfo(session('account'));
        $id = I('post.chapter_id');
        //超级管理员和学院管理员课删除，其余角色不可删除
        if($user['role'] != 1 && $user['role'] != 3 ){
            $info = $this->packResult(false,'权限不够！');
        }else{
            $flag2 = M('knowledge')->where(array('del_flag'=>1,'chapter_id'=>$id))->find();

            //判断该章节下是否还是有知识点存在
            if(!empty($flag2)) {
                $info = $this->packResult(false, '该章节下还有知识点！');
            }
            else{
                $flag1 = $this->where(array('id'=>$id))->setField('del_flag',0);

                if($flag1 === false){
                    $info = $this->packResult(false,'删除失败');
                }else {
                    $info = $this->packResult(true,'删除成功');
                }

            }
        }
        return $info;
    }

    /**
     * @function 管理员添加章节信息
     * @Author   许加明
     * @DateTime 2017-3-20 20:13:34
     * @param    null
     * @return   array $info 结果信息
     */
    public function addChapter(){
        $info = null;
        $user = D('Sysuser')->getMyselfInfo(session('account'));

        if(!$this->create()){
            $info = $this->packResult(false,$this->getError());
        }else{
            if($user['role'] != 1 && $user['role'] != 3 ){
                $info = $this->packResult(false,'权限不够！');
            }else{
                if($this->add() === false){
                    $info = $this->packResult(false,'添加失败！');
                }else{
                    $info = $this->packResult(true,'添加成功！');
                }
            }
        }
        return $info;
    }

    /**
     * @Function: 获取在添加章节使得课程选项
     * @Author: xuanhao
     * @DateTime: ${DATE} ${TIME}
     *
     * @return mixed
     */
    public function getLession($map1) {
        $course = M('course')->field('id,name')->where($map1)->select();
        foreach($course as &$value){
            $children = M('lession')->field('id,name,course_id')->where(array('course_id'=>$value['id'], 'del_flag'=>1))->select();
            $value['children'] = $children;
        }
        unset($value);
        return $course;
    }


}