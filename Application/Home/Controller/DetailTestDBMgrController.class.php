<?php 
namespace Home\Controller;
use Common\Controller\HomeBaseController;

/**
 *  题库详细管理控制页面
 */
class DetailTestDBMgrController extends HomeBaseController{

	/**
	 * 自动验证
	 * @var array
	 */
	protected $_validate =  array(

	);

	/**
	 * 权限验证
	 * @function
	 * @AuthorPJY
	 * @DateTime  2017-04-26T20:20:52+0800
	 * @return    [type]                   [description]
	 */
	public function checkaddPermiss() {
		if (session('accInfo.role') != 1 ){
			$testdb_id = I('id');
			$status = D('testdatabase')->checkPermiss($testdb_id,2);
			if(!$status) {
				$data['msg'] = "没有操作权限";
				$data['status'] = false;
			}else {
				$data['msg'] = "具有操作权限";
				$data['status'] = true;
			}
		} else{
			$data['msg'] = "具有操作权限";
			$data['status'] = true;
		}
		
		$this->ajaxReturn($data);
	}

	/**
	 * 添加单个题目模板渲染
	 * @function
	 * @AuthorPJY
	 * @DateTime  2017-04-18T22:08:56+0800
	 */
	public function addOneQuestion() {
		$testdb_id = I("testdb_id","");
		// $knowledge = M('knowledge')->field('id,name')->where(array('del_flag'=>'1'))->select();
		// 根据该题库所属学科， 将知识点按课程分类
		$lession_id = M('testdatabase')->where(array('id'=>$testdb_id))->getField('lession_id');
		$knowledge = D('knowledge')->getKonwlegeByLession($lession_id);
		$this->assign('knowledge',$knowledge);	
		$this->assign('testdb_id',$testdb_id);	
		$this->display();			
	}


	/**
	 * 添加单个题目动作
	 * @function
	 * @AuthorPJY
	 * @DateTime  2017-04-25T20:17:28+0800
	 */
	public function addOneAction() {
		if(!$_POST) {
			$return = array('msg'=>"提交方式不对",'status'=>false);
		} else {
			// $_POST['knowledge_ids'];
			$return = D("Question")->addOneQuestion();
		}
		$this->ajaxReturn($return);
	}


	/**
	 * 编辑题目模板渲染
	 * @function
	 * @AuthorPJY
	 * @DateTime  2017-05-01T18:06:27+0800
	 * @return    [type]                   [description]
	 */
	public function editQuestion() {
		$testdb_id = I("testdb_id","");
		$id = I("id","");

		// 根据该题库所属学科， 将知识点按课程分类
		$lession_id = M('testdatabase')->where(array('id'=>$testdb_id))->getField('lession_id');
		$knowledge = D('knowledge')->getKonwlegeByLession($lession_id);
		// 得到单个详细信息
		$info = D("question")->getOneQuestion($id,$testdb_id);
		// var_dump($knowledge);
		// exit();
		$this->assign("id",$id);
		$this->assign('testdb_id',$testdb_id);
		$this->assign('knowledge',$knowledge);
		$this->assign('answer',$info['answer']);
		$this->assign('question',$info['question']);
		$this->display();
	}


	/**
	 * 编辑题目动作
	 * @function
	 * @AuthorPJY
	 * @DateTime  2017-05-01T17:49:26+0800
	 * @return    [type]                   [description]
	 */
	public function editAction() {
		if(!$_POST) {
			$return = array('msg'=>"提交方式不对",'status'=>false);
		} else {
			// $_POST['knowledge_ids'];
			$return = D("Question")->editQuestion();
		}
		$this->ajaxReturn($return);
	}


	/**
	 * 删除题目事件
	 * @function
	 * @AuthorPJY
	 * @DateTime  2017-04-26T20:13:20+0800
	 * @return    [type]                   [description]
	 */
	public function deleteAction() {
		$id = I("id");
		$return = D("question")->deleteQuestion($id);
		$this->ajaxReturn($return);
	
	}


