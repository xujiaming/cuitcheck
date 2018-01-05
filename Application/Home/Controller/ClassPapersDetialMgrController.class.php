<?php 
namespace Home\Controller;
use Common\Controller\HomeBaseController;

/**
 * 功能：试卷详情查看
 * 修改:luochao
 * @author：maqingwen
 * 
 */
class ClassPapersDetialMgrController extends HomeBaseController{
	/**
	 * 试卷详情页面展示
	 * @param $id
	 */
	public function paperDetial(){

		$id = I('id', '');
        $college_id=I('college_id','');
        $lession_id=I('lession_id','');
        $type_id=I('type_id','');
        // p($type_id);die();
		$testPaper = D('PaperDetial')->getPaperById($id);
		$testPaperQuesNum = D('Testpaper')->getPaperQuestionNum($id);//获取题目数量

		//获取该试卷的全部题目,参数：$id.试卷id; 题目类型
		$choiceList = D('PaperDetial')->getQuestionList($id, 1);
		$trueOrFalseList = D('PaperDetial')->getQuestionList($id, 2);
		$fillBlankList = D('PaperDetial')->getQuestionList($id, 3);
		$programeList = D('PaperDetial')->getQuestionList($id, 4);

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

		$this->assign('testPaper', $testPaper);//试卷信息
		$this->assign('testPaperQuesNum', $testPaperQuesNum);//试题数量
		$this->assign('choiceList', $choiceList);//选择题列表
		$this->assign('trueOrFalseList', $trueOrFalseList);//判断题列表
		$this->assign('fillBlankList', $fillBlankList);//填空题列表
		$this->assign('programeList', $programeList);//编程题列表
		$this->assign('easyNumArray', $easyNumArray);//简单题数组
		$this->assign('normalNumArray', $normalNumArray);//普通题数组
		$this->assign('hardNumArray', $hardNumArray);//困难题数组
        $this->assign('college_id',$college_id);
        $this->assign('lession_id',$lession_id);
		$this->assign('testpaper_id', $id);
        $this->assign('type_id', $type_id);
		$this->display('paperDetial');
		// p($easyNumArray);
	}

	/**
	 * 功能：根据试卷id和题目id获取答案
	 * @param 题目id：question_id
	 * @return JSON
	 */
	public function getAnswer(){

		$question_id = I('question_id', '');

		$data = D('PaperDetial')->getAnsewe($question_id);

		$this->ajaxReturn($data, 'json');
	}

	/**
	 * 修改单个题目分数
	 * @param 题目id question_id，试卷id testpaper_id
	 * @return JSON
	 */
	public function changeValue(){

		$question_id = I('question_id', '');
		$testpaper_id = I('testpaper_id', '');
		$value = I('value', '');

		$data = D('PaperDetial')->changeValue($question_id, $testpaper_id, $value);//修改题目分数以及重新统计总分

		$this->ajaxReturn($data, 'json');
	}

	/**
	 * 删除单个的事件处理
	 * @param 题目id question_id，试卷id testpaper_id
	 * @return JSON
	 */
	public function deleteSingle(){
		$question_id = I('question_id', '');
		$testpaper_id = I('testpaper_id', '');

		$data = D('PaperDetial')->deleteSingle($question_id, $testpaper_id);

		$this->ajaxReturn($data, 'json');
	}

	/**
	 * 初始化添加单个题目的窗口
	 * @param testpaper_id
	 */
	public function editAddSingle(){

		$testpaper_id = I('testpaper_id', '');
        $college_id=I('college_id','');
        $lession_id=I('lession_id','');
        $type_id=I('type_id','');
        // P($type_id);die();
        // 从题库权限表中获取拥有该题库权限的数据
        $map['del_flag'] = array('eq', '1');
        $map['college_id'] = array('eq', $college_id);
        $testid = M('Testdatabase_permission')
        ->field('testdb_id')
        ->where($map)->select();

        // p($testid);die();
        // 获取有操作该题库权限的图库表的题库集
        $testids=array_column($testid,'testdb_id');
        if($type_id==1){
            $map3['type_id'] = array('eq', 1);
        }
        // p($testids);die();
        $map3['id']=array('in',$testids);
        $map3['del_flag']=array('eq','1');
        $map4['lession_id']=array('eq',$lession_id);
        $dataDB=M('Testdatabase')->field('id,name')->where($map3,$map4,'or')->select();
        // p($lession_id);die();
		// $lessionList = getlession($college_id);
        // p($dataDB);die();
		$this->assign('DbList', $dataDB);
		$this->assign('testpaper_id', $testpaper_id);
        $this->assign('college_id', $college_id);

		$this->display('addSingerQuestion');
	}
	/**
     * @function 获得题库列表和章节列表
     * @Author   许加明
     * @DateTime 2017-6-25 16:01:31
     * 修改:luochao
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
     * 通过题库列表和章节列表
     * @param 题库ids和章节chs
     */
    public function queryList(){

    	$testDbIds = I("ids","");
        $testpaper_id = I("testpaper_id", "");
        $chapterIds = I("chs", "");
        // p($chapterIds);die();
        $list = D('PaperDetial')->queryList($testDbIds,$chapterIds,$testpaper_id);
        $courseList = D('Course')->getAllList();

		$this->assign('courseList', $courseList);
        $this->assign('testpaper_id', $testpaper_id);
        $this->assign('questionlist', $list);

        $this->display('addSingerQuestion');
    }

