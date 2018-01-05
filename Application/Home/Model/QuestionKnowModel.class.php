<?php

/**
 * 题目知识点模型
 */
namespace Home\Model;
use Think\Model;

class QuestionKnowModel extends Model{


	/**
	 * 根据问题id添加知识点
	 * @function
	 * @AuthorPJY
	 * @DateTime  2017-07-24T16:11:19+0800
	 * @param     [type]                   $question_id [description]
	 * @param     [type]                   $knowledges  [description]
	 */
	public function addKnow($question_id,$knowledges) {
		foreach ($knowledges as $key => $value) {
			$map[] = array(
				'question_id' => $question_id,
				'knowledge_id' => $value,
			);
		}
		$status = $this->addAll($map);
		return $status;
	}

}