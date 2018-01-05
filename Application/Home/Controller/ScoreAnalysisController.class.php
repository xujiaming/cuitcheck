<?php
/**
 * @Created by PhpStorm.
 * @Function: 成绩分析统计控制器模块
 * @User: xuanhao
 * @Date: 2017/7/20
 * @Time: 14:56
 */
namespace Home\Controller;
use Common\Controller\HomeBaseController;

class ScoreAnalysisController extends HomeBaseController {

    /**
     * @Function: 考试记录首页模板渲染数据
     * @Author: xuanhao
     * @DateTime: ${DATE} ${TIME}
     *
     */
    public function testRecordList() {
        //获得请求的页码
        $requestPage = I('requestPage', '1');
        //查询的关键词
        $keyword = I('keyword', '');
        $college_id = I('college_id', '');
        $type_id = I('type_id', '');
        //查询的学校分级信息
        //一页显示的数据条数
        $rows = 8;
        $accInfo = session('accInfo');
        $dept = $accInfo['dept_id'];
        $data = null;
        $college = '';
        //如果为超级管理员或者系统管理员，可查看所有
        if($accInfo['role'] == '1') {
            $college = M('college')->field('id, name')->where(array('del_flag' => 1))->select();
        }else if($accInfo['role'] == '3') {
            //学院管理员,只能查看本学院
            $dept_id = session('accInfo.dept_id');
            $college = M('college')->field('id, name')->where(array('del_flag' => 1, 'id' => $dept_id))->select();
        }else {

        }
        $testType = M('dict')->field('value, label')->where(array('type' => 'testType'))->select();
        $data = D('PaperCourserclass')->getAllList($requestPage, $keyword, $college_id, $type_id, $rows);
        $this->assign('testType', $testType);
        $this->assign('college', $college);
        $this->assign('paperList',$data['list']);
        $this->assign('pages', $data['pages']);
        $this->assign('requestPage', $requestPage);
        $this->assign('college_id', $college_id);
        $this->assign('type_id', $type_id);
        $this->assign('keyword', $keyword);
        $this->display();
    }

    /**
     * @Function: 当前试卷使用开考的行课班级
     * @Author: xuanhao
     * @DateTime: ${DATE} ${TIME}
     *
     */
    public function detailPaper() {
        $paperId = I('id', '');     //试卷id
        $requestPage = I('requestPage', '1');
        $rows = 8;
        $data = D('PaperCourserclass')->getCoursePaper($paperId, $requestPage, $rows);
        $paperName = M('testpaper')->field('name')->where(array('id' => $paperId))->find();
        $this->assign('paperId', $paperId);
        $this->assign('requestPage', $requestPage);
        $this->assign('pages', $data['pages']);
        $this->assign('paperName', $paperName['name']);
        $this->assign('paperList', $data['list']);
        $this->display();

    }

    /**
     * @Function: 考过的试卷成绩记录展示  delete？？
     * @Author: xuanhao
     * @DateTime: ${DATE} ${TIME}
     *
     */
//    public function paperScoreList() {
//        //获得请求的页码
//        $requestPage = I('requestPage', '1');
//        //查询的关键词
//        $keyword = I('keyword', '');
//        $college_id = I('college_id', '');
//        $type_id = I('type_id', '');
//        //查询的学校分级信息
//        //一页显示的数据条数
//        $rows = 8;
//        $accInfo = session('accInfo');
//        $dept = $accInfo['dept_id'];
//        $data = null;
//        $college = '';
//        //如果为超级管理员或者系统管理员，可查看所有
//        if($accInfo['role'] == '1') {
//            $college = M('college')->field('id, name')->where(array('del_flag' => 1))->select();
//        }else if($accInfo['role'] == '3') {
//            //学院管理员,只能查看本学院
//            $dept_id = session('accInfo.dept_id');
//            $college = M('college')->field('id, name')->where(array('del_flag' => 1, 'id' => $dept_id))->select();
//        }else {
//
//        }
//        $data = D('PaperCourserclass')->getScorePaper($college_id, $type_id, $keyword, $rows, $requestPage);
//        $testType = M('dict')->field('value, label')->where(array('type' => 'testType'))->select();
//        $this->assign('testType', $testType);
//        $this->assign('college', $college);
//        $this->assign('paperList',$data['list']);
//        $this->assign('pages', $data['pages']);
//        $this->assign('requestPage', $requestPage);
//        $this->assign('college_id', $college_id);
//        $this->assign('type_id', $type_id);
//        $this->assign('keyword', $keyword);
//        $this->display();
//    }

