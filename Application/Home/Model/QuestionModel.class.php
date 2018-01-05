<?php
/**
 * 题目的数据模型
 * @arthur luochao
 */
namespace Home\Model;
use Think\Model;

class QuestionModel extends Model{


	/**
	 * 自动验证
	 * @var array
	 */
	protected $_validate = array(
		array('content','require','题目已经存在!',1,'unique',3),
	);


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
	 * 判断题目类型
	 * @function
	 * @AuthorPJY
	 * @DateTime  2017-04-18T00:47:05+0800
	 */
	public function JudgeType($type) {
		if($type == 1) {
			return "选择";
		}
		if($type == 2) {
			return "判断";
		}
		if($type == 3) {
			return "填空";
		}
	}

	/**
	 * 判断题目等级
	 * @function
	 * @AuthorPJY
	 * @DateTime  2017-04-18T00:50:37+0800
	 * @param     [type]                   $level [description]
	 */
	public function JugeLevel($level) {
		if($level == 1) {
			return "简单";
		}
		if($level == 2) {
			return "普通";
		}
		if($level == 3) {
			return "困难";
		}
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


	/**
	 * 返回信息
	 * @function
	 * @AuthorPJY
	 * @DateTime  2017-04-25T20:29:38+0800
	 * @return    [type]                   [description]
	 */
	public function packResult($status,$msg='') {
        if($msg !== ''){
            $arr['msg'] = $msg;
        }else{
            if($status){
                $arr['msg'] = '操作成功！';
            }else{
                $arr['msg'] = '操作失败！';
            }
        }

        $arr['status'] = $status;

        return $arr;

	}

	/**
	 * 根据题库id获得所有题目
	 * @function
	 * @AuthorPJY
	 * @DateTime  2017-04-18T00:04:30+0800
	 * @return    [type]                   [description]
	 */
	public function getQuestionList($testDBId,$type,$level,$keyword,$requestPage,$rows) {

		// 搜索信息
		$map['del_flag'] = array('eq', '1');
		$map['testdb_id'] = array('eq',$testDBId);
		if ($keyword != ''){
			// 题干
            $map['content'] = array('like', '%'.$keyword.'%');
            // 知识点
        }
        if ($type != ''){
           $map['type'] = array('eq',$type);
        }
        if ($level != ''){
           $map['level'] = array('eq',$level);
        }
        $limit = ($requestPage-1)*$rows.','.$rows;
        $total = $this		//获取全部符合条件的数据条数
        	->where($map)->count();
        $list = $this->where($map)->limit($limit)->order('id')->select();


		// 进行内容的处理
		foreach ($list as $key => $value) {

			// 获得所属的题库信息
			$testdb = current(M('testdatabase')->where(array('id'=>$value['testdb_id']))->select());
			$list[$key]['testdb_id'] = $testdb['name'];

			// 获得含有的知识点列表;
			// $knowledgeids = explode(",", $value['knowledge_ids']);
			$knowledgeids = M('question_know')->where(array('question_id'=>$value['id']))->getField('knowledge_id',true);
			$knowledgenames="";
			foreach ($knowledgeids as $key1 => $value1) {
				$knowledge = current(M('knowledge')->where(array('id'=>$value1))->select());
				$knowledgenames[$key1] =$knowledge['name'];
			}
			$list[$key]['knowledge_ids'] = implode(" ", $knowledgenames);

			// 格式题目类型
			$list[$key]['type'] = $this->JudgeType($value['type']);

			// 格式题目难度
			$list[$key]['level'] = $this->JugeLevel($value['level']);

			// 格式题目内容
			// preg_replace("/<([a-za-z]+)[^>]*>/","<\1>",$html)
			$list[$key]['content'] =  $this->DeleteHtml($list[$key]['content']);
			
		}
		$data = array(
			'pages'	=>	intval($total/$rows + 1),
			'list'	=>	$list,
		);
		return $data;
	}


	/**
	 * 得到一个问题及答案的详细信息
	 * @function
	 * @AuthorPJY
	 * @DateTime  2017-05-01T18:02:08+0800
	 * @return    [type]                   [description]
	 */
	public function getOneQuestion($id="",$testdb_id) {
		$question = $this->where(array("id"=>$id))->find();

		// 获得含有的知识点列表;
		// $knowledgeids = explode(",", $question['knowledge_ids']);
		$knowledgeids = M('question_know')->where(array('question_id'=>$id))->getField('knowledge_id',true);

		$question['knowledge_ids'] = $knowledgeids;

		$answer = D("Answer")->getOneInfo($id);

		$return = array(
			'answer' => $answer,
			'question' => $question
		);
		return $return;
	}



	/**
	 * 添加单个题目
	 * @function
	 * @AuthorPJY
	 * @DateTime  2017-04-25T20:27:53+0800
	 */
	public function addOneQuestion() {
		if(!$this->create()) {
			$return = $this->packResult(false,$this->getError());
		}else {
			$info = $this->add();
			if($info == false) {
				$return = $this->packResult(false);
			}else{
				// 进行答案的添加			
				$status = D("Answer")->addAnserByOne($info,$_POST);
				if($status == false) {
					$return = $this->packResult(false);
				}else {
					$return = $this->packResult(true);
				}

				// 进行知识点添加
				$knowledge_ids = split(',', $_POST['knowledgeIds']);
				$know_info = D("question_know")->addKnow($info, $knowledge_ids);
				if($know_info == false) {
					$return = $this->packResult(false);
				}else {
					$return = $this->packResult(true);
				}

			}
		}
		return $return;
	}


	/**
	 * 根据上传的数据添加题目
	 * @function
	 * @AuthorPJY
	 * @DateTime  2017-05-28T04:12:41+0800
	 * @return    [type]                   [description]
	 */
	public function uploadQuestion($question){
		$question['create_by'] = $this->getAccount();
		$question['create_date'] = $this->getTime();
		$question['del_flag'] = 1;
		$status = $this->add($question);
		return $status;
	}



	/**
	 * 编辑题目
	 * @function
	 * @AuthorPJY
	 * @DateTime  2017-05-01T17:52:26+0800
	 * @return    [type]                   [description]
	 */
	public function editQuestion() {
		if(!$this->create()) {
			$return = $this->packResult(false,$this->getError());
		}else {
			$info = $this->save();
			if($info == false) {
				$return = $this->packResult(false);
			}else{
				// 进行答案的修改
				$answer = M("answer")->where(array('question_id'=>$_POST['id']))->field('id')->select();			
				$status = D("Answer")->editAnswer($answer,$_POST);
				if($status == false) {
					$return = $this->packResult(false);
				}else {
					$return = $this->packResult(true);
				}

				// 进行知识点修改
				$knowledge_ids = split(',', $_POST['knowledgeIds']);
				$knowledgeids = M('question_know')->where(array('question_id'=>$_POST['id']))->delete();
				$know_info = D("question_know")->addKnow($_POST['id'], $knowledge_ids);
				if($know_info == false) {
					$return = $this->packResult(false);
				}else {
					$return = $this->packResult(true);
				}
			}
		}
		return $return;
	}




	/**
	 * 根据id删除题目
	 * @function
	 * @AuthorPJY
	 * @DateTime  2017-04-26T20:25:08+0800
	 * @param     [type]                   $id [description]
	 * @return    [type]                       [description]
	 */
	public function deleteQuestion($id) {
		$map['id'] = $id;
		$map['del_flag'] = '0';
		$map['update_date'] = $this->getTime();
		$map['update_by'] = $this->getAccount();
		$status = $this->save($map);
		if(status) {
			$return = $this->packResult(true);
		}else {
			$return = $this->packResult(false);
		}
		return $return;
	}



}