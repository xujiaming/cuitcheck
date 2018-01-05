<?php
namespace Home\Controller;
use Common\Controller\HomeBaseController;
	/**
	 * @function: 学生管理控制
	 * @Author: 梁轩豪
	 * @DateTime:  2017-03-11 10:37:51
	 */
class StudentMgrController extends HomeBaseController {
	/**
	 * @function: 展示学生信息列表
	 * @Author: 梁轩豪
	 * @DateTime:  2017-03-11T10:37:51+0800
	 * @return     [type]   
	 */
	public function studentList() {
		//获得请求的页码
		$requestPage = I('requestPage', '1');
		//查询的关键词
		$keyword = I('keyword', '');
		//查询的学校分级信息
		$stuArray = array(
			'college_id' => I('dcol',''),
			'major_id' => I('dmaj',''),
			'grade_id' => I('dgra',''),
			'class_id' => I('dcla',''),
			);
		//一页显示的数据条数
		$rows = 10;
		$accInfo = session('accInfo');
		$dept = $accInfo['dept_id'];
		$data = null;
		//如果为超级管理员或者系统管理员，可查看所有
		if(judgeRole() == 1) {
			$data = D('Student')->getAllList($requestPage, $keyword, $rows,$stuArray);
			$college_list = M('college')->order('id desc')->select();
			$major_list = M('major')->order('id desc')->select();
			$class_list = M('class')->order('id desc')->select();
		}else if(judgeRole() == 3) {
			//学院管理员
			$data = D('Student')->getAllList($requestPage, $keyword, $rows,$stuArray,$dept);
			$college_list = M('college')->where(array('id' => $dept))->order('id desc')->select();
			$major_list = M('major')->where(array('dept_id' => $dept))->order('id desc')->select();
			$class_list = M('class')->where(array('college_id' => $dept))->order('id desc')->select();

		}else {
			$data = null;
		}
		$grade_list = M('grade')->order('id desc')->select();
		//p($data);die;
		$this->assign('studentList',$data['list']);
		$this->assign('pages', $data['pages']);
		$this->assign('requestPage', $requestPage);
		$this->assign('keyword', $keyword);
		$this->assign('college_list', $college_list);
		$this->assign('major_list', $major_list);
		$this->assign('grade_list', $grade_list);
		$this->assign('class_list', $class_list);
		$this->assign('stuArray', $stuArray);
		//$this->assign('majorTest', $stuArray['major_id']);
		$this->display();
	}
	/**
	 * @function: 添加学生的视图
	 * @Author:梁轩豪
	 * @DateTime:  2017-03-12 13:42:07
	 */
	public function addStudent() {
		//添加学生分角色
		$accInfo = session('accInfo'); 
		$dept = $accInfo['dept_id'];
		//查询学院，专业，年级，班级
		//超级管理员，系统管理员可以添加任意学院学生
		if(in_array($accInfo['role'], array(1,2))) {
			$college_list = M('college')->field('id,name')->where(array('del_flag' => 1))->order('id desc')->select();
			//$major_list = M('major')->field('id,name')->order('id desc')->select();
			//$class_list = M('class')->field('id,name')->order('id desc')->select();
		}else if($accInfo['role'] == '3') {
			//学院管理员只可以添加本学院的学生
			$college_list = M('college')->where(array('id' => $dept,'del_flag'=>1))->order('id desc')->select();
			//$major_list = M('major')->where(array('dept_id' => $dept))->order('id desc')->select();
			//$class_list = M('class')->where(array('college_id' => $dept))->order('id desc')->select();

		}
		$grade_list = M('grade')->field('id,name')->where(array('del_flag' => 1))->order('id desc')->select();
		$this->assign('college_list', $college_list);
		//$this->assign('major_list', $major_list);
		$this->assign('grade_list', $grade_list);
		//$this->assign('class_list', $class_list);
		$this->assign('user_dept_id', $accInfo['dept_id']);
		$this->display();
	}
	/*
	联动选择专业，年级，班级
	 *//*
	public function linkSelect() {
		//获取前台传来的所选中的专业或年级ID
		//$major_id = I('major_id');
		//p($major_id);die;
		$grade_id = I('grade_id');
		//$gra_list = M('grade')->where(array('major_id'=>$major_id))->select();
		$cla_list = M('class')->where(array('grade_id'=>$grade_id))->select();
		// if($major_id != null) {
		// 	$opt = "<option>--请选择年级--</option>";
		// 	foreach($gra_list as $key => $val) {
		// 		$opt.="<option value='{$val['id']}'>{$val['name']}</option>";
		// 	}
		// }
		if($grade_id != null) {
			$opt = "<option>--请选择班级--</option>";
			foreach($cla_list as $key => $val) {
				$opt.="<option value='{$val['id']}'>{$val['name']}</option>";
			}
		}
		
		$this->ajaxReturn($opt,'json');
	}*/