    /**
     * 添加单个题的事件处理
     * @param 题目id和试卷id
     */
    public function addSinger(){

    	$question_id = I('question_id', '');
    	$testpaper_id = I('testpaper_id', '');

    	$data = D('PaperDetial')->addSinger($question_id, $testpaper_id);

    	$this->ajaxReturn($data, 'json');
    }

    /**
     * 正式考试的班级分配处理
     * @param 
     */
    public function showFinalTest(){

    	$data = D('PaperDetial')->getAllFinalTest();

    	if ($data == 0){
    		$this->error("抱歉，当前用户没有权限");
    	}else{
    		$this->assign('finalTestList', $data);
    	}

    	$this->display('finalTest');
    }
    /**
     * 正式试卷分配前判断试卷中是否有试题
     */
    public function judgepaper(){
        $testpaper_id = I('id');   
        $map['testpaper_id']=array('eq', $testpaper_id);
        $judgedata=M('PaperQuestion')->where($map)->select();
        if(sizeof($judgedata)==0){
            $data = array('success'=>false, 'msg'=>'该试卷中没有试题，请先出题哦！');
        }else{
           $data['success']=true;
        }
        // p($judgedata);die();
        $this->ajaxReturn($data, 'json');
    }
    /**
     * 正式考试点击添加班级的事件处理
     */
    public function showAddClass(){

    	$testpaper_id = I('testpaper_id','');
    	$ptime = time();
    	
    	$courseClassList = D('PaperDetial')->getClassByPaper($testpaper_id);

    	$this->assign('testpaper_id', $testpaper_id);
    	$this->assign('courseClassList', $courseClassList);
    	$this->assign('ptime', $ptime);
    	$this->display('addClass');
    }

    /**
     * 点击添加班级的事件处理
     * 
     */
    public function addClassWin(){

    	$testpaper_id = I('testpaper_id','');

    	if(D('PaperDetial')->checkTestUse($testpaper_id)){
    		//获取全部班级列表（id, name）
    		$allClassList = D('PaperDetial')->getAllClassList();
    		//获取正在使用的班级列表(ids)
    		$classList_ids = D('PaperDetial')->getClassList($testpaper_id);
    		//获取正在使用的班级的开始时间和结束时间
    		$data = D('PaperDetial')->getClassByPaper($testpaper_id);
    		if (sizeof($data) != 0){
    			$start_time = $data[0]['start_time'];
    			$end_time = $data[0]['end_time'];
    			$status = '1';	//表示可以添加
    		}else{
    			$status = '2';	//表示新添加
    		}

    	}else{
    		$status = '0';	//表示无法添加
    	}

    	$this->assign('testpaper_id', $testpaper_id);
    	$this->assign('start_time', $start_time);
    	$this->assign('end_time', $end_time);
    	$this->assign('status', $status);
    	$this->assign('allClassList', $allClassList);
    	$this->assign('classList_ids', $classList_ids);

    	$this->display('addClassWin');
    }

    /**
     * 点击保存的事件处理
     */
    public function addClass(){

    	$class_ids = I('class_ids', '');
    	$start_time = I('start_time');
    	$end_time = I('end_time');
    	$testpaper_id = I('testpaper_id', '');
    	
    	if (!D('PaperDetial')->checkTestUse($testpaper_id)){
    		$data = array('success'=>false, 'msg'=>'抱歉，试卷已被使用过，无法再次使用');
    	}else if($class_ids == ''){
    		$data = array('success'=>false, 'msg'=>'请填写数据');
    	}else{
    		$allClass_ids = split(',', $class_ids);
    		$data = D('PaperDetial')->addClass($allClass_ids, $start_time, $end_time, $testpaper_id);
    	}

    	$this->ajaxReturn($data, 'json');
    }

    /**
     * 点击删除的事件处理
     */
    public function deleteClass(){
    	$courserclass_id = I('courserclass_id');
    	$testpaper_id = I('testpaper_id');

    	$data = D('PaperDetial')->deleteClass($courserclass_id, $testpaper_id);

    	$this->ajaxReturn($data, 'json');
    }
}