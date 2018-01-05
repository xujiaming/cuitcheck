<?php

/**
 * 答案的数据模型
 */
namespace Home\Model;
use Think\Model;

class AnswerModel extends Model{


	/**
	 * 自动完成
	 * @var array
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
	 * 添加一个题目的答案
	 * @function
	 * @AuthorPJY
	 * @DateTime  2017-04-26T21:01:09+0800
	 */
	public function addAnserByOne($id,$info) {
		// return $info;
		 
	 	$map['question_id'] = $id;
		$map['del_flag'] = 1;
		$map['create_by'] = $this->getAccount();
		$map['create_date'] = $this->getTime();

		 
		// 选择题
		if($info['type'] == 1) {
			$select = $info['select'];
			for ($i=1; $i <=4 ; $i++) { 
				$map['content'] = $info["answer$i"];
				// 正确答案的判断
				if($select == $i){
					$map['is_true'] = 1;
				}else {
					$map['is_true'] = 0;
				}
				$return = $this->add($map);
			}
		}

		// 判断题
		if($info['type'] == 2) {
			// 内容
			$map['content'] = $info['content'];
			// 是否正确
			if($info['answerjudge'] == true){
				$map['is_true'] = 1;
			}else {
				$map['is_true'] = 0;
			}
			$return = $this->add($map);

		}

		// 填空题
		if($info['type'] == 3) {
			$map['content'] = $info['answertian'];
			$map['is_true'] = 1;
			$return = $this->add($map);
		}

		return $return;
	}


	/**
	 * 得到单个题目的详细信息
	 * @function
	 * @AuthorPJY
	 * @DateTime  2017-05-01T18:20:09+0800
	 * @return    [type]                   [description]
	 */
	public function getOneInfo($id) {
		$answer = $this->where(array("question_id"=>$id))->select();
		return $answer;
	}



	/**
	 * 编辑答案
	 * @function
	 * @AuthorPJY
	 * @DateTime  2017-05-02T12:37:02+0800
	 * @return    [type]                   [description]
	 */
	public function editAnswer($answer,$info) {
		$map['question_id'] = $info['id'];
		$map['del_flag'] = 1;
		$map['update_by'] = $this->getAccount();
		$map['update_date'] = $this->getTime();
		// var_dump($answer);
		// exit();

		// 选择题
		if($info['type'] == 1) {
			$select = $info['select'];
			for ($i=1; $i <=4 ; $i++) { 
				$map['content'] = $info["answer$i"];
				$j = $i - 1;
				$map['id'] = $answer[$j]['id'];
				// 正确答案的判断
				if($select == $i){
					$map['is_true'] = 1;
				}else {
					$map['is_true'] = 0;
				}
				$return = $this->save($map);
			}
		}

		// 判断题
		if($info['type'] == 2) {
			// 内容
			$map['content'] = $info['content'];
			$map['id'] = $answer[0]['id'];
			// 是否正确
			if($info['answerjudge'] == true){
				$map['is_true'] = 1;
			}else {
				$map['is_true'] = 0;
			}
			$return = $this->save($map);

		}

		// 填空题
		if($info['type'] == 3) {
			$map['content'] = $info['answertian'];
			$map['id'] = $answer[0]['id'];
			$return = $this->save($map);
		}

		return $return;

	}



}