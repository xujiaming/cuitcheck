<?php
namespace Home\Model;
use Think\Model;

/**
 * 功能：试卷模型类
 * 作者：许加明
 * 日期：2017-6-19 17:22:46
 */
class TestpaperModel extends Model{
    /**
     * 自定义验证规则
     */
    protected $_validate = array(
        array('name','require', array('success'=>false, 'msg'=>'请填写题库名称')),	//试卷名称不能为空
        array('college_id','checkCollegeId',array('success'=>false,'msg'=>'学院范围有误！'),0,'callback'),
        array('type_id','checkTypeId',array('success'=>false,'msg'=>'类型范围有误！'),0,'callback'),
    );
    
    /**
     * @function 学院id检测
     * @Author   许加明
     * @DateTime 2017-6-20 15:44:59
     * @param    $value
     * @return   boolean
     */
    public function checkCollegeId($value){
        $course = M('College')->field('id')->where(array('id'=>$value,'del_flag'=>1))->select();
        if(sizeof($course) == 1){
            return true;
        }else{
            return false;
        }
    }

    /**
     * @function 类型id检测
     * @Author   许加明
     * @DateTime 2017-6-20 15:50:34
     * @param    $value
     * @return   boolean
     */
    public function checkTypeId($value){
        $course = dict('testType',$value);
        if(sizeof($course) == 1){
            return true;
        }else{
            return false;
        }
    }

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
     * @function 打包需要返回的信息
     * @Author   许加明
     * @DateTime 2017-3-24 19:01:09
     * @param    boolean 状态值
     * @return   array 打包的数据
     */
    public function packResult($status,$msg=''){
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
     * 获取符合条件的全部list
     */
    public function getAllList($requestPage, $keyword, $college_id, $type_id, $rows){


        //权限判断
        $isCanMakeTestPaper = false;
        $role = session('accInfo.role');
        if($role == 4){
            $isTeacherCanMakeTestPaper = D('TestTeacherPermission')-> checkPermission();
            if ($isTeacherCanMakeTestPaper){
                $isCanMakeTestPaper = true;
            }
        }else if($role == 1 || $role == 3){
            $isCanMakeTestPaper = true;
        }


        $map['kh_dict.type'] = 'testType';

        if (!$isCanMakeTestPaper){
            $map['kh_dict.value']=array('neq', '2');
        }

        $map['kh_testpaper.del_flag'] = array('eq', '1');
        if ($keyword != ''){
            $map['kh_testpaper.id|kh_testpaper.name|kh_dict.label|kh_college.name'] = array('like', '%'.$keyword.'%');
        }

        if ($college_id != ''){
            $map['kh_testpaper.college_id'] = array('eq', $college_id);
        }

        if ($type_id != ''){
            $map['kh_testpaper.type_id'] = array('eq', $type_id);
        }

        if (session('accInfo.role') == 2){
            $data = array(
                "pages" => 0,
                "list" => null
            );
            return $data;
        }else{
            if($college_id == ''){
                $dept_id = session('accInfo.dept_id');
                session('accInfo.role') == 1? : $map['kh_testpaper.college_id'] = $dept_id;
            }
        }

        $limit = ($requestPage-1)*$rows.','.$rows;

        $total = $this	//获取符合条件的数据条数
        ->field('kh_testpaper.id, kh_testpaper.name, kh_dict.label as typename, kh_college.name as collegename, kh_testpaper.full_score, kh_testpaper.pass_score,kh_testpaper.is_use, kh_testpaper.create_by,kh_testpaper.create_date')
            ->join('JOIN kh_dict ON kh_dict.value = kh_testpaper.type_id')
            ->join('JOIN kh_college ON kh_college.id = kh_testpaper.college_id')
            ->where($map)->count();

        $list = $this
            ->field('kh_testpaper.id, kh_testpaper.name, kh_dict.label as typename,kh_testpaper.type_id, kh_college.name as collegename,kh_college.id as college_id, kh_testpaper.full_score,kh_lession.name as lessionname,kh_lession.id as lession_id,kh_testpaper.pass_score,kh_testpaper.is_use,kh_testpaper.create_by,kh_testpaper.create_date')
            ->join('JOIN kh_dict ON kh_dict.value = kh_testpaper.type_id')
            ->join('JOIN kh_college ON kh_college.id = kh_testpaper.college_id')
            ->join('JOIN kh_lession ON kh_lession.id = kh_testpaper.lession_id')
            ->where($map)
            ->limit($limit)
            ->order('create_date desc')
            ->select();

        //查找题量字段
        // p($list);die();
        $i = 0;
        foreach($list as &$value){

            $value['number'] = $this->getPaperQuestionNum($value['id']);
            $i++;
        }
        unset($value);

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
     * @function 获得试卷题目数量
     * @Author   许加明
     * @DateTime 2017-6-20 15:51:58
     * @param    $paper_id 
     * @return   number
     */
    public function getPaperQuestionNum($paper_id){
        return M('PaperQuestion')->where(array('testpaper_id'=>$paper_id))->count();
    }

    /**
     * @function 添加试卷
     * @Author   许加明
     * @DateTime 2017-6-20 16:07:12
     * @param    null
     * @return   null
     */
    public function addPaper(){
        $info = null;
        $user = D('Sysuser')->getMyselfInfo(session('account'));

        if(session('accInfo.role') != 1){
            $_POST['college_id'] = session('accInfo.dept_id');
        }

        if(!$this->create()){
            $info = $this->getError();
        }else{
            if($user['role'] == 2 ){
                $info = $this->packResult(false,'没有权限！');
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
     * @function 添加试卷
     * @Author   许加明
     * @DateTime 2017-6-20 16:07:12
     * @param    null
     * @return   null
     */
    public function updatePaper(){
        $info = null;

        if(!$this->create()){
            $info = $this->getError();
        }else{
            if(session('accInfo.role') == 2 ){
                $info = $this->packResult(false,'没有权限！');
            }else{
                if($this->save() === false){
                    $info = $this->packResult(false,'修改失败！');
                }else{
                    $info = $this->packResult(true,'修改成功！');
                }
            }
        }
        return $info;
    }

}