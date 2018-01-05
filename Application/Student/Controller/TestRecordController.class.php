<?php
namespace Student\Controller;
use Think\Controller;
use Common\Controller\StudentBaseController;

/**
 * Class TestMgrController
 * @package Student\Controller
 *
 */
class TestRecordController extends StudentBaseController {


    /**
     * 试卷详情页面
     * @function
     * @AuthorPJY
     * @DateTime  2017-07-15T14:33:05+0800
     * @return    [type]                   [description]
     */
    public function detail(){
        $testpaper_id = I('testpaper_id');
        if(empty($testpaper_id)){
            $this->error();
        }else{
            $this->assign('testpaper_id',$testpaper_id);
            $this->display();
        }
    }


    /**
     * 获取试卷详情
     * @function
     * @AuthorPJY
     * @DateTime  2017-07-15T14:33:56+0800
     * @return    [type]                   [description]
     */
    public function getDetails(){
        $account_id = session('stu_account');
        $paper_id = I("testpaper_id");
        $type = I('type', 'all');
        $requestPage = I('requestPage', 1);
        $rows = 4;

        // 如果没有传入参数,返回错误
        if ( empty($paper_id) ) {
            $data['status'] = false;
            $data['msg'] = "请传入相应的参数!";

        }else{
            $data['status'] = true;
            $data['paper'] = D('TestFinal')->getPaperDetails($paper_id, $account_id);                       // 获得试卷详情

            $student_id = M('student')->where(array('account'=>$account_id))->getField('id');

            $list = D('TestFinal')->getQuestionDetails($paper_id, $student_id, $type, $requestPage, $rows); // 获得问题详情
            $data['question'] = $list['question'];     
            $data['pages'] = $list['pages'];        // 分页相关
            $data['requestPage'] = $requestPage;    // 请求页
            $data['type'] = $type;                  // 请求类型
        }
        return $this->AjaxReturn($data);
    }



    /**
     * 获取试卷记录
     * @function
     * @AuthorPJY
     * @DateTime  2017-07-18T10:11:06+0800
     * @return    [type]                   [description]
     */
    public function getRecord(){
        $account_id = session('stu_account');
        $type = I('type');
        $requestPage = I('requestPage', 1);
        $rows = 4;

        // 获得已经结束的考试记录
        $record_info = D("PaperCourserclass")->getRecordInfo($account_id, $type, $requestPage, $rows);

        // 进行数据返回
        $data['pages'] = $record_info['pages'];
        $data['list'] = $record_info['list'];
        $data['requestPage'] = $requestPage;
        $data['type'] = $type;
 
        // var_dump($record_info);  
        return $this->ajaxReturn($data);
    }






  



}