    public function linkSelect() {
        //获取前台传来的所选中的学院id
        $dept_id = I('dept_id','');
        //$gra_list = M('grade')->where(array('major_id'=>$major_id))->select();
        $maj_list = M('major')->where(array('dept_id'=>$dept_id))->select();
        $cla_list = M('class')->where(array('college_id'=>$dept_id))->select();
        if($dept_id != '') {
            $opt1 = "";
            //该学院下专业
            if(empty($maj_list)) {
                $opt1 = "<option>暂无数据</option>";
            }else {
                foreach($maj_list as $key => $val) {
                    $opt1.="<option value='{$val['id']}'>{$val['name']}</option>";
                }
            }
            //该学院下班级
            $opt2 = "";
            if(empty($cla_list)) {
                $opt2 = "<option>暂无数据</option>";
            }else {
                foreach($cla_list as $key1 => $val1) {
                    $opt2.="<option value='{$val1['id']}'>{$val1['name']}</option>";
                }
            }
        }
        $data = array(
            'major' => $opt1,
            'class' => $opt2
        );
        //p($data);
        $this->ajaxReturn($data,'json');
    }
	/**
	 * @function: 添加学生
	 * @Author:梁轩豪
	 * @DateTime:  2017-03-12 13:43:11
	 */
	public function addStudentHandle() {
		if(!IS_POST) {
			$data = array('success'=>false, 'msg'=>'提交方式不正确');
		}else {
			//判断是否开启，如果未开启，将status设为0
			if($_POST['status'] == null) {
				$_POST['status'] = 0;
			}
			$data = D('Student')->addStu();
		}
		$this->ajaxReturn($data,'json');
	}
	/**
	 * @function: 编辑学生视图
	 * @Author:梁轩豪
	 * @DateTime:  2017-03-12T15:20:29+0800
	 * @return    
	 */
	public function editStudent() {
		$accInfo = session('accInfo');
		$dept = $accInfo['dept_id'];
        $id = I('id');
        $data = D('Student')->getStudentById($id);
		//查询学院，专业，年级，班级
		if(in_array($accInfo['role'], array(1,2))) {
			$college_list = M('college')->order('id desc')->select();
            //超级管理员默认先获取该学生所在学院下的专业和班级，后续可联动选择其他学院
			$major_list = M('major')->order('id desc')->where(array('dept_id' => $data['col_id'],'del_flag'=>1))->select();
			$class_list = M('class')->order('id desc')->where(array('college_id' => $data['col_id'],'del_flag'=>1))->select();
		}else if($accInfo['role'] == '3') {
			//学院管理员只能获取所在学院的专业与班级
			$college_list = M('college')->where(array('id' => $dept))->order('id desc')->select();
			$major_list = M('major')->where(array('dept_id' => $dept))->order('id desc')->select();
			$class_list = M('class')->where(array('college_id' => $dept))->order('id desc')->select();

		}
		$grade_list = M('grade')->order('id desc')->select();
		$this->assign('college_list', $college_list);
		$this->assign('major_list', $major_list);
		$this->assign('grade_list', $grade_list);
		$this->assign('class_list', $class_list);
		$this->assign('stu_info', $data);
		$this->display();
	}
	/*
	编辑学生信息提交
	 */
	public function editStudentHandle() {
		if(!IS_POST) {
			$data = array('success'=>false, 'msg'=>'提交方式不正确');
		}else {
			//判断是否开启，如果未开启，将status设为0
			if($_POST['status'] == null) {
				$_POST['status'] = 0;
			}
			$data = D('Student')->editStu();
		}
		$this->ajaxReturn($data,'json');
	}

	/*
	查看学生详情
	 */
	public function detailStudent() {
		$id = I('id');
		$data = D('Student')->getStudentById($id);
		$this->assign('stu_info',$data);
		$this->display();
	}
	/*
	删除学生，PS：在这里只是将del_flag改为0不显示
	 */
	public function delStudent() {
		$id = I('id');
		$data = D('Student')->deleteStudent($id);
		$this->ajaxReturn($data,'json');
	}
	/*
	重置密码 
	 */
	public function resetPs() {
		if(!IS_POST) {
			$data = array('success'=>false, 'msg'=>'提交方式不正确');
		}else{
			$data = D('Student')->reset();
		}
		$this->ajaxReturn($data, 'json');
	} 

