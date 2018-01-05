<?php
namespace Home\Controller;
use Common\Controller\BaseController;

class AutoCreatePaperMgrController extends BaseController{

    /**
     * @function 展示自动生成页面
     * @Author   许加明
     * @DateTime 2017-6-25 16:00:23
     * @param    null
     * @return   null
     */
    public function showAutoPaper(){
        $paper_id = I('testpaper_id','');
        $college_id = I('college_id','');
        $lession_id = I('lession_id','');

        // 从题库权限表中获取拥有该题库权限的数据
        $map['del_flag'] = array('eq', '1');
        $map['college_id'] = array('eq', $college_id);
        $testid = M('Testdatabase_permission')
        ->field('testdb_id')
        ->where($map)->select();

        // p($testid);die();
        // 获取有操作该题库权限的图库表的题库集
        $testids=array_column($testid,'testdb_id');
        // p($testids);die();
        $map3['id']=array('in',$testids);
        $map3['del_flag']=array('eq','1');
        $map4['lession_id']=array('eq',$lession_id);
        $dataDB=M('Testdatabase')->field('id,name')->where($map3,$map4,'or')->select();
        // p($lession_id);die();
        // $lessionList = getlession($college_id);
        // p($dataDB);die();
        $this->assign('DbList', $dataDB);
        $this->assign('paper_id', $paper_id);
        $this->assign('college_id', $college_id);

        $this->display('AutoCreatePaper');
    }

    /**
     * @function 获取题库剩余题量
     * @Author   许加明
     * @DateTime 2017-7-22 22:34:33
     * @param    null
     * @return   json
     */
    public function getResidueSubjectNum(){
        if(!IS_POST){
            return;
        }
        $testDbIds = I("ids","");
        $chapterIds = I("chs","");
        if($testDbIds == "" || $testDbIds == null){
            $msg = array('success'=>false,'msg'=>'请选择至少一个题库库！');
            $this->ajaxReturn($msg,'json');
        }

        $result = $this->getNotEqualMatrix($testDbIds,$chapterIds);

        $out = $result['arrCount'];

        $msg = array('success'=>true,'msg'=>$out);

        $this->ajaxReturn($msg,'json');

    }