    /**
     * @Function: 获取该班级下（行课班级）所有学生该试卷的成绩信息 ???????????????????
     * @Author: xuanhao
     * @DateTime: ${DATE} ${TIME}
     *
     */
//    public function detailScore() {
//        $paperId = I('paperId');
//        $courseclass_id = I('courseclass_id');
//        //获得请求的页码
//        $requestPage = I('requestPage', '1');
//        //$sortType = I('sortType', '0');   //排序
//        //查询的关键词
//        $keyword = I('keyword', '');
//        $data = D('PaperCourserclass')->generalPaper($paperId, $courseclass_id, $keyword, $requestPage);
//        //对一些基本信息的分装
//        $passdata = array(
//            'paperId' => $paperId,
//            'courseclass_id' => $courseclass_id,
//            'requestPage' => $requestPage,
//            'keyword' => $keyword
//        );
//        $this->assign('data', $data);
//        $this->assign('scoreList', $data['scoreList']);
//        $this->assign('score_segment', $data['score_segment']);
//        $this->assign('passdata', $passdata);
//        $this->display();
//    }

    /**
     * @Function: 导出该班级的学生成绩
     * @Author: xuanhao
     * @DateTime: ${DATE} ${TIME}
     *
     */
    public function exportCourseScore() {
        $paperId = I('paperId');
        $courseclass_id = I('courseclass_id');
        //获取班级名称
        $courseName = M('courseclass')->field('name')->where(array('id' => $courseclass_id))->find();
        $data = D('PaperCourserclass')->exportData($paperId, $courseclass_id);
        $title = $courseName['name'].$data['paperName'].'成绩表';  //设置文件名
        $cellName = array('A','B','C','D','E','F','G','H'); //设置表格单元格
        $cellHeader = array('学号','姓名','性别','成绩','行政班级');    //表头
        $data2 = $data['scoreList'];    //成绩信息
        //调用公共方法导出文件
        outputExcel($title,$cellName,$cellHeader,$data2);
    }

    /**
     * @Function: 学生检索页面渲染
     * @Author: xuanhao
     * @DateTime: ${DATE} ${TIME}
     *
     */
    public function studentScore() {
        //D('PaperCourserclass')->getSomeTotalData();
        $this->display();
    }

    public function guoduSearch() {
        $keyword = I('keyword','');
        if(is_numeric($keyword)) {
            $this->searchStudent($keyword);
        }else {
//            $map['kh_student.del_flag'] = array('eq', '1');
//            $map['kh_student.name'] = array('like', '%'.$keyword.'%');
//            $data  = M('student')->field('id,account,name,kh_college.name as colname,kh_class.name as classname')
//                ->join('kh_college ON kh_college.id = kh_student.dept_id')
//                ->join('kh_class ON kh_class.id = kh_student.class_id')
//                ->where($map)->select();
//            return $data;
//            exit;
            $this->error('请输入正确的学号！');
        }
    }
    /**
     * @Function:
     * @Author: xuanhao
     * @DateTime: ${DATE} ${TIME}
     * 根据学号检索出的学生的成绩信息
     */
    public function searchStudent($stuAccount) {
        //$keyword = I('keyword','');
        $data = D('PaperCourserclass')->getStudentScoreInfo($stuAccount);
        //p($data);die;
        $this->assign('studentScore', $data);
        $this->display('searchStudent');


    }