	/**
	 * 上传的图片接口
	 * @function
	 * @AuthorPJY
	 * @DateTime  2017-04-25T18:47:13+0800
	 * @return    [type]                   [description]
	 */
	public function uploads() {
		$upload = new \Think\Upload();
		$upload->maxSize = "31457280";
		$upload->exts = array("jpg","gif","png","jpeg");	//上传文件类型
		$upload->autoSub = true;							//自动使用子目录保存上传的文件
		$upload->subName = array('date','Ymd');				//文件命名方式以时期和时间戳命名		
		$upload->savePath = '/question_photo/';
		//设置时区为中国
		date_default_timezone_set('prc');
		$time = time();
		$tempName = $time."".rand(1,10000);
		//设置文件名保存为时间加随机数
		$upload->saveName = $tempName;

		$info = $upload->upload();
		// 文件名
		$site = "http://localhost/cuitcheck/Uploads";
		$filename =$site.$info['file']['savepath'].$info['file']['savename'];
		if(!$info) {
			$return = array(
				'code' => 1,
				'msg' => $this->error($upload->getError()),
			);
		}else {
			$return = array(
				'code' => 0,
				'msg' => '上传成功！',
				'data' => array(
				// 'src'=>"http://localhost/cuitcheck/Uploads/question_photo/20170425/1493121026388.jpg",			//前台页面返回的图片	
					'src' => $filename,
				),
			);
		}
		$this->ajaxReturn($return);
	}



	/**
	 * 上传题目页面
	 * @function
	 * @AuthorPJY
	 * @DateTime  2017-05-28T01:59:22+0800
	 * @return    [type]                   [description]
	 */
	public function uploadQuestionViews(){
		$testdb_id = I('testdb_id');
		$this->assign('testdb_id',$testdb_id);
		$this->display();
	}



	/**
	 * 上传题目模板
	 * @function
	 * @AuthorPJY
	 * @DateTime  2017-05-28T02:51:26+0800
	 * @return    [type]                   [description]
	 */
	public function questionTemple(){
		//创建Excel对象
		Vendor("PHPExcel.PHPExcel");
		Vendor("PHPExcel.PHPExcel.IOFactory");
		$objExcel = new \PHPExcel();
		//设置文件名
		$title = '批量题目导入模板';
		$objSheet = $objExcel->getActiveSheet();
		//设置文件默认水平垂直居中
		$objSheet->getDefaultStyle()->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER)->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

		//列
		$cellName = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
		
		//

		//设置自动换行
		$objExcel->getActiveSheet()->getStyle('A')->getAlignment()->setWrapText(true);
		$objExcel->getActiveSheet()->getStyle('B')->getAlignment()->setWrapText(true);
		$objExcel->getActiveSheet()->getStyle('C')->getAlignment()->setWrapText(true);
		$objExcel->getActiveSheet()->getStyle('D')->getAlignment()->setWrapText(true);
		$objExcel->getActiveSheet()->getStyle('E')->getAlignment()->setWrapText(true);

		//设置宽度
		$objExcel->getActiveSheet()->getColumnDimension('A')->setWidth(16);
		$objExcel->getActiveSheet()->getColumnDimension('B')->setWidth(16);
		$objExcel->getActiveSheet()->getColumnDimension('C')->setWidth(50);
		$objExcel->getActiveSheet()->getColumnDimension('D')->setWidth(50);
		$objExcel->getActiveSheet()->getColumnDimension('E')->setWidth(30);


		//设置表头
		$cellHeader = array(
			'*题目类型','*难度','*题目内容','*答案','备注',
			);

		// 设置例题
		
		// 填空题
		$question1 = array(
			'3',
			'2',
			'设a,b,t 为整型变量,初值为a=7,b=9,执行完语句t=(a>b)?a:b后,t 的值是',
			'1',
			'测试例题(添加时,请务必将其覆盖或删除!!!)'
			);
		// 判断题
		$question2 = array(
			'2',
			'1',
			'C语言程序总是从main()函数开始执行',
			'T(T正确,F错误)',
			'测试例题(添加时,请务必将其覆盖或删除!!!)'
			);
		//选择题
		$question3 = array(
			'3',
			'3',
			'设a和b均为double型变量，且a=5.5、b=2.5，则表达式(int)a+b/b的值是',
			'0:6.500000(END)0:6(END)3:5.500000(END)0:6.000000(END)(0代表错误答案,正确答案请用当前顺序表示(从1开始).每个答案以"(END)"结束).',
			'测试例题(添加时,请务必将其覆盖或删除!!!)'
			);

		//填充表头
		for($i = 0;$i < sizeof($cellHeader);$i++) {
			$objExcel->getActiveSheet()->setCellValue($cellName[$i].'1',$cellHeader[$i]);
		}
		// 填充例题
		for($i = 0;$i < sizeof($question1);$i++) {
			$objExcel->getActiveSheet()->setCellValue($cellName[$i].'2',$question1[$i]);
		}
		for($i = 0;$i < sizeof($question2);$i++) {
			$objExcel->getActiveSheet()->setCellValue($cellName[$i].'3',$question2[$i]);
		}
		for($i = 0;$i < sizeof($question3);$i++) {
			$objExcel->getActiveSheet()->setCellValue($cellName[$i].'4',$question3[$i]);
		}

