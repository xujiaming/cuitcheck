<?php

namespace Student\Model;
use Think\Model;

/**
 * 试卷-问题表
 */
class AnswerModel extends Model{


	/**
	 * 根据问题-答案数组进行检验
	 * @function
	 * @AuthorPJY
	 * @DateTime  2017-07-22T17:43:41+0800
	 * @param     [type]                   $question_answer [description]
	 * @return    [type]                                    [description]
	 */
	public function checkAnswer($question_answer){

		$is_true = [];			// 答案数组
		foreach ($question_answer as $key => $value) {
			$map['question_id'] = $key;
			$map['content'] = $value;
			// 保存该答案是否正确
			$is_true[$key]['is_true'] = $this->where($map)->getField('is_true'); 
			// 检验填空题和判断题 : 答案不符合则设置为0
			if($is_true[$key]['is_true'] == NULL){
				$is_true[$key]['answer_id'] = 0;
				$is_true[$key]['is_true'] = 0;
			}
			// 获得选择题id
			else {
				$is_true[$key]['answer_id'] = $this->where($map)->getField('id'); 
			}

		}
		return $is_true;

	}

}