    /**
     * @Function: 行课班级列表
     * @Author: xuanhao
     * @DateTime: ${DATE} ${TIME}
     *
     */
    public function classList() {
        $requestPage = I('requestPage', 1);	//获取请求的页码数
        $grade_id = I('grade_id','');
        $college_id = I('college_id','');
        $keyword = I('keyword', '');		//获取查询关键词
        $rows = 8;		//每页展示的数据
        $accInfo = session('accInfo');
        $dept = $accInfo['dept_id'];
        $data = null;
        $college = '';
        $showFlag = '';     //判断某些角色的视图组件显示标识
        //如果为超级管理员或者系统管理员，可查看所有
        if($accInfo['role'] == '1') {
            $college = M('college')->field('id, name')->where(array('del_flag' => 1))->select();
            $showFlag = 'true';
        }else if($accInfo['role'] == '3') {
            //学院管理员,只能查看本学院
            $dept_id = session('accInfo.dept_id');
            $college = M('college')->field('id, name')->where(array('del_flag' => 1, 'id' => $dept_id))->select();
            $showFlag = 'true';
        }else {
            $showFlag = 'false';
        }
        $grade = M('grade')->field('id,name')->where(array('del_flag' => 1))->select();
        $data = D('PaperCourserclass')->getClassList($requestPage, $keyword, $rows,$grade_id, $college_id);
        $this->assign('college', $college);
        $this->assign('grade', $grade);
        $this->assign('pages', $data['pages']);
        $this->assign('requestPage',$requestPage);
        $this->assign('keyword',$keyword);
        $this->assign('grade_id', $grade_id);
        $this->assign('college_id',$college_id);
        $this->assign('classList',$data['list']);
        $this->assign('showFlag', $showFlag);
        $this->display();
    }

    /**
     * @Function: 处理该行课班级下的所有试卷
     * @Author: xuanhao
     * @DateTime: ${DATE} ${TIME}
     *
     */
    public function classPaper() {
        $id = I('id');
        $keyword = I('keyword','');
        $requestPage = I('requestPage',1);
        $paper_classInfo = M('courseclass')->field('name')->where(array('id'=>$id))->find();
        $data = D('PaperCourserclass')->getClassPaper($id, $keyword, $requestPage);
        //p($data);die;
        $this-> assign('paperList', $data['list']);
        $this->assign('pages',$data['pages']);
        $this->assign('requestPage', $requestPage);
        $this->assign('keyword', $keyword);
        $this->assign('courseclass_id', $id);
        $this->assign('className', $paper_classInfo['name']);
        $this->display();
    }

    /**
     * @Function: 该行课班级下的试卷详情
     * @Author: xuanhao
     * @DateTime: ${DATE} ${TIME}
     *
     */
    public function paperScore() {
        $paperId = I('paperId','');
        $courseclass_id = I('courseclass_id','');
        //获得请求的页码
        $requestPage = I('requestPage', '1');
        //$sortType = I('sortType', '0');   //排序
        //查询的关键词
        $keyword = I('keyword', '');
        //分数范围获取
        $score_min = I('score_min', '');
        $score_max = I('score_max', '');
        $data = D('PaperCourserclass')->generalPaper($paperId, $courseclass_id, $keyword, $requestPage, $score_min, $score_max);
        //p($data);die;
        //对一些基本信息的分装
        $passdata = array(
            'paperId' => $paperId,
            'courseclass_id' => $courseclass_id,
            'requestPage' => $requestPage,
            'keyword' => $keyword,
            'score_min' => $score_min,
            'score_max' => $score_max
        );
        $this->assign('data', $data);
        $this->assign('scoreList', $data['scoreList']);
        $this->assign('score_segment', $data['score_segment']);
        $this->assign('passdata', $passdata);
        $this->display();
    }

    /**
     * @Function: 试卷的知识点分析
     * @Author: xuanhao
     * @DateTime: ${DATE} ${TIME}
     *
     */
    public function paperKnowledge() {
        $paperId = I('paperId','');
        $courseclassId = I('courseclass_id','');
        $data = D('PaperCourserclass')->getPaperKnowledge($paperId, $courseclassId);    //获取试卷的各类型各难度题目的数量
        //p($data['knowledgeData']);die;
        $this->assign('testPaper', $data['testPaper']);//试卷信息
        $this->assign('testPaperQuesNum', $data['testPaperQuesNum']);//试题数量
        $this->assign('easyNumArray', $data['easyNumArray']);//简单题数组
        $this->assign('normalNumArray', $data['normalNumArray']);//普通题数组
        $this->assign('hardNumArray', $data['hardNumArray']);//困难题数组
        $this->assign('testpaper_id', $paperId);
        $this->assign('knowledge', $data['knowledgeData']);
        //p($data);die;
        $this->display();
    }
    /**
     * @Function: 进入行政班级显示该班级下的学生列表 ???????????????????
     * @Author: xuanhao
     * @DateTime: ${DATE} ${TIME}
     *
     */
//    public function classStudent() {
//        $id = I('id');
//        $keyword = I('keyword','');
//        $requestPage = I('requestPage',1);
//        $classInfo = M('class')->field('name')->where(array('id'=>$id))->find();
//        $data = D('PaperCourserclass')->getClassStudent($id, $keyword, $requestPage);
//        $this-> assign('studentList', $data['list']);
//        $this->assign('pages',$data['pages']);
//        $this->assign('requestPage', $requestPage);
//        $this->assign('keyword', $keyword);
//        $this->assign('class_id', $id);
//        $this->assign('className', $classInfo['name']);
//        $this->display();
//    }

