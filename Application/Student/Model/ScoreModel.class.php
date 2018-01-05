<?php

namespace Student\Model;
use Think\Model;

/**
 * 成绩表
 */
class ScoreModel extends Model{

	 protected $_auto = array (
	 		array('create_date', 'getTime', 1, 'callback'),		//新增数据时将创建时间设为当前时间		
 	);


	 /**
	 * 获取当前时间
	 * 
	 */
	public function getTime(){
		return date('Y-m-d H:i:s', time());
	}



	/**
	 * 根据检验的答案进行成绩的存入
	 * @function
	 * @AuthorPJY
	 * @DateTime  2017-07-24T11:09:19+0800
	 * @param     [type]                   $account      [description]
	 * @param     [type]                   $testpaper_id [description]
	 * @param     [type]                   $checkArr     [description]
	 * @return    [type]                                 [description]
	 */
	public function saveScore($account, $testpaper_id, $checkArr) {
		$map['account'] = $account;
		$map['testpaper_id'] = $testpaper_id;
		$map['del_flag'] = 1;
		$map['score'] = 0;
		$map['create_date'] = date('y-m-d h:i:s',time());
		foreach ($checkArr as $key => $value) {
			// 如果答案正确
			if($value['is_true'] == 1 ){
				$map['score'] += M('paper_question')
							->where(array('testpaper_id' => $testpaper_id, 'question_id' => $key))
							->getField('value');
			}
		}
		$this->add($map);
	}


}