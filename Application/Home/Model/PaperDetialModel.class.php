<?php 
/**
 * 
 * 试卷详情的的数据模型
 * @author maqingwen
 * @日期 2017/6/25
 */
namespace Home\Model;
use Think\Model;

class PaperDetialModel extends Model{
	protected $tableName = 'testpaper';


	/**
	 * 根据id获取试卷信息
	 * @param $id
	 * @return testPaper
	 */
	public function getPaperById($id){

		$map['del_flag'] = array('eq', '1');
		$map['id'] = array('eq', $id);

		$data = M('Testpaper')
		->field('id, name, full_score, pass_score')
		->where($map)
		->find();

		return $data;
	}

	/**
	 * 计算试卷各类题的数量
	 * @param $testpaper_id:试卷id, $type:题目类型, $level:题目难度
	 * @return num
	 */
	public function getQuestionNum($testpaper_id, $type, $level){

		$map['kh_question.del_flag'] = array('eq', '1');
		$map['kh_question.type'] = array('eq', $type);
		$map['kh_question.level'] = array('eq', $level);
		$map['kh_paper_question.testpaper_id'] = array('eq', $testpaper_id);

		$data = M('PaperQuestion')
		->join('JOIN kh_question ON kh_question.id = kh_paper_question.question_id')
		->where($map)->count();

		return $data;
	}

	/**
	 * 根据试卷id和试题类型获取题目列表
	 * @param $testpaper_id试卷id $type题目类型
	 * @return list
	 */
	public function getQuestionList($testpaper_id, $type){

		$map['kh_question.del_flag'] = array('eq', '1');
		$map['kh_question.type'] = array('eq', $type);
		$map['kh_paper_question.testpaper_id'] = array('eq', $testpaper_id);

		$data = M('PaperQuestion')
		->field('kh_paper_question.value, kh_paper_question.question_id, kh_question.content, kh_question.level, kh_question.type')
		->join('JOIN kh_question ON kh_question.id = kh_paper_question.question_id')
		->where($map)->select();

		foreach($data as $key => $value){
			$data[$key]['content'] =  $this->DeleteHtml($data[$key]['content']);
		}

		return $data;

	}

	/**
	 * 功能：获取答案list
	 * @param question_id
	 */
	public function getAnsewe($question_id){
		
		$map['del_flag'] = array('eq', '1');
		$map['question_id'] = array('eq', $question_id);

		$list = M('Answer')
		->field('content, is_true')
		->where($map)->select();

		foreach($list as $key => $value){
			$list[$key]['content'] =  $this->DeleteHtml($list[$key]['content']);
		}

		if ($list == null || $list==false){
			$data = array('success'=>false, 'msg'=>'展示答案出错');
		}else{
			$data = array('success'=>true, 'list'=>$list);
		}

		return $data;
	}

	/**
	 * 功能：修改单个题目分数以及重新统计总分
	 * @param question_id
	 */
	public function changeValue($question_id, $testpaper_id, $value){

		$map['del_flag'] = array('eq', '1');
		$map['question_id'] = array('eq', $question_id);
		$map['testpaper_id'] = array('eq', $testpaper_id);
		$map1['testpaper_id'] = array('eq', $testpaper_id);
		$map2['id'] = array('eq', $testpaper_id);

		$form['value'] = $value;
		M('PaperQuestion')->where($map)->save($form);	//修改题目分数

		$count = M('PaperQuestion')
		->where($map1)->sum('value');
		//根据总分自动设置及格分数
		$pass_score=round(0.6*$count); 

		$form1['full_score'] = $count;
		$form1['pass_score'] = $pass_score;
		M('Testpaper')->where($map2)->save($form1);//计算并修改总分

		$data = array('success'=>true, 'msg'=>'修改成功');

		return $data;
	}

