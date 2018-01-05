<?php

namespace Student\Model;
use Think\Model;

/**
 * 试卷-问题表
 */
class PaperQuestionModel extends Model{


	/**
	 * 根据试卷id得到问题列表
	 * @function
	 * @AuthorPJY
	 * @DateTime  2017-07-22T09:53:03+0800
	 * @return    [type]                   [description]
	 */
	public function getQuestionList($paper_id){
		// 得到问题id
		$question = $this->where(array('testpaper_id'=>$paper_id))->getField('question_id',true);
		$question_info = [];
		foreach ($question as $key => $value) {
			$answer_info = "";
			$info = M('question')->field(array('content','type','id'))->where(array('id'=>$value))->select();
			$question_info[$key] = $info[0];
			$answer_info = M('answer')->field(array('content', 'id'))->where(array('question_id'=>$value))->select();
			$question_info[$key]['answer'] = $answer_info;

		
		}

	


		$data = $question_info;
		return $data;
	}

}