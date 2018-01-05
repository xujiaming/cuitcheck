<?php

namespace Home\Controller;
use Common\Controller\HomeBaseController;

/**
 * Class ChapterMgrController
 * @package Home\Controller
 *
 * 在许加明的基础上进行了一些修改，因为需求修改了。
 * 修改者：梁轩豪
 */
class ChapterMgrController extends HomeBaseController{
    /**
     * @function 展示章节列表
     * @Author   许加明
     * @DateTime 2017-3-17 17:23:21
     * @param    无 
     * @return   无
     */
    public function chapterList (){
        $course = M('Course')->field('name,id')->select();
        $dept = getdeptId();
        //控制选择学院的按妞是否显示
        if($dept == 0) {
            $select_show = true;
            $college = M('college')->field('id,name')->select();  //默认显示第一个学院
            $defaultDept = M('college')->field('id')->find();  //返回查找的第一个学院的id
            $choiceDept = I('choiceDept', $defaultDept['id']);          //超级管理员可选择学院进行查看，默认为查询的一个学院的数据
            $map1 = array(
                'del_flag' => 1,
                'college_id' => $choiceDept
            );
            $this->assign('collegeList', $college);
        }else {
            $select_show = false;
            $map1 = array(
                'del_flag' => 1,
                'college_id' => $dept
            );
        }
        $lessionList = D('Chapter')->getLession($map1);
        $this->assign('lessionList', $lessionList);
        $this->assign('course',$course);
        $this->assign('select_show', $select_show);
        $this->display();
    }

    /**
     * @function 得到章节树列表，当是超级管理员的时候，默认的学院为查询获得的第一个学院
     * @Author   许加明
     * @DateTime 2017-3-18 13:20:19
     * @param    null
     * @return   json
     */
//    public function getChapterList(){
//        $course = M('Course')->field('name,id')->select();
//        //组织json
//        $i = 0;
//        foreach($course as &$value){
//            $i == 0? $value['spread'] = true :$value['spread'] = false;
//
//            $children = M('Chapter') -> where(array('course_id'=>$value['id'],'del_flag'=>1))
//                -> order('sortnumber') -> select();
//
//            $j = 0;
//            foreach($children as &$value2){
//                $j++;
//                $value2['name'] = '第'.$j.'章:'.$value2['name'].'('.$value2['sortnumber'].')';
//                $value2['homePart'] = 'kids';
//            }
//            unset($value2);
//
//            $value['children'] = $children;
//            $value['homePart'] = 'parent';
//            $i++;
//        }
//        unset($value);
//        $this->ajaxReturn($course,'json');
//    }
    public function getChapterList(){
        $dept = getdeptId();
        if($dept == 0) {
            $defaultDept = M('college')->field('id')->find();  //返回查找的第一个学院的id
            $choiceDept = I('choiceDept', $defaultDept['id']);          //超级管理员可选择学院进行查看，默认为查询的一个学院的数据
            $map1 = array(
                'del_flag' => 1,
                'college_id' => $choiceDept
            );
        }else {
            $map1 = array(
                'del_flag' => 1,
                'college_id' => $dept
            );
        }
        $course = M('course')->field('id,name')->where($map1)->select();
        //组织json
        //$i,$j,$k的意义：默认第一个展开
        $i = 0;
        $k = 0;
        foreach ($course as &$value1) {
            $k == 0? $value1['spread'] = true :$value1['spread'] = false;
            //获取学科下的课程
            $children1 = M('lession')->field('id,name,course_id')->where(array('course_id'=>$value1['id'], 'del_flag'=>1))->select();
            //获取章节
            foreach($children1 as &$value2){
                $i == 0? $value2['spread'] = true :$value2['spread'] = false;

                $children2 = M('Chapter') -> where(array('lession_id'=>$value2['id'],'del_flag'=>1))
                    -> order('sortnumber') -> select();

                $j = 0;
                //组装章节
                foreach($children2 as &$value3){
                    $j++;
                    $value3['name'] = '第'.$value3['sortnumber'].'章:'.$value3['name'].'('.$value3['sortnumber'].')';
                    $value3['homePart'] = 'kids';
                }
                unset($value3);

                $value2['children'] = $children2; //压入章节到课程
                $value2['homePart'] = 'parent';
                $i++;
            }
            unset($value2);
            $value1['children'] = $children1;       //压入课程到学科
            $value1['homePart'] = '';
            $k++;

        }
        unset($value1);
        $this->ajaxReturn($course,'json');
    }

    /**
     * @function 得到单个章节信息页面
     * @Author   许加明
     * @DateTime 2017-3-20 16:27:20
     * @param    null 
     * @return   null
     */
    public function getChapterItem(){
        $chapterId = I('id');
//        $course = M('Course')->field('name,id')->select();

        $info = M('Chapter')->join('LEFT join kh_lession ON kh_lession.id = kh_chapter.lession_id')
            ->field('kh_chapter.id,kh_chapter.name,kh_chapter.sortnumber,kh_chapter.create_date,kh_chapter.comment,kh_chapter.lession_id,kh_lession.name as lessionname')
            ->where(array('kh_chapter.id'=>$chapterId))->find();

//        $this->assign('course',$course);
        $this->assign('info',$info);
        $this->display('chapterItem');
    }

    /**
     * @function 更新章节
     * @Author   许加明
     * @DateTime 2017-3-20 19:46:03
     * @param    null
     * @return   null
     */
    public function updateChapter(){
        $result = D('Chapter')->updateChapter();
        $this->ajaxReturn($result,'json');
    }

    /**
     * @function 删除章节
     * @Author   许加明
     * @DateTime 2017-3-20 19:46:03
     * @param    null
     * @return   null
     */
    public function deleteChapter(){
        $result = D('Chapter')->deleteChapter();
        $this->ajaxReturn($result,'json');
    }

    /**
     * @function 添加章节
     * @Author   许加明
     * @DateTime 2017-3-20 19:59:39
     * @param    null
     * @return   null
     */
    public function addChapter(){
        $result = D('Chapter')->addChapter();
        $this->ajaxReturn($result,'json');
    }

    /**
     * @Function:  超级管理员添加章节时候的学院联动学科课程
     * @Author: xuanhao
     * @DateTime: ${DATE} ${TIME}
     *
     */
    public function adminGetDept()
    {
        $dept = I('choiceDept1', '');
        $map1 = array(
            'del_flag' => 1,
            'college_id' => $dept
        );
        $data = D('Chapter')->getLession($map1);
        $this->ajaxReturn($data,'json');
    }
}