	/**
	 * 功能：删除单个题目以及重新计分
	 * @param $question_id, $testpaper_id
	 */
	public function deleteSingle($question_id, $testpaper_id){

		$map['del_flag'] = array('eq', '1');
		$map['question_id'] = array('eq', $question_id);
		$map['testpaper_id'] = array('eq', $testpaper_id);
		$map1['testpaper_id'] = array('eq', $testpaper_id);
		$map2['id'] = array('eq', $testpaper_id);

		M('PaperQuestion')->where($map)->delete();

		$count = M('PaperQuestion')
		->where($map1)->sum('value');

		$form1['full_score'] = $count;
		M('Testpaper')->where($map2)->save($form1);//计算并修改总分

		$data = array('success'=>true, 'msg'=>'删除成功');

		return $data;
	}

	/**
	 * 格式化题干
	 * @function
	 * @AuthorPJY
	 * @DateTime  2017-04-26T20:03:59+0800
	 * @param     [type]                   $str [description]
	 */
	public function DeleteHtml($str) {
		// 将字符串进行解码操作
		$str = htmlspecialchars_decode($str);
		// 去除掉html标签
		$str = preg_replace("/<([a-zA-Z]+)[^>]*>/","<\\1>",$str);
		return $str;
	}

	public function getchapter($question_id){
		$map5['question_id']=array('eq',$question_id);
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
        $data2 = D('Chapter')->field('name')->where($map3)->select();
        $Chaptername=array_column($data2,'name');
        $chapter=implode(",", $Chaptername);
        return $chapter;
	}
	/**
	 * 获取符合条件的题目list
	 */
	public function queryList($testDbIds, $chapterIds, $testpaper_id){

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
        // p($request);die();
        $questionArray = array();
        //转化为题目数组
        foreach ($request as $key => $item){
            array_push($questionArray,$item["question_id"]);
        }

        if(sizeof($questionArray) != 0){

        	$map1['testpaper_id'] = array('eq', $testpaper_id);
        	$list1 = M('PaperQuestion')->field('question_id')->where($map1)->select();
        	$testQuIds = array_column($list1, 'question_id');//去掉重复的题目id
        	foreach ($questionArray as $key => $value) {
        		foreach ($testQuIds as $value2) {
        			if ($value == $value2){
        				unset($questionArray[$key]);
        			}
        		}
        	}
        	// p($questionArray);die();
			if (sizeof($questionArray)==0) {
				$list=null;
			}else{
	        	$map['kh_question.id'] = array('in',
	        	 $questionArray);
		        $map['kh_question.del_flag'] = array('eq', '1');

		        $list = D('Question')
		        ->field('kh_question.id, kh_testdatabase.name, kh_question.content, kh_question.level, kh_question.type')
		        ->join('JOIN kh_testdatabase ON kh_question.testdb_id = kh_testdatabase.id')
		        ->where($map)->select();

		        foreach($list as $key => $value){
					$list[$key]['content'] =  $this->DeleteHtml($list[$key]['content']);
					$list[$key]['chapter'] =  $this->getchapter($list[$key]['id']);
				}
			}
        }else{
        	$list = null;
        }

        

        return $list;
	}

	/**
	 * 添加单个题目
	 */
	public function addSinger($question_id, $testpaper_id){

		$map['question_id'] = array('eq', $question_id);
		$map['testpaper_id'] = array('eq', $testpaper_id);

		$list = M('PaperQuestion')
		->where($map)->select();

		if (sizeof($list) != 0){
			$data = array('success'=>false, 'msg'=>'试卷中已有该题，请重新添加');
		}else{

			$form['testpaper_id'] = $testpaper_id;
			$form['question_id'] = $question_id;
			$form['value'] = '0';//新增的题默认0分

			M('PaperQuestion')->add($form);

			$data = array('success'=>true, 'msg'=>'添加成功，请前往详情页面修改分数');
		}

		return $data;
	}

