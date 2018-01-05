<?php
namespace Home\Controller;
use Common\Controller\HomeBaseController;

class KnowledgeMgrController extends HomeBaseController{
    
    /**
     * @function 显示主页面
     * @Author   许加明
     * @DateTime 2017-3-23 16:08:13
     * @param    null 
     * @return   null
     */
    public function knowledgeList(){
        $chapterList = D('Knowledge')->getChapterList();
        $course = M('Course')->field('name,id')->select();
        $dept = getdeptId();
        //控制选择学院的按妞是否显示
        if($dept == 0) {
            $select_show = true;
            $college = M('college')->field('id,name')->select();  //默认显示第一个学院
//            $defaultDept = M('college')->field('id')->find();  //返回查找的第一个学院的id
//            $choiceDept = I('choiceDept', $defaultDept['id']);          //超级管理员可选择学院进行查看，默认为查询的一个学院的数据
//            $map1 = array(
//                'del_flag' => 1,
//                'college_id' => $choiceDept
//            );
            $this->assign('collegeList', $college);
        }else {
            $select_show = false;
//            $map1 = array(
//                'del_flag' => 1,
//                'college_id' => $dept
//            );
        }
        $this->assign('select_show', $select_show);
        $this->assign('chapter',$chapterList);
        $this->assign('course',$course);
        $this->display();
    }

    /**
     * @function 得到章节树列表
     * @Author   许加明
     * @DateTime 2017-3-18 13:20:19
     * @param    null
     * @return   json
     */
    public function getChapterList(){
        $course = D('Knowledge')->getChapterList();
        $this->ajaxReturn($course,'json');
    }

    /**
     * @function 获取指定数据
     * @Author   许加明
     * @DateTime 2017-3-23 16:15:17
     * @param    null
     * @return   null
     */
//    public function knowledges(){
//        $user = D('Sysuser')->getMyselfInfo(session('account'));
//
//        $requestPage = I('requestPage', 1);	//获取请求的页码数
////        $beginDate = I('beginDate', '');	//获取开始时间
////        $endDate = I('endDate', '');		//获取结束时间
//        $keyword = I('keyword', '');		//获取查询关键词
//        $chapter_id = I('chapter_id','');   //所选章节
//        $course_id = I('course_id','');     //所选学科
//        $rows =10;		                    //每页展示的数据
//        $data = null;
//
//        if(in_array($user['role'],array(1,3,4))){
//            $data = D('Knowledge')->getAllList($requestPage,$keyword,$rows,$course_id,$chapter_id);
//        }else{
//            $data = null;
//        }
//
//        $this->assign('list', $data['list']);
//        $this->assign('pages', $data['pages']);
//        $this->assign('requestPage', $requestPage);
////        $this->assign('beginDate', $beginDate);
////        $this->assign('endDate', $endDate);
//        $this->assign('course_id',$course_id);
//        $this->assign('chapter_id',$chapter_id);
//        $this->assign('keyword', $keyword);
//        $this->display('knowledgeItem');
//
//    }
    public function knowledges(){
        $user = D('Sysuser')->getMyselfInfo(session('account'));

        $requestPage = I('requestPage', 1);	//获取请求的页码数
        $keyword = I('keyword', '');		//获取查询关键词
        $chapter_id = I('chapter_id','');   //所选章节
        $lession_id = I('lession_id','');     //所选课程，当点击的节点是课程时触发
        $rows =10;		                    //每页展示的数据
        $data = null;

        if(in_array($user['role'],array(1,3,4))){
            $data = D('Knowledge')->getAllList($requestPage,$keyword,$rows,$lession_id,$chapter_id);
        }else{
            $data = null;
        }
        //添加知识点的时候，1.点击课程名是，可供选项为该课程下的所有的章节；2.点击的是学科时，默认匹配当前学科，但也可以选择该课程下的其他章节
        $chapterForSelect = D('Knowledge')->getTheChapterForLession($lession_id, $chapter_id);
        $this->assign('chapterForSelect', $chapterForSelect);
        $this->assign('list', $data['list']);
        $this->assign('pages', $data['pages']);
        $this->assign('requestPage', $requestPage);
        $this->assign('chapter_id',$chapter_id);
        $this->assign('keyword', $keyword);
        $this->display('knowledgeItem');

    }

    /**
     * @function 编辑单个知识点页面
     * @Author   许加明
     * @DateTime 2017-3-28 14:31:20
     * @param    null
     * @return   null
     */
    public function editKnowledge(){
        $user = D('Sysuser')->getMyselfInfo(session('account'));
        $id = I('get.id');
        $chapter = M('knowledge')->where(array('del_flag'=>1,'id'=>$id))->field('chapter_id')->find();
        $chapterList = D('Knowledge')->getTheChapterForLession('', $chapter['chapter_id']);
        if(in_array($user['role'],array(1,3,4))){
            $data = M('Knowledge')->where(array('id'=>$id,'del_flag'=>'1'))->find();
        }else{
            $data = null;
        }
        $this->assign('chapter_id', $chapter['chapter_id']);
        $this->assign('chapterList',$chapterList);
        $this->assign('data',$data);
        $this->display('editKnow');
    }

    /**
     * @function 更新单个知识点
     * @Author   许加明
     * @DateTime 2017-3-23 16:15:17
     * @param    null
     * @return   null
     */
    public function updateKnowledge(){
        $data = D('Knowledge')->updateKnowledge();

        $this->ajaxReturn($data,'json');
    }