	/*************************************************************************************/
	/**********************************学生信息的批量上传*********************************/
	/**
	 * @function: 上传文件注意事项页面
	 * @Author:梁轩豪
	 * @DateTime:  2017-03-13T13:46:58+0800
	 */
	public function importStudentHtml() {
		$this->display();
	}
	/**
	 * @function: 设置导入学生文件的模板
	 * @Author:梁轩豪
	 * @DateTime:  2017-03-14 13:25:59
	 * @return   
	 */
	public function studentTemplate() {
		//创建Excel对象
		Vendor("PHPExcel.PHPExcel");
		Vendor("PHPExcel.PHPExcel.IOFactory");
		$objExcel = new \PHPExcel();
		//设置文件名
		$title = '学生信息导入模板';
		$objSheet = $objExcel->getActiveSheet();
		//列
		$cellName = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
		//设置文件默认水平垂直居中
		$objSheet->getDefaultStyle()->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER)->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		//设置每列的宽度
		$objExcel->getActiveSheet()->getColumnDimension('A')->setWidth(16);
		$objExcel->getActiveSheet()->getColumnDimension('B')->setWidth(16);
		$objExcel->getActiveSheet()->getColumnDimension('C')->setWidth(16);
		$objExcel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
		$objExcel->getActiveSheet()->getColumnDimension('E')->setWidth(30);
		$objExcel->getActiveSheet()->getColumnDimension('F')->setWidth(16);
		$objExcel->getActiveSheet()->getColumnDimension('G')->setWidth(16);
		$objExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
		$objExcel->getActiveSheet()->getColumnDimension('I')->setWidth(22);
		$objExcel->getActiveSheet()->getColumnDimension('J')->setWidth(32);
		$objExcel->getActiveSheet()->getColumnDimension('K')->setWidth(16);
		$objExcel->getActiveSheet()->getColumnDimension('L')->setWidth(16);
		$objExcel->getActiveSheet()->getColumnDimension('M')->setWidth(16);
		$objExcel->getActiveSheet()->getColumnDimension('N')->setWidth(16);
		$objExcel->getActiveSheet()->getColumnDimension('O')->setWidth(16);
		// 设置时间列的格式为文本串,防止phpexcel将日期自动转换
		$objExcel->getActiveSheet()->getStyle('D')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
		//设置表头
		$cellHeader = array(
			'*学号','*姓名','*性别','*学院','*专业','*年级','*班级','手机号','邮箱','备注',
			);
		//填充表头
		for($i = 0;$i < sizeof($cellHeader);$i++) {
			$objExcel->getActiveSheet()->setCellValue($cellName[$i].'1',$cellHeader[$i]);
		}
		$objWriter = new \PHPExcel_Writer_Excel5($objExcel);
		//刷新缓冲
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
        exit(); //跳转结束，否则文件会报错

	}

	/**
	 * @function: 上传Excel文件
	 * @Author:梁轩豪
	 * @DateTime:  2017-03-13T13:49:32+0800
	 */
	public function importFileStudent() {
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
		$upload->savePath = '/student_import/';
		//上传文件
		$info = $upload->uploadOne($_FILES['stu_import']);
		if(!$info) {
			$result = array(
				'code' => 1,
				'msg' => '上传失败！请仔细阅读注意事项！'
				);
		}else{
			//获取上传后的文件名
			$file_name = $upload->rootPath.$info['savepath'].$info['savename'];
			//获得文件的后缀名
			$exts = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
			//调用函数，将数据保存到数据库，得到返回的错误集
			$temp = $this->saveStuToDb($file_name, $exts);
			if(sizeof($temp) == 0) {
				$result['msg'] = "全部学生信息导入成功！";
			}else {
				//定义错误提示格式
				for($i=1;$i<=8;$i++) {
					$type[$i]['msg']='<tr><td>第 ';
					$type[$i]['nums'] = 0;
				}
				for($i = 0;$i < sizeof($temp);$i++) {
					if($temp[$i]['error_code'] == 1) {
						$type[1]['nums']++;
						$type[1]['msg'] .=$temp[$i]['rows']."，";
					}
					if($temp[$i]['error_code'] == 2) {
						$type[2]['nums']++;
						$type[2]['msg'] .=$temp[$i]['rows']."，";
					}
					if($temp[$i]['error_code'] == 3) {
						$type[3]['nums']++;
						$type[3]['msg'] .=$temp[$i]['rows']."，";
					}
					if($temp[$i]['error_code'] == 4) {
						$type[4]['nums']++;
						$type[4]['msg'] .=$temp[$i]['rows']."，";
					}
					if($temp[$i]['error_code'] == 5) {
						$type[5]['nums']++;
						$type[5]['msg'] .=$temp[$i]['rows']."，";
					}
					if($temp[$i]['error_code'] == 6) {
						$type[6]['nums']++;
						$type[6]['msg'] .=$temp[$i]['rows']."，";
					}
					if($temp[$i]['error_code'] == 7) {
						$type[7]['nums']++;
						$type[7]['msg'] .=$temp[$i]['rows']."，";
					}
					if($temp[$i]['error_code'] == 8) {
						$type[8]['nums']++;
						$type[8]['msg'] .=$temp[$i]['rows']."，";
					}
				}
				$type[1]['msg'].='行</td><td>该行有必填项为空</td></tr>';
				$type[2]['msg'].='行</td><td>该行的学院,专业,年级,或班级无对应信息或者级联关系错误</td></tr>';
				$type[3]['msg'].='行</td><td>该行的学生档案在我院已存在</p>';
				$type[4]['msg'].='行</td><td>数据错误</td></tr>';
				$type[5]['msg'].='行</td><td>您只能添加您所在学院的学生信息</td></tr>';
				$type[6]['msg'].='行</td><td>该学生的性别格式错误</td></tr>';
				$type[7]['msg'].='行</td><td>该学生的手机格式错误</td></tr>';
				$type[8]['msg'].='行</td><td>该学生账号格式错误</td></tr>';
				$str = '<div style="padding: 5px;"><table class="layui-table" style="text-align:center;"><thead><tr><th style="text-align:center;">行数</th><th style="text-align:center;">错误原因</th><tr></thead>';
				for($j = 1;$j <= 8;$j++) {
					if($type[$j]['nums']) {
						$str.=$type[$j]['msg'];
					}
				}
				$str.='</table></div>';
				$result['msg'] = $str;
				$result['code'] = 2;
			}
		}
		$this->ajaxReturn($result, 'json');
		//echo json_encode($result);

	}
	/*
	对导入的文件数据进行验证导入数据库
	 */
	public function saveStuToDb($filename, $exts) {
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
		/*************开始处理对数据的验证****************/
		/***********预定义错误类型********************/
		/**
		 *   错误信息代码处理：code 1: 必填项为空
		 *   				   code 2: 无对应的学院年级或班级 
		 *   				   code 3: 该学生的信息已经存在
		 *   				   code 4: 添加出错
		 *   				   code 5：只能添加本学院的学生
		 *   				   code 6：性别格式错误
		 *   				   code 7: 手机格式错误
		 *   				   code 8：账号格式错误
		 *   				   code 0: 添加成功
		 *   				    
		 */
		//初始化错误的信息数组
		$error = array();
		//从第二行开始取数据,将数据统一转化为String类型，便于处理
		for($i = 2;$i <= $maxRow;$i++) {
			$stu_account = (String)$objPHPExcel->getActiveSheet()->getCell("A" . $i)->getValue();
			$stu_name = 	(String)$objPHPExcel->getActiveSheet()->getCell("B" . $i)->getValue();
	   		$stu_sex = (String)$objPHPExcel->getActiveSheet()->getCell("C" . $i)->getValue();

	   		$stu_college = (String)$objPHPExcel->getActiveSheet()->getCell("D" . $i)->getValue();
	   		$stu_major = (String)$objPHPExcel->getActiveSheet()->getCell("E" . $i)->getValue();
	   		$stu_grade = (String)$objPHPExcel->getActiveSheet()->getCell("F" . $i)->getValue();
	   		$stu_class = (String)$objPHPExcel->getActiveSheet()->getCell("G" . $i)->getValue();
	   		$stu_phone = (String)$objPHPExcel->getActiveSheet()->getCell("H" . $i)->getValue();
	   		$stu_email = (String)$objPHPExcel->getActiveSheet()->getCell("I" . $i)->getValue();
	   		$stu_remarks = (String)$objPHPExcel->getActiveSheet()->getCell("J" . $i)->getValue();
	   		//	1.判断是否必填项为空，如果有一项必填项为空，将错误信息追加至错误数组中，然后跳出本次循环，进行下一次循环
	   		if($stu_account==''||$stu_name==''||$stu_sex==''||$stu_college==''||$stu_major==''||$stu_grade==''||$stu_class=='') {
	   			$miss = $this->getError_array(1, $i);
	   			//将错误信息数组压入$error末尾
	   			array_push($error, $miss);
	   			continue;
	   		}
	   		//2. 判断学号的格式是否正确
	   		if(!$this->checkStuAccount($stu_account)) {
	   			$miss = $this->getError_array(8, $i);
	   			array_push($error, $miss);
	   			continue;
	   		}
	   		//	3.判断是否可以在数据库中出找到对应院校专业的信息
	   		$isset = $this->getId_ByName($stu_college, $stu_major, $stu_grade, $stu_class);
	   		if(!$isset['isset']) {
	   			$miss = $this->getError_array(2, $i);
	   			array_push($error, $miss);
	   			continue;
	   		}	   		
	   		//	4.判断该项中的学院是否是该登录管理员所在学院
	   		//如果不是，则结束本次循环
	   		$accInfo = session('accInfo');
	   		$dept = $accInfo['dept_id'];
	   		//如果是学院管理员导入，则进行判断是否是本学院学生
	   		if($accInfo['role'] == '3') {
	   			if($isset['ids']['dept_id'] != $dept) {
		   			$miss = $this->getError_array(5, $i);
		   			array_push($error, $miss);
		   			continue;
	   			}
	   		}
	   		
	   		  		
	   		//5.验证性别字段，性别表示，男1女0
	   		if($stu_sex=='男') {
	   			$sex = 1;
	   		}else if($stu_sex=='女') {
	   			$sex = 0;
	   		}else {
	   			$miss = $this->getError_array(6,$i);
	   			array_push($error, $miss);
	   			continue;
	   		}
	   		//	6.如果填写了手机号进行手机号的验证，判断手机号格式是否正确
	   		if(!empty($stu_phone)) {
	   			if(!$this->checkPhone($stu_phone)) {
	   			$miss = $this->getError_array(7, $i);
	   			array_push($error, $miss);
	   			continue;
	   			}
	   		}	 

	   		/*********************验证完成***********************/
	   		//判断该学生学号是否已经存在,如果已经存在则跳出当前本次循环，若要修改到学生信息列表页面查找学号进行修改；如果不存在，则进行添加
	   		if(!$this->isset_Student($stu_account)) {
	   			$miss = $this->getError_array(3, $i);
	   			array_push($error, $miss);
	   			continue;
	   		}else{
	   			//添加学生信息
	   			$stu_info = array(
	   				'account' => $stu_account,
	   				'password' => md5(sha1($stu_account)),
	   				'name' => $stu_name,
	   				'sex' => $sex,
	   				'class_id' => $isset['ids']['class_id'],
	   				'grade_id' => $isset['ids']['grade_id'],
	   				'major_id' => $isset['ids']['major_id'],
	   				'dept_id' => $isset['ids']['dept_id'],
	   				'phone' => $stu_phone,
	   				'email' => $stu_email,
	   				'remarks' => $stu_remarks,
	   				'status' => '1',
	   				'create_date' => date('Y-m-d H:i:s', time()),
	   				'create_by' => session('account'),
	   				);
	   			//入库
	   			//p($stu_info);die;
	   			$isSuccess = M('Student')->add($stu_info);
	   			if($isSuccess) {
	   				//添加成功,
	   				//
	   			}else{
	   				$miss = $this->getError_array(4, $i);
	   				array_push($error,$miss);
	   				continue;
	   			}
	   		}

		}
		//返回错误数组
		return $error;
	}
	/**
	 * 功能:根据错误代码，获取错误信息
	 * 参数:$error_code 错误代码  $row 错误的行号
	 * 返回值:$error 错误信息数组
	 */
	public function getError_array($error_code,$row){
		$error['rows'] = $row;
		$error['success'] = false;
	    if($error_code==1){
	    	$error['error_code'] = 1;
	    	$error['msg'] =  '该行有必填项为空!';
	    }
	    if($error_code==2){
	    	$error['error_code'] = 2;
	    	$error['msg'] =  '该行的学院,专业,年级,或班级无对应信息或者级联关系错误!';
	    }
	    if($error_code==3){
	    	$error['error_code'] = 3;
	    	$error['msg'] = '该行的学生信息已存在!';
	    }
	    if($error_code==4){
	    	$error['error_code'] = 4;
	    	$error['msg'] = '该行添加时出现错误!';
	    }
	    if($error_code==5) {
	    	$error['error_code'] = 5;
	    	$error['msg'] = '您只能添加本学院的信息！';
	    }
	    if($error_code==6) {
	    	$error['error_code'] = 6;
	    	$error['msg'] = '学生性别格式错误！';
	    }
	    if($error_code==7) {
	    	$error['error_code'] = 7;
	    	$error['msg'] = '学生手机号格式不正确！';
	    }
	    if($error_code==8) {
	    	$error['error_code'] = 8;
	    	$error['msg'] = '学生账号错误！';
	    }
	    if($error_code==0){
	    	$error['msg'] = '该行导入成功!';
	    	$error['success'] = true;
	    }

	    return $error;
	}
	/**
	 * 功能：根据Excel表中的院校名称，获取院校对应信息的id
	 * 参数：院校的名称
	 * 返回值:院校的id数组,或者错误编号
	 */	
	public function getId_ByName($college_name, $major_name, $grade_name, $class_name){
		$idArray = array();
		$map['col_name'] = $college_name;
		$map['maj_name'] = $major_name;
		$map['gra_name'] = $grade_name;
		$map['cla_name'] = $class_name;
	    $data = D('StudentNameId')->where($map)->select();
	    //p($data);die;
        $checkMajor = $this->checkAfterMajor($data[0]['dept_id'], $data[0]['major_id'], $data[0]['grade_id'], $data[0]['class_id']);
	    if(sizeof($data)==0){
	    	$idArray['isset'] = false;
	    }else if($checkMajor == 1){
            $idArray['isset'] = false;
        }else{
	    	$idArray['isset'] = true;
	    	//如果查询成功，将各个对应id保存

	    	$ids['dept_id'] = $data[0]['dept_id']
	    	;
	    	$ids['major_id'] = $data[0]['major_id'];
	    	$ids['grade_id'] = $data[0]['grade_id'];
	    	$ids['class_id'] = $data[0]['class_id'];
	    	$idArray['ids'] = $ids;
	    }
	    return $idArray;
	}
	/**
	 * 功能：判断是否存在学生账号信息  存在返回false
	 * 参数：学生学号
	 * 返回值：存在 false 不存在  true
	 */
	public function isset_Student($account){
		$result = M('Student')->where(array('account'=>$account,'del_flag'=>1))->count();
		//0条记录，表示还没有该学号，返回true
		return $result==0?true:false;
	}
	/**
	 * 功能：判断手机号的格式是否正确
	 * 参数：手机号
	 * 返回值：正确true 错误false
	 */
	public function checkPhone($phoneNum) {
		if(!is_numeric($phoneNum)) {
			return false;
		}
		return preg_match('#^13[\d]{9}$|^14[5,7]{1}\d{8}$|^15[^4]{1}\d{8}$|^17[0,6,7,8]{1}\d{8}$|^18[\d]{9}$#', $phoneNum) ? true : false;
	}
	/**
	 * 功能：判断学号号的格式是否正确
	 * 参数：学号
	 * 返回值：正确true 错误false
	 */
	public function checkStuAccount($stu_account) {
		if(!is_numeric($stu_account)) {
			return false;
		}else {
			return true;
		}
	}

    /**
     * @Function: 检查 学院 专业 年级 班级的级联关系是否正确
     * @Author: xuanhao
     * @DateTime: ${DATE} ${TIME}
     * * @param string $dept_id
     * @param string $major_id
     * @param string $grade_id
     * @param string $class_id
     * @return int|string  返回代码信息 1错误  0正确
     */
	public function checkAfterMajor($dept_id = '', $major_id = '', $grade_id = '', $class_id = '') {
	    $msg = '';
        //查询专业的上级学院
        $exit_flag1 = M('major')->field('dept_id')->where(array('id'=>$major_id,'del_flag'=>1))->find();
        //查询班级的所属学院和年级
        $exit_flag2 = M('class')->field('college_id,grade_id')->where(array('id'=>$class_id,'del_flag'=>1))->find();
        if($exit_flag1['dept_id'] != $dept_id || $exit_flag2['college_id'] != $dept_id || $exit_flag2['grade_id'] != $grade_id) {
            $msg = 1;
        }else {
            $msg = 0;
        }
        return $msg;
    }
	
} 
?>