    /**
     * @Function: 获取该学生的所有成绩信息   ？？？？？？？？？？？？？？？？
     * @Author: xuanhao
     * @DateTime: ${DATE} ${TIME}
     *
     */
//    public function getTheStudentScore() {
//        $stuId = I('stuId', '');
//        $keyword = I('keyword','');
//        $requestPage = I('requestPage',1);
//        $stuInfo = M('student')->where(array('id'=>$stuId))->find();
//        $data = D('PaperCourserclass')->getTheStudentAllScore($stuId, $keyword, $requestPage);
//        $this->assign('studentScore', $data['list']);
//        $this->assign('pages',$data['pages']);
//        $this->assign('requestPage', $requestPage);
//        $this->assign('keyword', $keyword);
//        $this->assign('class_id', $stuInfo['class_id']);
//        $this->assign('stuName', $stuInfo['name']);
//        $this->assign('stuId',$stuId);
//        $this->display();
//    }

    /**
     * @Function: 导出该学生的成绩数据(按行课班级查看时)
     * @Author: xuanhao
     * @DateTime: ${DATE} ${TIME}
     *
     */
    public function exportScore() {
        $stuId = I('stuId');
        $data = D('PaperCourserclass')->exportStudentScore($stuId);
        $title = $data[0]['classname'].$data[0]['stuname'].'成绩表';  //设置文件名
        $cellName = array('A','B','C','D','E','F','G','H'); //设置表格单元格
        $cellHeader = array('学号','姓名','性别','试卷名称','成绩','行政班级','记录时间');    //表头
        //调用公共方法导出文件
        outputExcel($title,$cellName,$cellHeader,$data);

    }

    //按年级或班级成绩的条件搜索
    /**
     * @Function: 展示按条件搜索页面（成绩检索菜单）
     * @Author: xuanhao
     * @DateTime: ${DATE} ${TIME}
     *
     */
    public function scoreSearch() {
        $accInfo = session('accInfo');
        $college = '';
        //如果为超级管理员或者系统管理员，可查看所有
        $grade_list = M('grade')->field('id, name')->where(array('del_flag' => 1))->select();
        if($accInfo['role'] == '1') {
            $college = M('college')->field('id, name')->where(array('del_flag' => 1))->select();
        }else if($accInfo['role'] == '3') {
            //学院管理员,只能查看本学院
            $dept_id = session('accInfo.dept_id');
            $college = M('college')->field('id, name')->where(array('del_flag' => 1, 'id' => $dept_id))->select();
        }
        $this->assign('grade_list', $grade_list);
        $this->assign('college_list', $college);
        $this->display();
    }
    /**
     * @Function: 对学院，学科，课程，和班级联动数据接口
     * @Author: xuanhao
     * @DateTime: ${DATE} ${TIME}
     *
     */
    //联动选择
    public function linkSearch() {
        //获取前台传来的所选中的学院id
        $dept_id = I('dept_id','');
        $course_id = I('course_id','');
        $lession_id = I('lession_id','');
        $grade_id = I('grade_id','');
        $data = '';
        //返回学院下的学科数据
        if($dept_id != '' && $course_id == '') {
            $course_list = M('course')->where(array('college_id'=>$dept_id))->select();
            $opt1 = "";
            //该学院下专业
            if(empty($course_list)) {
                $opt1 = "<option value=''>暂无数据</option>";
            }else {
                foreach($course_list as $key => $val) {
                    $opt1.="<option value='{$val['id']}'>{$val['name']}</option>";
                }
            }
//
            $data = array(
                'course' => $opt1,
            );
        }else {

        }
        //返回学院下的学科下的课程数据
        if($course_id != '' && $dept_id == '') {
            $lession_list = M('lession')->where(array('course_id'=>$course_id))->select();
            $opt1 = "";
            //该学院下专业
            if(empty($lession_list)) {
                $opt1 = "<option value=''>暂无数据</option>";
            }else {
                foreach($lession_list as $key => $val) {
                    $opt1.="<option value='{$val['id']}'>{$val['name']}</option>";
                }
            }

            $data = array(
                'lession' => $opt1,
            );
        }
        //返回学院下课所属课程下的试卷数据
        if($dept_id != '' && $lession_id != '') {
            $opt1 = "";
            $paper_list = M('testpaper')->where(array('college_id'=>$dept_id,'lession_id'=>$lession_id))->select();
            if(empty($paper_list)) {
                $opt1 = "<option value=''>暂无数据</option>";
            }else {
                foreach($paper_list as $key => $val) {
                    $opt1.="<option value='{$val['id']}'>{$val['name']}</option>";
                }
            }
            $data = array(
                'paper' => $opt1
            );
        }
        //返回学院下的指定课程以及指定年级的班级数据
        if($dept_id != '' && $lession_id != '' && $grade_id != '') {
            $courseclass = M('courseclass')->where(array('college_id'=>$dept_id,'lession_id'=>$lession_id,'grade_id'=>$grade_id))->select();
            $opt2 = "";
            //该学院下专业

            if(empty($courseclass)) {
                $opt2 = "<option value=''>暂无数据</option>";
            }else {
                foreach($courseclass as $key => $val) {
                    //$opt2.="<option value='{$val['id']}'>{$val['name']}</option>";
                    $opt2.="<input type='checkbox' name='courseclass[$key]' title='{$val["name"]}' lay-skin='primary' value='{$val['id']}'>";
                }
            }
//
            $data = array(
                'courseclass' => $opt2
            );
        }

        //p($data);
        $this->ajaxReturn($data,'json');
    }