    /**
     * @function 添加单个知识点
     * @Author   许加明
     * @DateTime 2017-3-28 14:32:47
     * @param    null
     * @return   null
     */
    public function addKnowledge(){
        $data = D('Knowledge')->addKnowledge();

        $this->ajaxReturn($data,'json');
    }


    /**
     * @function 删除单个数据
     * @Author   许加明
     * @DateTime 2017-3-23 16:15:17
     * @param    null
     * @return   null
     */
    public function deleteKnowledge(){
        $data = D('Knowledge')->deleteKnowledge();
        $this->ajaxReturn($data,'json');
    }

    /**
     * @function 批量添加页面展示
     * @Author   许加明
     * @DateTime 2017-3-28 15:08:48
     * @param    null
     * @return   null
     */

    public function showImportKnow(){
        $this->display('importKnowledgePage');
    }

    /**
     * @function 获取导入知识点模板
     * @Author   许加明
     * @DateTime 2017-3-28 17:31:01
     * @param    null
     * @return
     */
    public function importKnowledgeTemplate(){
        //设置列
        $cellName = array('A','B','C');
        //设置表头
        $cellHeader = array('知识点名','所对应章节','注释');

        outputExcel('导入知识点模板',$cellName,$cellHeader);
    }

    /**
     * @function 获取导入知识点模板
     * @Author   许加明
     * @DateTime 2017-3-28 17:31:01
     * @param    null
     * @return
     * @modify By 梁轩豪           修改超级管理员的导出模板还包括课程所在学院，其他角色就是返回当前所属学院的数据
     */
    public function outputChapter(){

        $dept = getdeptId();
        //控制选择学院的按妞是否显示
        if($dept == 0) {
            $cellName = array('A','B','C','D');
            //设置表头
            $cellHeader = array('章节编号','章节名称','所属课程','课程所属学院');
            //设置数据
            $data = M('Chapter')->field(array('kh_chapter.id','kh_chapter.name','kh_lession.name as lessionname','kh_college.name as collegename'))
                ->join('LEFT JOIN kh_lession ON kh_chapter.lession_id = kh_lession.id')
                ->join('LEFT JOIN kh_college ON kh_college.id = kh_lession.college_id')
                ->order('kh_chapter.lession_id,kh_chapter.sortnumber')->select();
        }else {
            //设置列
            $cellName = array('A','B','C');
            //设置表头
            $cellHeader = array('章节编号','章节名称','所属课程');
            //设置数据
            $data = M('Chapter')->field(array('kh_chapter.id','kh_chapter.name','kh_lession.name as lessionname'))
                ->join('LEFT JOIN kh_lession ON kh_chapter.lession_id = kh_lession.id')->where(array('kh_lession.college_id'=>$dept))
                ->order('kh_chapter.lession_id,kh_chapter.sortnumber')->select();
        }
        //p($data);
        outputExcel('章节数据',$cellName,$cellHeader,$data);
    }


    public function importKnowlodge(){
        //实例化上传类
        $upload = new \Think\Upload();
        //设置上传文件大小，此处为 3M
        $upload->maxSize = 3145728;
        //上传文件类型
        $upload->exts = array('xlsx','xls');
        //设置时区为中国
        date_default_timezone_set('prc');
        $time = time();
        $tempName = $time."".rand(1,10000);
        //设置文件名保存为时间加随机数
        $upload->saveName = $tempName;
        //设置文件保存路径
        $upload->savePath = '/knowledge_import/';
        //上传文件
        $info = $upload->uploadOne($_FILES['knowledge_import']);

        $result = array();

        if(!$info){
            $result['status'] = false;
            $result['msg'] = '上传失败！';
        }else{
            $result['status'] = true;
            $result['msg'] = '上传成功！';

            //获取上传后的文件名
            $file_name = $upload->rootPath.$info['savepath'].$info['savename'];
            //获得文件的后缀名
            $exts = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
            //调用函数，将数据保存到数据库，得到返回的错误集
            $excelData = getDataFromUpExcel($file_name,'xlsx',
                array('name','chapter_id','comment'));

            //错误结果集
            $errList = null;
            $i = 0;
            if(sizeof($excelData) == 0){
                $list['msg'] = '导入成功！';
                $list['code'] = 1;
            }else{
                foreach($excelData as $num => $arr){
                    $info = null;
                    $knowledge = D('Knowledge');

                    if(!$knowledge->create($arr)){
//                    array_push($errList,array($num => $knowledge->getError()));
                        $errList[$num]['arrs'] = $knowledge->getError();
                    }else{
                        if($knowledge->add() === false){
                            $info = '系统存入出错！';
//                        array_push($errList,array($num => $info));
                            $errList[$num]['arrs'] = $info;
                        }else{

                        }
                    }
                    $i++;
                }

                $errResult = '';

                foreach($errList as $num => $value){
                    $errResult = $errResult.'<p>第'.($num+2).'行存在错误：';
                    foreach($errList[$num]['arrs'] as $key => $value2){
                        $errResult=$errResult.$value2;
                    }
                    $errResult = $errResult.'</p>';
                }

                $list['msg'] = $errResult;
                $list['code'] = 2;
            }
            $this->ajaxReturn($list,'json');
        }
    }

    public function test(){
//        p(getDataFromUpExcel('./Uploads/student_import/2017-03-15/1489583713309.xls','xls',
//            array('学号','姓名','性别','学院','专业','年级','班级','手机号','邮箱','备注')));


//        p(D('Knowledge')->updateKnowledge());
//        p(I('get.'));
    }

}