    /**
     * @function 获得题库列表和章节列表
     * @Author   许加明
     * @DateTime 2017-6-25 16:01:31
     * @param    $course_id
     * @return   json
     */
    public function getList(){
        if(!IS_POST){
            $this->ajaxReturn('','json');
        }
        $database_id = I('database_id');
        $college_id=I('college_id');
        // p($college_id);die();
        // p($data3);die();
        
        //根据题库id获取知识点，再由知识点获取到章节以供筛选
        $map4['del_flag']=array('eq','1');
        $map4['testdb_id']=array('eq',$database_id);
        $question=M('question')->field('id')->where($map4)->select();
        $questionids=array_column($question,'id');
        //获取知识点id
        $map5['question_id']=array('in',$questionids);
        $knowlege=M('question_know')->field('knowledge_id')->where($map5)->select();
        $knowlegeids=array_column($knowlege,'knowledge_id');
        //获取章节id
        $map6['del_flag']=array('eq','1');
        $map6['id']=array('in',$knowlegeids);
        $Chapter=M('knowledge')->field('chapter_id')->where($map6)->select();
        $Chapterids=array_column($Chapter,'chapter_id');
        
        // p($Chapter);die();

        // $lessionids=array_column($data,'lession_id');
        // p($lessionids);die();
        
        //根据章节id获取章节信息
        $map3['del_flag']=array('eq','1');
        $map3['id']=array('in',$Chapterids);
        $data2 = D('Chapter')->where($map3)->select();
        // p($data2);die();

        $list = array('dbs'=>$data2);
        // p($list);die();
        $this->ajaxReturn($list,'json');
    }

    
    /**
     * @function 自动生成试题方法
     * @Author   许加明
     * @DateTime 2017-7-27 22:04:57
     * @param    null 
     * @return   json   
     */
    //todo: 待验证
    public function autoCreatePaper(){
        $testNum = I('testNum');
        $testA = I('testA');
        $testB = I('testB');
        $testC = I('testC');
        $testVal = I('testVal');
        $dataBaseId = I('database_id');
        $chapterId = I('chapter_id');
        $paperId = I('paper_id');
        // p($dataBaseId);die();
        //对用户选择的题量进行矩阵转置
        $numMatrix = array();
        for($i = 0;$i < 4; $i++){
            $numMatrix[$i][0] = $testA[$i];
            $numMatrix[$i][1] = $testB[$i];
            $numMatrix[$i][2] = $testC[$i];
        }

        //判断是否是有高级选项

        //只有总题量的情况
        $questionList = array();    //查询结果
        $result = array();          //统计数据
        $msg = array();             //记录返回消息

        if($this->checkArrayIsAllZero($testA)&&$this->checkArrayIsAllZero($testB)&&$this->checkArrayIsAllZero($testC)){
            $result = $this->getNotEqualMatrix($dataBaseId,$chapterId);
            $list = $result['arrList'];     //二维数组
            $dataBaseCount = array(array(),array(),array(),array());       //一维数组
            for($i=0;$i<4;$i++){            //二维转一维
                array_push($dataBaseCount[$i],array_merge($list[$i][0],$list[$i][1],$list[$i][2]));
            }
//            p($dataBaseCount);
            $questionList = $this->getRadomElementBuildOnBase($dataBaseCount,$testNum);

        }else{  //有高级选项的情况
            $result = $this->getNotEqualMatrix($dataBaseId,$chapterId);
            $questionList = $this->getRadomElementBuildOnHight($result['arrList'],$numMatrix);
        }

        //添加入库
        if(count($questionList)>0){
            $paperQuestion = M('PaperQuestion');
            $addAllOk = true;               //判断是否全部添加
            $paperQuestion->startTrans();   //开启事物
            $paperVal = 0;
            //循环添加
            foreach ($questionList as $value){
                $type = M('Question')->where(array('id'=>$value))->find();
                $questionVal = '';
                switch (intval($type['type'])){
                    case 1:{
                        $questionVal = $testVal[0];
                    }break;
                    case 2:{
                        $questionVal = $testVal[1];
                    }break;
                    case 3:{
                        $questionVal = $testVal[2];
                    }break;
                    case 4:{
                        $questionVal = $testVal[3];
                    }break;
//                    p("------------".$questionVal."----------");
                }
                $data['testpaper_id'] = $paperId;
                $data['question_id'] = $value;
                $data['value'] = $questionVal;
                $data['comment'] = "自动生成添加";

                $paperVal+=$questionVal;

                if($id = M('PaperQuestion')->add($data)){
                    $msg['success'] = true;
                    $msg['msg'] = "自动生成成功！";
                }else{
                    $paperQuestion->rollback();     //事物回滚
                    $addAllOk = false;
                    $msg['success'] = false;
                    $msg['msg'] = "自动生成失败！部分插入失败";
                }
            }
            //判断是否添加成功，提交事物
            if($addAllOk){
                $data2['id'] = $paperId;
                $data2['full_score'] = $paperVal;
                $data2['pass_score'] = $paperVal*0.6;
                $data2['update_by'] = session('account');
                $data2['update_date'] = date('Y-m-d H:i:s', time());
                $data2['comment'] = '自动生成更新基础分值';
                if(!$id = D('Testpaper')->save($data2)){
                    $paperQuestion->rollback();
                }
                $paperQuestion->commit();
            }
        }else{
            $msg['success'] = false;
            $msg['msg'] = "自动生成失败！";
        }
        $this->ajaxReturn($msg,'json');
    }