    /**
     * @Function: 查询按条件选择的成绩数据
     * @Author: xuanhao
     * @DateTime: ${DATE} ${TIME}
     *
     */
    public function findTheData() {


        $data1['dept_id'] = I('dept_id','');        //学院
        $data1['course_id'] = I('course_id','');    //学科
        $data1['lession_id'] = I('lession_id','');  //课程
        $data1['grade_id'] = I('grade_id','');      //年级
        $data1['paper_id'] = I('paper_id','');      //试卷
        $data1['courseclass_id'] = I('courseclass',''); //班级
        //判断班级参数类型，js在链接中传递参数，需要为字符串类型，初始化的查询结果该参数可能为数组
        //$courseclass_id_temp 主要是班级数据传递
        if(is_array($data1['courseclass_id'])) {
            $courseclass_id_temp = implode(',', $data1['courseclass_id']);      //将数组转化为字符串
        }else {
            $courseclass_id_temp = $data1['courseclass_id'];
        }

        $courseclass_id = $data1['courseclass_id'];     //班级参数值
        $requestPage = I('requestPage', '1');
        //$sortType = I('sortType', '0');   //排序
        //查询的关键词
        $keyword = I('keyword', '');
        //分数范围获取
        $score_min = I('score_min', '');
        $score_max = I('score_max', '');
        $data = D('PaperCourserclass')->generalPaper($data1['paper_id'], $courseclass_id, $keyword, $requestPage, $score_min, $score_max);
        //对一些基本信息的分装
        $passdata = array(
            'paperId' => $data1['paper_id'],
            'courseclass_id' => $courseclass_id_temp,
            'requestPage' => $requestPage,
            'keyword' => $keyword,
            'score_min' => $score_min,
            'score_max' => $score_max
        );
        $this->assign('data', $data);
        $this->assign('scoreList', $data['scoreList']);
        $this->assign('score_segment', $data['score_segment']);
        $this->assign('passdata', $passdata);
        $this->display();
    }

    /**
     * @Function: 对搜索和的结果的数据进行导出
     * @Author: xuanhao
     * @DateTime: ${DATE} ${TIME}
     *
     */
    public function exportSearchScore() {
        $paperId = I('paperId');
        $courseclass_id = I('courseclass_id');
        $data = D('PaperCourserclass')->exportData($paperId, $courseclass_id);
        $title = $data['paperName'].'成绩表';  //设置文件名
        $cellName = array('A','B','C','D','E','F','G','H'); //设置表格单元格
        $cellHeader = array('学号','姓名','性别','成绩','行政班级');    //表头
        $data2 = $data['scoreList'];    //成绩信息
        //调用公共方法导出文件
        outputExcel($title,$cellName,$cellHeader,$data2);
    }

}