		$objWriter = new \PHPExcel_Writer_Excel5($objExcel);
		// 刷新缓存
		ob_end_clean();
		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
		header("Content-Type:application/force-download");
		header("Content-Type:application/vnd.ms-execl");
		header("Content-Type:application/octet-stream");
		header("Content-Type:application/download");
		//生成的文件名字
		//attachment新窗口打印inline本窗口打印
		header('Content-Disposition:attachment;filename='.$title.'.xls');
		header("Content-Transfer-Encoding:binary");
		//文件通过浏览器下载
		$objWriter->save('php://output');

	}


	/**
	 * 导入题目
	 * @function
	 * @AuthorPJY
	 * @DateTime  2017-05-28T01:58:15+0800
	 * @return    [type]                   [description]
	 */
	public function uploadQuestion(){
		// 获得题库id
		$testdb_id = $_POST['testdb_id'];

		//实例化上传类
		$upload = new \Think\Upload();
		//设置上传文件大小，此处为 3M
		$upload->maxSize = 3145728;
		//上传文件类型
		$upload->exts = array('xlsx','xls');
		//设置时区为中国
		date_default_timezone_set('prc');
		$time = time();
		$tempName = $time."".rand(1,10000);
		//设置文件名保存为时间加随机数
		$upload->saveName = $tempName;
		//设置文件保存路径
		$upload->savePath = '/question_import/';
		//上传文件
		$info = $upload->uploadOne($_FILES['question_import']);
		if(!$info) {
			$result = array(
				'status' => false,
				'msg' => '上传失败！请仔细阅读注意事项！'
			);
		}else{
			//获取上传后的文件名
			$file_name = $upload->rootPath.$info['savepath'].$info['savename'];
			//获得文件的后缀名
			$exts = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
			//调用函数，将数据保存到数据库，得到返回的错误集
			$temp = $this->saveQuesToDb($file_name, $exts,$testdb_id);
			if(sizeof($temp) == 0) {
				$result['msg'] = "全部学生信息导入成功！";
				$result['status'] = true;
			}else{
				$result['msg'] = $temp[0];
				// $result['msg'] = $temp;
				$result['status'] = false;
			}

		}
		$this->ajaxReturn($result);
	}


	/**
	 * 将问题保存到数据库中
	 * @function
	 * @AuthorPJY
	 * @DateTime  2017-05-28T03:20:45+0800
	 * @return    [type]                   [description]
	 */
	public function saveQuesToDb($filename,$exts,$testdb_id){
		//引入类
		Vendor("PHPExcel.PHPExcel");
		//判断导入的Excel文件格式
		if($exts == 'xlsx') {
			$objReader = \PHPExcel_IOFactory::createReader('Excel2007');
			$objPHPExcel = $objReader->load($filename, $encode = 'utf-8');
		}else if($exts == 'xls') {
			$objReader = \PHPExcel_IOFactory::createReader('Excel5');
			$objPHPExcel = $objReader->load($filename, $encode = 'utf-8');
		}
		//获取sheet，如果需要兼容多sheet，将这里改为遍历
		$sheet = $objPHPExcel->getSheet(0);
		//获得行数和列数
		$maxRow = $sheet->getHighestRow();
		$maxColumn = $sheet->getHighestColumn();
		//初始化错误的信息数组
		$error = array();

		//从第二行开始取数据判断正确性
		for($i = 2;$i <= $maxRow;$i++) {
			$type = $objPHPExcel->getActiveSheet()->getCell("A".$i)->getValue();	//题目类型
			$level = $objPHPExcel->getActiveSheet()->getCell("B".$i)->getValue();	//题目难度
			$content = $objPHPExcel->getActiveSheet()->getCell("C".$i)->getValue();	//题目内容
			$answer = $objPHPExcel->getActiveSheet()->getCell("D".$i)->getValue();	//题目答案
			$remarks = $objPHPExcel->getActiveSheet()->getCell("E".$i)->getValue();	//题目备注

			if($type==''&& $level==''&& $content==''&& $answer==''){
				break;
			}

			//	1.判断是否必填项为空，如果有一项必填项为空，将错误信息追加至错误数组中，然后跳出本次循环
	   		if($type==''|| $level==''|| $content==''|| $answer=='') {
	   			$miss = $this->getError_array(0);
	   			//将错误信息数组压入$error末尾
	   			array_push($error, "第".$i."行".$miss);
	   			break;
	   		}


	   		// 判断类型是否正确
	   		// return ($type != 1 && $type != 2 && $type != 3);
	   		if(!($type != 1 || $type != 2 || $type != 3)) {
	   			$miss = $this->getError_array(1);
	   			//将错误信息数组压入$error末尾
	   			array_push($error, "第".$i."行".$miss);
	   			break;
	   		}

	   		// 判断难度是否正确
	   		if(!($level != 1 || $level != 2 || $level != 3)) {
	   			$miss = $this->getError_array(2);
	   			array_push($error, "第".$i."行".$miss);
	   			break;
	   		}


	   		// 判断选择题答案是否包含ABCD选项
	   		if($type == 1){
	   			// 定义ABCD数组 
	   			$needle = array(
	   				'1:','2:','3:','4:'
   				);
				
				
   				// 没有找到1234其中的一个,则退出
   				if(strpos($answer,$needle[0]) && strpos($answer,$needle[1]) && strpos($answer,$needle[2])  && strpos($answer,$needle[3]) ){
   					$miss = $this->getError_array(3);
		   			array_push($error, "第".$i."行".$miss);
		   			break;
   				}
	   		}






	   		// 判断判断题是否符合规定
	   		if($type == 2){
	   			// 定义ABCD数组 
	   			$needle = array(
	   				'T','F'
   				);
   				// 没有找到ABCD其中的一个,则退出
   				for ($j=0; $j < $needle.length; $j++) { 
	   				if(strpos($answer,$needle[$j]) === false){
	   					$miss = $this->getError_array(3);
			   			array_push($error, "第".$i."行".$miss);
			   			break;
	   				}
   				}
	   		}
	   	
		}

		// 进行数据添加
		if(empty($error)){
			for($i = 2;$i <= $maxRow;$i++) {
				$type = $objPHPExcel->getActiveSheet()->getCell("A".$i)->getValue();	//题目类型
				$level = $objPHPExcel->getActiveSheet()->getCell("B".$i)->getValue();	//题目难度
				$content = $objPHPExcel->getActiveSheet()->getCell("C".$i)->getValue();	//题目内容
				$answer = $objPHPExcel->getActiveSheet()->getCell("D".$i)->getValue();	//题目答案
				$remarks = $objPHPExcel->getActiveSheet()->getCell("E".$i)->getValue();	//题目备注

				if($type==''&& $level==''&& $content==''&& $answer==''){
					break;
				}

				// 进行答案的添加
				$question = array(
					'testdb_id' => $testdb_id,
					'content' => $content,
					'type' => $type,
					'level' => $level,
					'remarks' => $remarks
				);
				$question_id = D('question')->uploadQuestion($question);		//得到题目id
				
				// 进行答案的添加
				$answer_content = array(
					'type' => $type,
				);

				// 选择题
				if($type == 1){
					// 将答案分隔开
					$answer = explode("(END)",$answer);

					// 遍历每个答案
					for($j = 0; $j < sizeof($answer); $j++) {
						// 将答案内容与正确性分隔开
						$content = explode(":",$answer[$j]);
						// 正确性
						if($content[0] != 0){
							$answer_content['select'] = $content[0];
						}
						// 答案内容
						$k = $j+1;
						$answer_content["answer".$k] = $content[1];
					}
				}


				// 判断题
				if($type == 2) {
					// 判断题内容
					$answer_content['content'] = $answer;
					// 判断是否正确
					if($answer == "T"){	
						$answer_content['answerjudge'] = true;
					}else{
						$answer_content['answerjudge'] = false;
					}
				}


				// 填空题
				if($type == 3) {
					$answer_content['answertian'] = $answer;
				}

				D("answer")->addAnserByOne($question_id,$answer_content);
			}
		}
		return $error;
	}


	/**
	 * 获得错误类型
	 * @function
	 * @AuthorPJY
	 * @DateTime  2017-05-28T04:16:45+0800
	 * @return    [type]                   [description]
	 */
	public function getError_array($error_code){
		$error = array(
			'请将必选项的内容填充完整!',
			'题目类型不符合规定!请仔细阅读注意事项!',
			'题目难度不符合规定!请仔细阅读注意事项!',
			'答案内容不符合规定!请仔细阅读注意事项!'
		);
		return $error[$error_code];
	}





}