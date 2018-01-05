<?php
/**
 * @Created by PhpStorm.
 * @User: xuanhao
 * @Date: 2017/7/20
 * @Time: 17:14
 */
namespace Home\Model;
use Think\Model;
date_default_timezone_set('PRC');   //设置当前时区为北京时间

class PaperCourserclassModel extends Model {
    protected $tableName = 'paper_courserclass';    //定义主表名

    protected $_validate = array(

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
     * 添加班级信息
     */
    public function addpaper(){
        if (!$this->create()){
            return $this->getError();
        }else{
            $this->add();
            return $this->getret(true);
        }
    }

    private function getret($status){
        $arr['success'] = $status;
        return $arr;
    }


    /**
     * @Function: 学院id检测
     * @Author: xuanhao
     * @DateTime: ${DATE} ${TIME}
     * * @param $value
     * @return bool
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
     * @Function: 试卷类型id检测
     * @Author: xuanhao
     * @DateTime: ${DATE} ${TIME}
     * * @param $value
     * @return bool
     */
    public function checkTypeId($value){
        $course = M('Testtype')->field('id')->where(array('id'=>$value,'del_flag'=>1))->select();
        if(sizeof($course) == 1){
            return true;
        }else{
            return false;
        }
    }

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
     * @Function: 判断试卷考试时间是否已经开始
     * @Author: xuanhao
     * @DateTime: ${DATE} ${TIME}
     *
     */
    public function judgeTimeStart() {
        $map['kh_paper_courserclass.del_flag'] = array('eq', '1');
        $nowTime = date('Y-m-d H:i:s');         //获取系统时间
        $map['kh_paper_courserclass.start_time'] = array('elt', $nowTime);      //试卷开始时间小于当前时间，代表考试已经开始
        $didPaperIds = M('paper_courserclass')->field('testpaper_id')->where($map)->group('testpaper_id')->select();   //获取所有试卷的id集合
        $data = array_column($didPaperIds, 'testpaper_id');     //按testpaper_id字段合并以为数组
        return $data;
    }

    /**
     * @Function: 判断考试时间是否结束
     * @Author: xuanhao
     * @DateTime: ${DATE} ${TIME}
     *
     * @return array
     */
    public function judgeTimeEnd() {
        $map['kh_paper_courserclass.del_flag'] = array('eq', '1');
        $nowTime = date('Y-m-d H:i:s');         //获取系统时间
        $map['kh_paper_courserclass.end_time'] = array('elt', $nowTime);      //试卷结束时间小于当前时间，代表考试已经结束
        $didPaperIds = M('paper_courserclass')->field('testpaper_id')->where($map)->group('testpaper_id')->select();   //获取所有试卷的id集合
        $data = array_column($didPaperIds, 'testpaper_id');     //按testpaper_id字段合并以为数组
        return $data;
    }
    /**
     * 获取符合条件的全部list
     */
    public function getAllList($requestPage, $keyword, $college_id, $type_id, $rows){

        $map['kh_dict.type'] = 'testType';

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
        //系统管理员状态
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
        $paperIds = $this->judgeTimeStart();
        $map['kh_testpaper.id'] = array('in', $paperIds);
        $limit = ($requestPage-1)*$rows.','.$rows;
        if(!empty($paperIds)) {
            $total = M('testpaper')	//获取符合条件的数据条数
            ->field('kh_testpaper.id, kh_testpaper.name, kh_dict.label as typename, kh_college.name as collegename, kh_testpaper.full_score, kh_testpaper.pass_score,kh_testpaper.is_use, kh_testpaper.create_by,kh_testpaper.create_date')
                ->join('JOIN kh_dict ON kh_dict.value = kh_testpaper.type_id')
                ->join('JOIN kh_college ON kh_college.id = kh_testpaper.college_id')
                ->where($map)->count();
            $list = M('testpaper')
                ->field('kh_testpaper.id, kh_testpaper.name, kh_dict.label as typename, kh_college.name as collegename, kh_testpaper.full_score, kh_testpaper.pass_score,kh_testpaper.is_use,kh_testpaper.create_by,kh_testpaper.create_date')
                ->join('JOIN kh_dict ON kh_dict.value = kh_testpaper.type_id')
                ->join('JOIN kh_college ON kh_college.id = kh_testpaper.college_id')
                ->where($map)
                ->limit($limit)->select();
        }else {
            $total = 0;
            $list = null;
        }
//        $total = M('testpaper')	//获取符合条件的数据条数
//        ->field('kh_testpaper.id, kh_testpaper.name, kh_dict.label as typename, kh_college.name as collegename, kh_testpaper.full_score, kh_testpaper.pass_score,kh_testpaper.is_use, kh_testpaper.create_by,kh_testpaper.create_date')
//            ->join('JOIN kh_dict ON kh_dict.value = kh_testpaper.type_id')
//            ->join('JOIN kh_college ON kh_college.id = kh_testpaper.college_id')
//            ->where($map)->count();
//        p($total);die;
//        $list = M('testpaper')
//            ->field('kh_testpaper.id, kh_testpaper.name, kh_dict.label as typename, kh_college.name as collegename, kh_testpaper.full_score, kh_testpaper.pass_score,kh_testpaper.is_use,kh_testpaper.create_by,kh_testpaper.create_date')
//            ->join('JOIN kh_dict ON kh_dict.value = kh_testpaper.type_id')
//            ->join('JOIN kh_college ON kh_college.id = kh_testpaper.college_id')
//            ->where($map)
//            ->limit($limit)->select();

        //查找题量字段
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

    public function getCoursePaper($paperId, $requestPage, $rows) {
        $map['kh_paper_courserclass.del_flag'] = array('eq', '1');
        $nowTime = date('Y-m-d H:i:s');         //获取系统时间
        $map['kh_paper_courserclass.start_time'] = array('elt', $nowTime);      //试卷开始时间小于当前时间，代表考试开始
        $map['kh_paper_courserclass.testpaper_id'] = array('eq', $paperId);
        $limit = ($requestPage-1)*$rows.','.$rows;
        //获取满足条件的数目
        $total  = M('paper_courserclass')->field('testpaper_id,courserclass_id,kh_paper_courserclass.start_time,kh_paper_courserclass.end_time,kh_courseclass.name as coursename,kh_courseclass.teacher_id,kh_sysuser.name')
            ->join('JOIN kh_courseclass ON kh_courseclass.id = kh_paper_courserclass.courserclass_id')
            ->join('JOIN kh_sysuser ON kh_sysuser.id = kh_courseclass.teacher_id')
            ->where($map)->count();
        $testPaperInfo = M('paper_courserclass')->field('testpaper_id,courserclass_id,kh_paper_courserclass.start_time,kh_paper_courserclass.end_time,kh_courseclass.name as coursename,kh_courseclass.teacher_id,kh_sysuser.name')
            ->join('JOIN kh_courseclass ON kh_courseclass.id = kh_paper_courserclass.courserclass_id')
            ->join('JOIN kh_sysuser ON kh_sysuser.id = kh_courseclass.teacher_id')
            ->where($map)->limit($limit)->select();
        foreach ($testPaperInfo as &$t) {
            $courseclass_id = $t['courserclass_id'];        //获取行课班级id
            $stuCount = M('class_student')->where(array('courseclass_id' => $courseclass_id))->count();        //该行课班级的总人数
            $t['totalstu'] = $stuCount;
            if(strtotime($nowTime) < strtotime($t['end_time'])) {
                $t['factstu'] = '考试暂未结束';
            }else{
                $t['factstu'] = $this->countFactStu($t['courserclass_id'], $t['testpaper_id']);     //获取实际人数
            }
        }
        //p($testPaperInfo);die;
        $pages = 0;
        if ($total%$rows == 0){
            $pages = $total/$rows;
        }else{
            $pages = intval($total/$rows + 1);
        }
        $data = array(
            "pages" => $pages,
            "list" => $testPaperInfo
        );

        return $data;



    }

    /**
     * @Function: 在开始结束后统计实际的考试人数
     * @Author: xuanhao
     * @DateTime: ${DATE} ${TIME}
     * * @param $courseclass_id
     */
    public function countFactStu($courseclass_id, $testpaper_id) {
        $map['kh_score.del_flag'] = array('eq', '1');
        $map['kh_score.testpaper_id'] = array('eq', $testpaper_id);
        $stuAccounts = M('Score')->field('account')->where($map)->select();  //查找出已经测试过该试卷的学生账号
        $stuAccountsT = array_column($stuAccounts, 'account');
        $shouldAccounts = M('class_student')->field('account')->where(array('courseclass_id'=>$courseclass_id))->select();       //本来这个班该有的学生
        $shouldAccountsT = array_column($shouldAccounts, 'account');
        $temp = array_intersect($stuAccountsT, $shouldAccountsT);                    //计算交集
        //p($temp);die;
        $factStu = count($temp);
        return $factStu;
    }

    /**
     * @Function: 获取所有的考试 已经结束的班级
     * @Author: xuanhao
     * @DateTime: ${DATE} ${TIME}
     *
     */
    public function getScorePaper($college_id, $type_id, $keyword, $rows, $requestPage) {
        $map['kh_dict.type'] = 'testType';

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
        //系统管理员状态
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
        $paperIds = $this->judgeTimeEnd();
        $map['kh_testpaper.id'] = array('in', $paperIds);
        $limit = ($requestPage-1)*$rows.','.$rows;
        if(!empty($paperIds)) {
            $total = M('testpaper')	//获取符合条件的数据条数
            ->field('kh_testpaper.id, kh_testpaper.name, kh_dict.label as typename, kh_college.name as collegename, kh_testpaper.full_score, kh_testpaper.pass_score,kh_testpaper.is_use,kh_testpaper.create_by,kh_testpaper.create_date,kh_paper_courserclass.courserclass_id,kh_courseclass.name as coursename')
                ->join('JOIN kh_dict ON kh_dict.value = kh_testpaper.type_id')
                ->join('JOIN kh_college ON kh_college.id = kh_testpaper.college_id')
                ->join('JOIN kh_paper_courserclass ON kh_paper_courserclass.testpaper_id = kh_testpaper.id')
                ->join('JOIN kh_courseclass ON kh_courseclass.id = kh_paper_courserclass.courserclass_id')
                ->where($map)->count();

            $list = M('testpaper')
                ->field('kh_testpaper.id, kh_testpaper.name, kh_dict.label as typename, kh_college.name as collegename, kh_testpaper.full_score, kh_testpaper.pass_score,kh_testpaper.is_use,kh_testpaper.create_by,kh_testpaper.create_date,kh_paper_courserclass.courserclass_id,kh_paper_courserclass.start_time,kh_paper_courserclass.end_time,kh_courseclass.name as coursename')
                ->join('JOIN kh_dict ON kh_dict.value = kh_testpaper.type_id')
                ->join('JOIN kh_college ON kh_college.id = kh_testpaper.college_id')
                ->join('JOIN kh_paper_courserclass ON kh_paper_courserclass.testpaper_id = kh_testpaper.id')
                ->join('JOIN kh_courseclass ON kh_courseclass.id = kh_paper_courserclass.courserclass_id')
                ->where($map)
                ->limit($limit)->order('id desc')->select();
            //获取参考人数
            foreach ($list as &$t) {
                //$courseclass_id = $t['courserclass_id'];        //获取行课班级id
                $t['factstu'] = $this->countFactStu($t['courserclass_id'], $t['id']);     //获取实际人数
                $t['testtime'] = $t['start_time'].' -- '.$t['end_time'];        //拼接时间
            }
        }else {
            $total = 0;
            $list = null;
        }
//        $total = M('testpaper')	//获取符合条件的数据条数
//            ->field('kh_testpaper.id, kh_testpaper.name, kh_dict.label as typename, kh_college.name as collegename, kh_testpaper.full_score, kh_testpaper.pass_score,kh_testpaper.is_use,kh_testpaper.create_by,kh_testpaper.create_date,kh_paper_courserclass.courserclass_id,kh_courseclass.name as coursename')
//            ->join('JOIN kh_dict ON kh_dict.value = kh_testpaper.type_id')
//            ->join('JOIN kh_college ON kh_college.id = kh_testpaper.college_id')
//            ->join('JOIN kh_paper_courserclass ON kh_paper_courserclass.testpaper_id = kh_testpaper.id')
//            ->join('JOIN kh_courseclass ON kh_courseclass.id = kh_paper_courserclass.courserclass_id')
//            ->where($map)->count();
//
//        $list = M('testpaper')
//            ->field('kh_testpaper.id, kh_testpaper.name, kh_dict.label as typename, kh_college.name as collegename, kh_testpaper.full_score, kh_testpaper.pass_score,kh_testpaper.is_use,kh_testpaper.create_by,kh_testpaper.create_date,kh_paper_courserclass.courserclass_id,kh_paper_courserclass.start_time,kh_paper_courserclass.end_time,kh_courseclass.name as coursename')
//            ->join('JOIN kh_dict ON kh_dict.value = kh_testpaper.type_id')
//            ->join('JOIN kh_college ON kh_college.id = kh_testpaper.college_id')
//            ->join('JOIN kh_paper_courserclass ON kh_paper_courserclass.testpaper_id = kh_testpaper.id')
//            ->join('JOIN kh_courseclass ON kh_courseclass.id = kh_paper_courserclass.courserclass_id')
//            ->where($map)
//            ->limit($limit)->order('id desc')->select();
//        //获取参考人数
//        foreach ($list as &$t) {
//            //$courseclass_id = $t['courserclass_id'];        //获取行课班级id
//            $t['factstu'] = $this->countFactStu($t['courserclass_id'], $t['id']);     //获取实际人数
//            $t['testtime'] = $t['start_time'].' -- '.$t['end_time'];        //拼接时间
//        }

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
     * @Function: 分析试卷的大概
     * @Author: xuanhao
     * @DateTime: ${DATE} ${TIME}
     *
     */
    public function generalPaper($paperId, $courseclass_id, $keyword, $requestPage, $score_min, $score_max) {
        //获取试卷基本信息
        $thePaper = M('testpaper')->where(array('del_flag' => '1','id' => $paperId))->find();
        $rows = 8;
        $scoreStage = $this->getScoreStage($thePaper, $courseclass_id, $keyword, $requestPage, $rows, $score_min, $score_max);
        return $scoreStage;

    }

    /**
     * @Function: 计算分析成绩的信息，包括分数段，平均成绩 等等
     * @Author: xuanhao
     * @DateTime: ${DATE} ${TIME}
     * * @param $thePaper
     * @param $courseclass_id
     * @return array
     */
    public function getScoreStage($thePaper, $courseclass_id, $keyword, $requestPage, $rows, $score_min, $score_max) {
        //查找出该班的所有学生
//        $students_temp = M('class_student')->field('account')->where(array('courseclass_id'=>$courseclass_id))->select();
        $students_temp = M('class_student')->field('account')->where(array('courseclass_id'=>array('in',$courseclass_id)))->select();
        $students = array_column($students_temp, 'account');        //转化一维数组
        $map['kh_score.del_flag'] = array('eq', '1');
        $map['kh_score.account'] = array('in', $students);
        $map['kh_score.testpaper_id'] = array('eq', $thePaper['id']);
        if ($keyword != ''){
            $map['kh_score.score|kh_score.account|kh_student.name'] = array('like', '%'.$keyword.'%');
        }
        //当分数上限或下限其中一个不为空时，进入判断
        if($score_min != '' || $score_max != '') {
            if($score_min != '' && $score_max == '') {  //大于等于下限
                $map['kh_score.score'] = array('egt',$score_min);
            }
            if($score_min == '' && $score_max != '') {  //小于等于上限
                $map['kh_score.score'] = array('elt',$score_max);
            }
            if($score_min != '' && $score_max != '') {      //之间
                $map['kh_score.score'] = array('between', array($score_min, $score_max));
            }
        }
        if(empty($students)) {
            $data['pages'] = '';
            $data['scoreList'] = '';
        }else {
            $limit = ($requestPage-1)*$rows.','.$rows;
            $total = M('score')->field('kh_score.account,testpaper_id,score,kh_student.name,kh_student.sex,kh_class.name as classname')
                ->join('kh_student ON kh_student.account = kh_score.account')
                ->join('kh_class On kh_class.id = kh_student.class_id')
                ->where($map)->count();
            $scoreList = M('score')->field('kh_score.account,testpaper_id,score,kh_student.name,kh_student.sex,kh_class.name as classname')
                ->join('kh_student ON kh_student.account = kh_score.account')
                ->join('kh_class On kh_class.id = kh_student.class_id')
                ->where($map)->limit($limit)->order('score desc')->select();
            $pages = 0;
            if ($total%$rows == 0){
                $pages = $total/$rows;
            }else{
                $pages = intval($total/$rows + 1);
            }
            //图表数据项
            $data = $this->getData($thePaper, $courseclass_id, $students);
            $temp = $data['list'];
            //因为这里的列表数目条数只有分页限制的条数，故不能用来标记名次，so对下面返回结果进行循环匹配名次
            foreach ($scoreList as &$v) {
                foreach ($temp as $vv) {
                    if($v['account'] == $vv['account']) {
                        $v['rank'] = $vv['rank'];
                    }
                }
            }
            $data['pages'] = $pages;
            $data['scoreList'] = $scoreList;
        }

        return $data;


    }

    /**
     * @Function: 对图表信息进行合理分析返回结果集
     * @Author: xuanhao
     * @DateTime: ${DATE} ${TIME}
     * * @param $thePaper
     * @param $courseclass_id
     * @param $students
     * @return array
     */
    public function getData($thePaper, $courseclass_id,$students) {
        $map['kh_score.del_flag'] = array('eq', '1');
        $map['kh_score.account'] = array('in', $students);
        $map['kh_score.testpaper_id'] = array('eq', $thePaper['id']);
        //直接按成绩排序
        $scoreList = M('score')->field('kh_score.account,testpaper_id,score,kh_student.name,kh_student.sex,kh_class.name as classname')
            ->join('kh_student ON kh_student.account = kh_score.account')
            ->join('kh_class On kh_class.id = kh_student.class_id')
            ->where($map)->order('score desc')->select();
        //p($score);die;
        $fenshuduan = array(0,0,0,0,0,0,0);
        $totalScore = 0;
        $i = 1;
        foreach ($scoreList as &$s) {
            $tempS = $s['score']/10; //统计分数段
            switch (intval($tempS)) {   //取整
                case 10:
                case 9:$fenshuduan[6]++;break;
                case 8:$fenshuduan[5]++;break;
                case 7:$fenshuduan[4]++;break;
                case 6:$fenshuduan[3]++;break;
                case 5:$fenshuduan[2]++;break;
                case 4:$fenshuduan[1]++;break;
                default: $fenshuduan[0]++;break;
            }
            $totalScore += $s['score'];     //计算总分
            $s['rank'] = $i;
            $i++;
        }
        //平均成绩应该是：参考的总成绩/参考的总人数
        $passCount = $fenshuduan[3]+$fenshuduan[4]+$fenshuduan[5]+$fenshuduan[6];   //及格人数
        $notPassCount = $fenshuduan[0]+$fenshuduan[1]+$fenshuduan[2];               //不及格人数
        $tempS2 = $passCount + $notPassCount;
        $averageScore = $totalScore/$tempS2;       //平均成绩
        //$tempS2 = count($students);
        $passRate = round(($passCount/$tempS2)*100,2).'%';  //计算及格率
        //封装图表显示的信息
        $data = array(
            'score_segment' => implode(',', $fenshuduan),          //转化为字符串
            'averageScore' => sprintf("%.2f",$averageScore),    //保留2位小数
            'passCount' => $passCount,
            'notPassCount' => $notPassCount,
            'passRate' => $passRate,
            'full_score' => $thePaper['full_score'],
            'pass_score' => $thePaper['pass_score'],
            'paperName' => $thePaper['name'],
            'list' => $scoreList
        );
        return $data;
    }

    /**
     * @Function: 获取要导出的数据项（按行课班级的查询和搜索多个班级查询的导出）
     * @Author: xuanhao
     * @DateTime: ${DATE} ${TIME}
     * * @param $paperId    试卷id
     * @param $courseclass_id 行课班级
     * @return array
     */
    public function exportData($paperId, $courseclass_id) {
        $thePaper = M('testpaper')->where(array('del_flag' => '1','id' => $paperId))->find();
        $students_temp = M('class_student')->field('account')->where(array('courseclass_id'=>array('in',$courseclass_id)))->select();
        $students = array_column($students_temp, 'account');
        $map['kh_score.del_flag'] = array('eq', '1');
        $map['kh_score.account'] = array('in', $students);
        $map['kh_score.testpaper_id'] = array('eq', $thePaper['id']);
        $scoreList = M('score')->field('kh_score.account,kh_student.name,kh_student.sex,score,kh_class.name as classname')
            ->join('kh_student ON kh_student.account = kh_score.account')
            ->join('kh_class On kh_class.id = kh_student.class_id')
            ->where($map)->order('account asc')->select();
        //转化性别
        foreach ($scoreList as &$s) {
            if($s['sex'] == 0) {
                $s['sex'] = '女';
            }else if($s['sex'] == 1) {
                $s['sex'] = '男';
            }
        }
        $data = array(
            'paperName' => $thePaper['name'],
            'scoreList' => $scoreList
        );

        return $data;

    }

//    public function getSomeTotalData() {
//        $map1['score'] = array('egt', 60);
//        $scorePass = M('score')->where($map1)->count();  //大于及格分数60
//        $map2['score'] = array('lt', 60);
//        $scoreNotPass = M('score')->where($map2)->count();      //没有及格的
//        //$temp = M('score')->field('score,account')->select();
//        $original = M();	//实例化空模型
//        //求出平均分最高的
//        $sql = "SELECT MAX(s1) smax, account FROM (SELECT account,score, AVG(score) s1 FROM kh_score GROUP BY account)t1";
//        $temp1 = $original->query($sql);
//        $sql1 = "SELECT testpaper_id,MAX(s1) smax FROM (SELECT testpaper_id,score, AVG(score) s1 FROM kh_score GROUP BY testpaper_id)t1 WHERE testpaper_id = t1.testpaper_id";
//        //$sql1 = "SELECT testpaper_id,score FROM kh_score GROUP BY testpaper_id";
//        $temp2 = $original->query($sql1);
//
//        //p($temp2);die;
//        //p($temp);die;
////        $total = $scorePass + $scoreNotPass;
////        $passRate = round(($scorePass/$total)*100,2).'%';  //计算及格率
//    }

    /**
     * @Function: 根据传来的关键词搜索
     * @Author: xuanhao
     * @DateTime: ${DATE} ${TIME}
     * * @param $keyword
     */
    public function getStudentScoreInfo($keyword) {
        $map['kh_score.del_flag'] = array('eq', '1');
        //$map['kh_score.account|kh_student.name'] = array('like', '%'.$keyword.'%');
        $map['kh_student.account'] = array('eq',$keyword);
        $data = M('score')->field('score,kh_score.account,kh_score.testpaper_id,kh_student.name as stuName,kh_student.id as stuid,kh_testpaper.name as paperName,kh_class.name as classname,kh_score.create_date')
            ->join('kh_student ON kh_student.account=kh_score.account')
            ->join('kh_class ON kh_class.id=kh_student.class_id')
            ->join('kh_testpaper ON kh_testpaper.id=kh_score.testpaper_id')
            ->where($map)->select();

        return $data;
    }

    /**
     * @Function: 获取所有的行课班级列表
     * @Author: xuanhao
     * @DateTime: ${DATE} ${TIME}
     *
     */
    public function getClassList($requestPage, $keyword, $rows,$grade_id, $college_id) {
        $account=session('account');
        $map2['account']=array('eq',$account);
        $temp=M('sysuser')->field('role,dept_id')->where($map2)->find();
        if($temp['role']!=1){
            $map['a.college_id'] = array('eq' , $temp['dept_id']);
        }
        //老师角色时，只可以查看自己所教的班级
        if($temp['role'] == 4) {
            $accountInfo = session('accInfo');
            $map['a.teacher_id'] = $accountInfo['id'];      //获取老师的id
        }
        $map['a.del_flag'] = array('eq', '1');
        if ($keyword != ''){
            $map['a.id|a.name|kh_sysuser.name|kh_lession.name'] = array('like', '%'.$keyword.'%');
        }
        if ($grade_id != ''){
            $map['a.grade_id'] = array('eq',$grade_id);
        }
        if ($college_id != ''){
            $map['a.college_id'] = array('eq', $college_id);
        }

        $limit = ($requestPage-1)*$rows.','.$rows;
        //统计符合条件的数量
        $total = M('courseclass as a')->join('LEFT join kh_college ON kh_college.id = a.college_id
				LEFT join kh_grade ON kh_grade.id = a.grade_id 
				LEFT join kh_sysuser ON kh_sysuser.id = a.teacher_id
				LEFT join kh_lession ON kh_lession.id = a.lession_id
			')->where($map)->count();		//获取全部符合条件的数据条数

        $list = M('courseclass as a')
            ->join('LEFT join kh_college ON kh_college.id = a.college_id
				LEFT join kh_grade ON kh_grade.id = a.grade_id 
				LEFT join kh_sysuser ON kh_sysuser.id = a.teacher_id
				LEFT join kh_lession ON kh_lession.id = a.lession_id
			')
            ->field('a.id,
				 a.name,a.start_time,a.end_time,
		 		 kh_college.name as college_name,
		 	     kh_grade.name as grade_name,
		 	     kh_sysuser.name as teacher_name,
		 	     kh_lession.name as lession_name
		 	     ')
            ->where($map)
            ->order('college_name asc')
            ->limit($limit)->select();

        $pages = 0;
        if ($total%$rows == 0){
            $pages = $total/$rows;
        }else{
            $pages = intval($total/$rows + 1);
        }
        $data = array(
            "pages" => $pages,
            "list" => $list,
        );
        return $data;
    }


    /**
     * @Function:  获取选取行课班级的所有考试试卷
     * @Author: xuanhao
     * @DateTime: ${DATE} ${TIME}
     * * @param $id
     * @param $keyword
     * @param $requestPage
     */
    public function getClassPaper($id, $keyword, $requestPage) {
        $map['a.del_flag'] = array('eq', '1');
        $map['a.courserclass_id'] = array('eq', $id);
        $map['kh_dict.type'] = 'testType';  //对数据字典中的类型指定为测试类型
        $rows = 8;
        if ($keyword != ''){
            $map['a.id|kh_testpaper.name|kh_dict.label'] = array('like', '%'.$keyword.'%');
        }
        $limit = ($requestPage-1)*$rows.','.$rows;
        //获取数量
        $total = M('paper_courserclass as a')->join('
            LEFT join kh_testpaper ON kh_testpaper.id = a.testpaper_id 
            LEFT join kh_dict ON kh_dict.value = kh_testpaper.type_id 
            LEFT join kh_college ON kh_college.id = kh_testpaper.college_id 
        ')->where($map)->count();
        //获取数据
        $list = M('paper_courserclass as a')->join('
            LEFT join kh_testpaper ON kh_testpaper.id = a.testpaper_id 
            LEFT join kh_dict ON kh_dict.value = kh_testpaper.type_id 
            LEFT join kh_college ON kh_college.id = kh_testpaper.college_id 
        ')->where($map)->field('a.start_time, a.end_time, a.comment,
        kh_testpaper.id as paper_id,kh_testpaper.name as paper_name,kh_testpaper.create_by as paper_create, kh_dict.label as examType, kh_college.name as college_name')->limit($limit)->select();

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
     * @Function:
     * @Author: xuanhao
     * @DateTime: ${DATE} ${TIME}
     * * @param $id 试卷id  $courseclassId行课班级id
     */
    public function getPaperKnowledge($id, $courseclassId) {
        $testPaperQuesNum = D('Testpaper')->getPaperQuestionNum($id);//获取题目数量


        //获取该试卷中各类题目中各种难度的数量：choiceEasyNum（选择题简单）choiceNormalNum选择题中等，choiceHardNum（选择题困难）
        $choiceEasyNum = D('PaperDetial')->getQuestionNum($id, 1, 1);//参数:试卷id，题目类型，题目难度
        $choiceNormalNum = D('PaperDetial')->getQuestionNum($id, 1, 2);
        $choiceHardNum = D('PaperDetial')->getQuestionNum($id, 1, 3);

        $TrueOrFalseEasyNum = D('PaperDetial')->getQuestionNum($id, 2, 1);
        $TrueOrFalseNormalNum = D('PaperDetial')->getQuestionNum($id, 2, 2);
        $TrueOrFalseHardNum = D('PaperDetial')->getQuestionNum($id, 2, 3);

        $fillBlankEasyNum = D('PaperDetial')->getQuestionNum($id, 3, 1);
        $fillBlankNormalNum = D('PaperDetial')->getQuestionNum($id, 3, 2);
        $fillBlankHardNum = D('PaperDetial')->getQuestionNum($id, 3, 3);

        $programeEasyNum = D('PaperDetial')->getQuestionNum($id, 4, 1);
        $programeNormalNum = D('PaperDetial')->getQuestionNum($id, 4, 2);
        $programeHardNum = D('PaperDetial')->getQuestionNum($id, 4, 3);

        //将以上的数据组成数组，方便前台展示
        $easyNumArray = array($choiceEasyNum, $TrueOrFalseEasyNum, $fillBlankEasyNum, $programeEasyNum);
        $normalNumArray = array($choiceNormalNum, $TrueOrFalseNormalNum, $fillBlankNormalNum, $programeNormalNum);
        $hardNumArray = array($choiceHardNum, $TrueOrFalseHardNum, $fillBlankHardNum, $programeHardNum);
        /*
         * 该行课班级下的所有学生的错误知识点罗列
        */
        //查找出该班的所有学生
        $students_temp = M('class_student')->field('account')->where(array('courseclass_id'=>array('in', $courseclassId)))->select();
        $students = array_column($students_temp, 'account');        //转化一维数组
        $map['kh_student.account'] = array('in', $students);
        //查询学生在数据表中的逻辑id
        $students_id_temp = M('student')->field('id')->where($map)->select();
        $students_id = array_column($students_id_temp, 'id');
        $knowledgeData = $this->theFalseKnowledge($id, $students_id);
        $data = array(
            'testPaperQuesNum' => $testPaperQuesNum,
            'easyNumArray' => $easyNumArray,
            'normalNumArray' => $normalNumArray,
            'hardNumArray' => $hardNumArray,
            'knowledgeData' => $knowledgeData
        );
        return $data;
    }

    /**
     * @Function: 错误的知识点合计
     * @Author: xuanhao
     * @DateTime: ${DATE} ${TIME}
     * * @param $paperId 试卷的id
     * @param $students 学生的id集
     */
    public function theFalseKnowledge($paperId, $students) {
        $map = array(
            'testpaper_id' => $paperId,
            'is_true' => 0,
            'student_id' => array('in', $students)
        );
        //查询改班级学生的本张试卷的错误的题目知识点详情
        $question_ids_temp = M('test_final')->field('question_id, count(question_id) as false_count')->where($map)->group('question_id')->select();
        foreach ($question_ids_temp as &$value1) {
            //获取该试题题目以及知识点信息
            $knowledge_info = M('question')->field('content,type,level')->where(array('id'=>$value1['question_id']))->find();
            //获取知识点的id集合
            $knowledges_ids_array = M('question_know')->field('knowledge_id')->where(array('question_id'=>$value1['question_id']))->select();
            //将二维数组按knowledge_id转化为一维的数组
            $knowledges_ids_array_one = array_column($knowledges_ids_array, 'knowledge_id');
            //将一维的数组转化为字符串
            $knowledges_ids_array_string = implode(',', $knowledges_ids_array_one);
            //压入字段
            $knowledge_info['knowledge_ids'] = $knowledges_ids_array_string;
            $value1['knowledge_info'] = $knowledge_info;
        }
        unset($value1);
        //获取该试题题目以及知识点信息
        foreach ($question_ids_temp as &$value2) {
//            foreach ($value2['knowledge_ids'] as &$value3) {
                $knowledge_ids_array = explode(',', $value2['knowledge_info']['knowledge_ids']);  //知识点id转为一维数组
                $knowledges = M('knowledge')->field('name')->where(array('id'=>array('in', $knowledge_ids_array)))->select();
                $value2['knowledges'] = $knowledges;     //将该道题目的知识点名称信息放入数组
//            }

        }
        unset($value);      //销毁变量
        //p($question_ids_temp);die;
        return $question_ids_temp;

    }
    /**
     * @Function: 获取指定行政班级下的学号是学生列表   ????????????????
     * @Author: xuanhao
     * @DateTime: ${DATE} ${TIME}
     * * @param $id
     * @param $keyword
     * @param $requestPage
     * @return array
     */
    public function getClassStudent($id, $keyword,$requestPage) {
        $map['kh_student.del_flag'] = array('eq', '1');
        $map['kh_student.class_id'] = array('eq', $id);
        $rows = 8;
        if ($keyword != ''){
            $map['kh_student.id|kh_student.name|kh_student.account'] = array('like', '%'.$keyword.'%');
        }
        $limit = ($requestPage-1)*$rows.','.$rows;
        //查询符合条件的条数
        $total = M('Student')->where($map)->count();
        $list = M('Student')->where($map)->order('account asc')
            ->limit($limit)->select();
        //p($list);die;
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
     * @Function: 该学生的所有的考试成绩信息
     * @Author: xuanhao
     * @DateTime: ${DATE} ${TIME}
     * * @param $stuId
     * @param $keyword
     * @param $requestPage
     * @return array
     */
    public function getTheStudentAllScore($stuId, $keyword, $requestPage) {
        $map['kh_score.del_flag'] = array('eq', '1');
        $rows = 8;
        if ($keyword != ''){
            $map['kh_student.name|kh_student.account|kh_testpaper.name'] = array('like', '%'.$keyword.'%');
        }
        $limit = ($requestPage-1)*$rows.','.$rows;
        $stuAccount = M('student')->field('account')->where(array('id'=>$stuId))->find();
        $map['kh_score.account'] = array('eq', $stuAccount['account']);
        $total = M('score')
            ->join('kh_student ON kh_student.account=kh_score.account')
            ->join('kh_class ON kh_class.id=kh_student.class_id')
            ->join('kh_testpaper ON kh_testpaper.id=kh_score.testpaper_id')
            ->where($map)->count();
        $list = M('score')->field('score,kh_score.account,kh_score.testpaper_id,kh_student.name as stuname,kh_student.sex,kh_testpaper.name as paperName,kh_class.name as classname,kh_score.create_date')
            ->join('kh_student ON kh_student.account=kh_score.account')
            ->join('kh_class ON kh_class.id=kh_student.class_id')
            ->join('kh_testpaper ON kh_testpaper.id=kh_score.testpaper_id')
            ->where($map)->order('create_date desc')->limit($limit)->select();
        //p($data);die;
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
     * @Function: 获取学生成绩导出数据
     * @Author: xuanhao
     * @DateTime: ${DATE} ${TIME}
     *
     */
    public function exportStudentScore($stuId) {
        $map['kh_score.del_flag'] = array('eq', '1');
        $stuAccount = M('student')->field('account')->where(array('id'=>$stuId))->find();
        $map['kh_score.account'] = array('eq', $stuAccount['account']);
        $list = M('score')->field('kh_score.account,kh_student.name as stuname,kh_student.sex,kh_testpaper.name as paperName,score,kh_class.name as classname,kh_score.create_date')
            ->join('kh_student ON kh_student.account=kh_score.account')
            ->join('kh_class ON kh_class.id=kh_student.class_id')
            ->join('kh_testpaper ON kh_testpaper.id=kh_score.testpaper_id')
            ->where($map)->order('create_date desc')->select();
        //转化性别
        foreach ($list as &$s) {
            if($s['sex'] == 0) {
                $s['sex'] = '女';
            }else if($s['sex'] == 1) {
                $s['sex'] = '男';
            }
        }
        return $list;
    }

}