	/**
	 * 获取正式考试试卷
	 */
	public function getAllFinalTest(){

		$map['del_flag'] = array('eq', '1');
		$map['type_id'] = array('eq', '2');
		$map['college_id'] = array('eq', session('accInfo.dept_id'));

		//检查权限部分
		if (session('accInfo.role') == 3 || session('accInfo.role') == 4 && D('TestTeacherPermission')->checkPermission()){

			$data = M('Testpaper')
			->field('id, name, full_score, pass_score, is_use')
			->where($map)->select();

			return $data;
		}else{
			return 0;
		}

	}

	/**
	 * 根据试卷id获取正在使用的班级
	 */
	public function getClassByPaper($testpaper_id){

		$map['kh_paper_courserclass.del_flag'] = array('eq', '1');
		$map['kh_paper_courserclass.testpaper_id'] = array('eq', $testpaper_id);

		$data = M('PaperCourserclass')
		->field('kh_paper_courserclass.testpaper_id, kh_paper_courserclass.courserclass_id, kh_courseclass.name, kh_paper_courserclass.start_time, kh_paper_courserclass.end_time, kh_paper_courserclass.comment')
		->join('join kh_courseclass on kh_courseclass.id = kh_paper_courserclass.courserclass_id')
		->where($map)->select();

		return $data;
	}

	/**
	 * 获取全部班级列表
	 */
	public function getAllClassList(){
		$map['del_flag'] = array('eq', '1');
		$map['college_id'] = array('eq', session('accInfo.dept_id'));

		$data = M('Courseclass')
		->field('id, name')
		->where($map)->select();

		return $data;
	}

	/**
	 * 获取正在使用的班级list,下拉框展示用
	 */
	public function getClassList($testpaper_id){

		$map['del_flag'] = array('eq', '1');
		$map['testpaper_id'] = array('eq', $testpaper_id);
		$ptime = time();
		$map['start_time'] = array('egt', $ptime);

		$data = M('PaperCourserclass')
		->field('courserclass_id as id')
		->where($map)->select();

		$list = array_column($data, 'id');

		return $list;
	}

	/**
	 * 检查试卷是否已被使用，如果有已开考或已结束班级存在，则该试卷无法继续添加班级
	 */
	public function checkTestUse($testpaper_id){

		$map['del_flag'] = array('eq', '1');
		$ptime = time();
		$map['start_time'] = array('elt', $ptime);

		$count = M('PaperCourserclass')
		->where($map)->count();

		if ($count != 0){
			return false;
		}else{
			return true;
		}
	}

	/**
	 * 添加班级的事件处理
	 */
	public function addClass($allClass_ids, $start_time, $end_time, $testpaper_id){

		$useingClass_ids = $this->getClassList($testpaper_id);
		if (sizeof($useingClass_ids) != 0){
			foreach ($allClass_ids as $key => $value) {
	    		foreach ($useingClass_ids as $value2) {
	    			if ($value == $value2){
	    				unset($allClass_ids[$key]);
	    			}
	    		}
	    	}
	    }

	    foreach ($allClass_ids as $key => $value){
	    	$form['testpaper_id'] = $testpaper_id;
	    	$form['courserclass_id'] = $value;
	    	$form['start_time'] = $start_time;
	    	$form['end_time'] = $end_time;
	    	$form['create_by'] = $this->getAccount();
	    	$form['create_date'] = $this->getTime();
	    	$form['del_flag'] = '1';

	    	M('PaperCourserclass')->add($form);
	    }
	    $data = array('success'=>true, 'msg'=>'保存成功');

	    return $data;
	}

	/**
	 * 删除班级的事件处理
	 */
	public function deleteClass($courserclass_id, $testpaper_id){

		$map['del_flag'] = array('eq', '1');
		$map['courserclass_id'] = array('eq', $courserclass_id);
		$map['testpaper_id'] = array('eq', $testpaper_id);
		$ptime = time();
		$map['start_time'] = array('egt', $ptime);

		$form['del_flag'] = '0';
		$form['update_by'] = $this->getAccount();
	    $form['update_date'] = $this->getTime();

	    M('PaperCourserclass')->where($map)->save($form);

	    $data = array('success'=>true, 'msg'=>'删除成功');
	    return $data;
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
	
}