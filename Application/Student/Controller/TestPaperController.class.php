<?php
namespace Student\Controller;
use Common\Controller\StudentBaseController;

/**
 * Class TestPaperController
 * @package Student\Controller
 * 功能：学生端试卷接口
 * @author：xuanhao
 */
class TestPaperController extends StudentBaseController {

    /**
     * @Function: 获取所有符合条件的试卷
     * @Author: xuanhao
     * @DateTime: ${DATE} ${TIME}
     *
     */
    public function paperList() {
        $stu_account = session('stu_account');  //获取登录的学生账户名
        $type_id = I('type_id','');         //获取试卷的测试类型
        $requestPage = I('requestPage', 1);     //获取请求的页码数
        $rows = 3;		                        //每页展示的数据

        //获取学生所属的行课班级，对应相应的课程
        $course_id_temp = M('class_student')->where(array('account' => $stu_account))->field('courseclass_id')->select();
        $course_id = array_column($course_id_temp,'courseclass_id');    //行课班级可能为多个，所以转化为一维数组，进行查找
        //获取该行课班级下的所有的试卷id
        $map['courserclass_id'] = array('in',$course_id);
        $paperIds_temp = M('paper_courserclass as t1')->field('testpaper_id, courserclass_id, t1.start_time,t1.end_time,kh_courseclass.name as courseclassName')->
            join('kh_courseclass ON kh_courseclass.id = courserclass_id')->where($map)->select();
        //p($paperIds_temp);die;
        $paperIds = array_column($paperIds_temp,'testpaper_id');
        //获取已经做过的试卷
        $didPaper_temp = M('score')->where(array('account' => $stu_account))->field('testpaper_id')->select();
        $didPaper = array_column($didPaper_temp,'testpaper_id');
        $canPaperIds = array_diff($paperIds, $didPaper);        //获取可以考试的试卷
        //判断是否有可考试或练习的试卷，没有返回信息并退出
        if($canPaperIds == null) {
            $result['status'] = 0;
            $result['msg'] = "暂时没有可考试或练习的试卷";
            echo json_encode($result);
            exit;
        }
        $paperM = D('Testpaper');
        //试卷的详细信息
        $paperListInfo1 = $paperM->getPaper($canPaperIds, $requestPage, $rows, $type_id);
        //将每张试卷的开始考试与结束时间加上
        $paperListInfo = $paperM->getPaperTime($paperListInfo1, $paperIds_temp);
        $result = array(
            'status' => 1,
            'msg' => '获取信息成功',
            'info' => $paperListInfo
        );
        //p($result);die;
        echo json_encode($result);

    }

    public function getThePaper_info() {
        $paperM = D('Testpaper');
        $result = $paperM->paper_info();
        //p($result);die;
        echo json_encode($result);
    }


    /**
     * @Function:
     * @Author: xuanhao
     * @DateTime: ${DATE} ${TIME}
     * 点击试卷，过渡页面
     */
    public function detailInfo(){
        $testpaper_id = I('id');
        $courseclass_id = I('courseclass_id');
        if(empty($testpaper_id) || empty($courseclass_id)){
            $this->error();
        }else{
            $this->assign('id',$testpaper_id);
            $this->assign('courseclass_id', $courseclass_id);
            $this->display();
        }
    }

    /**
     * 考试页面
     * @function
     * @AuthorPJY
     * @DateTime  2017-07-21T11:26:25+0800
     * @return    [type]                   [description]
     */
    public function paper(){
        $paper_id = I('post.paper_id');
        $course_id = I('post.course_id');

        // 传递变量为空
        if( empty($paper_id) && empty($course_id)  ) {
            $this->error('请按照正确途径进入!');
        }
        
        // 检验是否在考试时间
        $status = D('paper_courserclass')->isIntime($paper_id, $course_id);
        // 不在考试时间之内
        if(!$status){
            $this->error('考试已经结束');
        }

        // 检验学生是否已经有该试卷成绩, 防止再次考试
        $score = M('score')->where(array('account'=>session('stu_account'), 'testpaper_id'=>$paper_id))->getField('score');
        if($score != NULL){
           $this->error('您已经完成该试卷了!');
        }

        else{
            // 查找试卷信息
            $paper_list = D('paper_courserclass')->getPapaerInfo($paper_id, $course_id);
            // 查找试卷题目
            $quesion_list = D('paper_question')->getQuestionList($paper_id);
            // $this->ajaxReturn($paper_list);
            $this->assign('paperInfo',$paper_list);
            $this->assign('question',$quesion_list);
            $this->display();
        }

    }


    /**
     * 检验试题答案
     * @function
     * @AuthorPJY
     * @DateTime  2017-07-22T15:56:04+0800
     */
    public function CheckAnswer(){
        $answer = I('post.answer');                         // 问题信息
        $testpaper_id = I('post.testpaper_id');             // 试卷id
        $student_id = M('student')
                    ->where(array('account'=>session('stu_account')))->getField('id');
        $question_answer = [];
        $status = true;

        // 格式化为 问题id=>答案信息
        foreach ($answer as $key => $value) {
            $question = explode("question_", $key);
            $question_id = $question[1];                    // 问题id
            $question_answer[$question_id] = $value;        // 答案内容(选择题为id, 判断题为T,F 填空题为内容)
                             
        }

        // 如果成绩存在则不进行保存
        $score = M('score')->where(array('account'=>session('stu_account'), 'testpaper_id'=>$testpaper_id))->getField('score');
        if($score != NULL) {
            $status = false;
        }else{
            $checkArr = D('answer')->checkAnswer($question_answer);                     // 检验答案
            $info = D('testFinal')->saveAnswer($testpaper_id, $student_id, $checkArr);                   // 存入答案
            $score = D('score')->saveScore(session('stu_account'), $testpaper_id, $checkArr);                        // 存入最终成绩 
        }


            $this->ajaxReturn($status);               
    }


}
?>