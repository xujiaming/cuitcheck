<?php
namespace Home\Controller;
use Common\Controller\BaseController;
/**
 *  * 功能：课堂测试试卷管理
 * 作者：许加明
 * 日期：2017-6-19 15:04:59
 */
class ClassPapersCreateMgrController extends BaseController{
    /**
     * @function 获得课堂测试试卷列表
     * @Author   许加明
     * @DateTime 2017-6-19 15:24:18
     * @param    null
     * @return   html
     */
    public function ClassPapersList(){
        $requestPage = I('requestPage', 1);	//获取请求的页码数
        $keyword = I('keyword', '');		//获取查询关键词
        $college_id = I('college_id','');
        $type = I('type','');
        $rows = 10;		//每页展示的数据



        $data = D('Testpaper')->getAllList($requestPage, $keyword,$college_id,$type,$rows);

        $collegeList = M('college')->order('id desc')->select();

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

        if (!$isCanMakeTestPaper){
            $paperTypeList = dict('testType','1');
        }else{
            $paperTypeList = dict('testType');
        }
        // p($data);die();
        $this->assign('role',session('accInfo.role'));
        $this->assign('papersList', $data['list']);
        $this->assign('pages', $data['pages']);
        $this->assign('requestPage', $requestPage);
        $this->assign('keyword', $keyword);
        $this->assign('college_id',$college_id);
        $this->assign('type',$type);
        $this->assign('collegeList',$collegeList);
        $this->assign('paperTypeList',$paperTypeList);

        $this->display('ClassPapersList');
    }

    /**
     * @function 展示添加页面
     * @Author   许加明
     * @DateTime 2017-6-20 14:57:33
     * @param    null
     * @return   null
     */
    public function showAddPaper(){
        $isCanMakeTestPaper = false;
        $collegeList = M('college')->order('id desc')->select();
        $paperTypeList = dict('testType');

        //权限判断
        $role = session('accInfo.role');
        if($role == 4){
            $isTeacherCanMakeTestPaper = D('TestTeacherPermission')-> checkPermission();
            if ($isTeacherCanMakeTestPaper){
                $isCanMakeTestPaper = true;
                $dept_id=getdeptId();
                $sessionlist=getlession($dept_id);
            }
        }else if($role == 1){
            $isCanMakeTestPaper = true;
        }
        else if ($role == 3) {
            $isCanMakeTestPaper = true;
            $dept_id=getdeptId();
            $sessionlist=getlession($dept_id);
            // p($sessionlist);die();
        }

        if (!$isCanMakeTestPaper){
            $paperTypeList = dict('testType','1');
        }else{
            $paperTypeList = dict('testType');
        }


        $this->assign('lessionlist',$sessionlist);
        $this->assign('isCanMakeTestPaper',$isCanMakeTestPaper);
        $this->assign('role',session('accInfo.role'));
        $this->assign('collegeList',$collegeList);
        $this->assign('paperTypeList',$paperTypeList);
        $this->display('addPaper');
    }
    
    /**
     * @function 添加测试试卷
     * @Author   许加明
     * @DateTime 2017/6/20 0020
     * @param    null
     * @return      null
     */
    public function addPaper(){
        $data = D('Testpaper')->addPaper();
        $this->ajaxReturn($data,'json');
    }

    /**
     * @function 展示修改页面
     * @Author   许加明
     * @DateTime 2017-6-20 14:57:33
     * @param    null
     * @return   null
     */
    public function showEditPaper(){
        $id = I('id');
        $collegeList = M('college')->order('id desc')->select();
        $paperTypeList = dict('testType');
        $paperInfo = M('Testpaper')
         ->field('kh_testpaper.id, kh_testpaper.name,kh_testpaper.type_id,kh_testpaper.college_id,kh_testpaper.lession_id,kh_testpaper.is_use,kh_testpaper.comment,kh_lession.name as lessionname')
         ->join('JOIN kh_lession ON kh_lession.id = kh_testpaper.lession_id')
        ->where(array('kh_testpaper.id'=>$id))
        ->find();
        // p($paperInfo);die();

        $role = session('accInfo.role');
        if ($role == 3||$role == 4) {
            $isCanMakeTestPaper = true;
            $dept_id=getdeptId();
            $sessionlist=getlession($dept_id);
            // p($sessionlist);die();
        }

        $this->assign('lessionlist',$sessionlist);
        $this->assign('paperInfo',$paperInfo);
        $this->assign('role',session('accInfo.role'));
        $this->assign('collegeList',$collegeList);
        $this->assign('paperTypeList',$paperTypeList);
        $this->display('editPaper');
    }

    /**
     * @function 修改测试试卷
     * @Author   许加明
     * @DateTime 2017/6/20 0020
     * @param    null
     * @return      null
     */
    public function updatePaper(){
        $data = D('Testpaper')->updatePaper();
        $this->ajaxReturn($data,'json');
    }

}