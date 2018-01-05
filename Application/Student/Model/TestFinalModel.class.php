<?php 


namespace Student\Model;
use Think\Model;

/**
 * 试卷详情
 */
class TestFinalModel extends Model{	
	
	/**
	 * 获取试卷详情
	 * @function
	 * @AuthorPJY
	 * @DateTime  2017-07-15T14:43:30+0800
	 * @param     [type]                   $paper_id   试卷id
	 * @param     [type]                   $student_id 学生id
	 * @param     [type]                   $type       试卷错误类型
	 * @param     [type]                   $pape       页码
	 * @return    [type]                               [description]
	 */
	public function getPaperDetails($paper_id, $account_id){

		// 获得试卷相关信息
		$list = M('testpaper')
				->field(array('name','full_score','pass_score','type_id','kh_paper_courserclass.create_by'))
				->join('kh_paper_courserclass ON kh_paper_courserclass.testpaper_id = kh_testpaper.id')
				->where(array('id'=>$paper_id))
				->select();

		// var_dump($paper_id."+".$account_id);
		$list[0]['type_name'] = M('dict')->where(array('type'=>'testType','value'=>$list[0]['type_id']))->getField('label');
		$list[0]['your_score'] = M('score')
								->where(array('account'=>$account_id,'testpaper_id'=>$paper_id))
								->getField('score');	

		return $list[0];

	}

	/**
	 * 得到问题详情
	 * @function
	 * @AuthorPJY
	 * @DateTime  2017-07-16T09:54:28+0800
	 * @param     [type]                   $paper_id   [description]
	 * @param     [type]                   $student_id [description]
	 * @param     [type]                   $type       [description]
	 * @param     [type]                   $pape       [description]
	 * @return    [type]                               [description]
	 */
	public function getQuestionDetails($paper_id, $student_id, $type, $requestPage, $rows){
		$map['testpaper_id'] = $paper_id;
		$map['student_id'] = $student_id;
		$map['is_true'] = $type;

		$limit = ($requestPage-1)*$rows.','.$rows;

		// 获得全部类型题目
		if($type == 'all'){
			unset($map['is_true']);
		}

		$total = $this->where($map)->count();


		// 获得题目详情
		$question = $this
					->field(array('question_id','answer_id','is_true'))
					->where($map)
					->limit($limit)
					->select();

		foreach ($question as $key => $value) {
			
			// 题目
			$question[$key]['question_content'] = M('question')->where(array('id'=>$value['question_id']))->getField('content');

			// 答案
			$question[$key]['answer'] = M('answer')->where(array('question_id'=>$value['question_id']))->getField('id,content,is_true',':');
			
			// 获得相关知识点
			// $knowledges = M('question')->where(array('question_id'=>$value['question_id']))->getField('knowledge_ids');
			// $knowledges = explode(',', $knowledges);
			$knowledges = M('question_know')->where(array('question_id'=>$value['question_id']))->getField('knowledge_id',true);
			foreach ($knowledges as $key1 => $value1) {
				$question[$key]['knowledges'][$key1] = M('knowledge')->where(array('id'=>$value1))->getField('name');
			}

		}
		$list = array(
			'pages' => intval($total / $rows + 1 ),
			'question' => $question,

		);

		return $list;
	}


	/**
	 * 根据检验结果,存入最终答案
	 * @function
	 * @AuthorPJY
	 * @DateTime  2017-07-24T10:22:30+0800
	 * @param     [type]                   $checkArr [description]
	 * @return    [type]                             [description]
	 */
	public function saveAnswer($testpaper_id, $student_id, $checkArr) {
		$map = [];
		foreach ($checkArr as $key => $value) {
			$map[] = array(
				'testpaper_id' => $testpaper_id, 
				'student_id' => $student_id, 
				'question_id' => $key,
				'answer_id' => $value['answer_id'],
				'is_true' => $value['is_true']
			);

		}
		$info = $this->addAll($map);
		return $info;
	}


}