    /**
     * @function 检查数组是否全零
     * @Author   许加明
     * @DateTime 2017-7-27 01:20:27
     * @param    array
     * @return   boolean
     */
    private function checkArrayIsAllZero($arr){
        $flag = true;
        foreach ($arr as $val){
            if($val != 0){
                $flag = false;
                return $flag;
            }
        }
        return $flag;
    }

    /**
     * @function 一般生成试卷方法
     * @Author   许加明
     * @DateTime 2017-7-27 23:29:59
     * @param    $matrix 题库总量数组 $numMaxtrix 用户选择题量数组
     * @return   array
     */
    private function getRadomElementBuildOnBase($matrix,$numMaxtrix){
        //根据题库总量矩阵得到各类型题目题号
        $result = array();
        for($i = 0; $i < 4; $i++){
            if ($numMaxtrix[$i] > 0 && count($matrix[$i][0]) > 0){
                if($numMaxtrix[$i] <= count($matrix[$i][0])){
                    shuffle($matrix[$i][0]);
                    $result = array_merge($result,array_slice($matrix[$i][0],0,$numMaxtrix[$i]));
                }else{
                    return false;
                }
            }
        }
        return $result;
    }

    /**
     * @function 高级生成试卷方法
     * @Author   许加明
     * @DateTime 2017-7-27 15:42:41
     * @param    $matrix 题库总量矩阵 $numMaxtrix 用户选择题量矩阵
     * @return   array
     */
    private function getRadomElementBuildOnHight($matrix,$numMaxtrix){
        //根据题库总量矩阵得到各类型题目题号
        $result = array();
        for($i = 0; $i < 4; $i++){
            for($j = 0;$j < 3; $j++){
                if ($numMaxtrix[$i][$j] > 0 && count($matrix[$i][$j]) > 0){
                    if($numMaxtrix[$i][$j] <= count($matrix[$i][$j])){
                        //todo:
                        shuffle($matrix[$i][$j]);
                        $result = array_merge($result,array_slice($matrix[$i][$j],0,$numMaxtrix[$i][$j]));
                    }else{
                        return false;
                    }
                }

            }
        }
        return $result;
    }

    private function getQuestionListByLevel(){

    }
    /**
     * @function 根据数据库ids和章节ids获得各级别列表矩阵
     * @Author   许加明
     * @DateTime 2017-7-27 02:10:08
     * @param    $testDbIds 数据库id  $chapterIds 章节id 
     * @return   array   
     */
    private function getNotEqualMatrix($testDbIds,$chapterIds){
        //判断是否选择了章节
        if($chapterIds == ""){
            $sqlString = <<< EOT
select DISTINCT kq.id AS question_id from kh_question kq WHERE kq.testdb_id in ($testDbIds);
EOT;
        }else{
            $sqlString = <<< EOT
select DISTINCT kqk.question_id from kh_question_know kqk LEFT JOIN kh_question kq ON kqk.question_id = kq.id,kh_knowledge kkl 
WHERE kkl.chapter_id in ($chapterIds) AND kkl.id = kqk.knowledge_id AND kq.testdb_id in ($testDbIds);
EOT;
        }

        //获取题目列表

        $request = D()->query($sqlString);

        $questionArray = array();
        //转化为题目数组
        foreach ($request as $key => $item){
            array_push($questionArray,$item["question_id"]);
        }

        //各类题型数量矩阵
        $out = array(
            array(0,0,0),
            array(0,0,0),
            array(0,0,0),
            array(0,0,0)
        );
        //各类题型题目列表矩阵
        $void = array();
        $outList  = array(
            array($void,$void,$void),
            array($void,$void,$void),
            array($void,$void,$void),
            array($void,$void,$void)
        );

        foreach($questionArray as $questionId){
            $result = M("Question")->where(array("id"=>$questionId))->find();
            $out[$result['type']-1][$result['level']-1] += 1;
            array_push($outList[$result['type']-1][$result['level']-1],$result['id']);
        }

        return array('arrCount'=>$out,'arrList'=>$outList);
    }

}