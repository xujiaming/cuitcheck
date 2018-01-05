<?php
	/**
	 * 公共的方法类
	 */
	/**
	 * 调试时更好的打印数据的函数
	 * @Author   taolei
	 * @DateTime 2017-02-16
	 * @param    [array]     $data [需要打印的数组]
	 * @return   [String]         返回打印的拼接字符串
	 */
	function p($data){
	    // 定义样式
	    $str='<pre style="display: block;padding: 9.5px;margin: 44px 0 0 0;font-size: 13px;line-height: 1.42857;color: #333;word-break: break-all;word-wrap: break-word;background-color: #F5F5F5;border: 1px solid #CCC;border-radius: 4px;">';
	    // 如果是boolean或者null直接显示文字；否则print
	    if (is_bool($data)) {
	        $show_data=$data ? 'true' : 'false';
	    }elseif (is_null($data)) {
	        $show_data='null';
	    }else{
	        $show_data=print_r($data,true);
	    }
	    $str.=$show_data;
	    $str.='</pre>';
	    echo $str;
	}
	//获取用户账号
	function getCurrentUser(){
		return session("account");
	}
	//根据session获取该用户属于哪个学院
	function getdeptId(){
		$account=session("account");
		$map['account']=array('eq',$account);
		$map['del_flag']=array('eq',1);
		$data=M('sysuser')->field('dept_id')->where($map)->find();
		// dump($data['dept_id']);die();
		$isset=$data['dept_id'];
		// dump($isset);die();
		//判断如果是超级管理员
		if(is_null($isset)){
			$data2=0;
		}else{
			$data2=$data['dept_id'];
		}
		// dump($data);die();
		return $data2;
	}
	//根据学院id获取该学院学科信息
	function getcourse($dept_id){
		// dump($id);die();
		$map['college_id']=array('eq',$dept_id);
		$map['del_flag']=array('eq',1);
		$data=M('course')->field('id,name')->where($map)->select();
		return $data;
	}
	//根据学院id获取课程信息
	function getlession($college_id){
		$map['college_id']=array('eq',$college_id);
		$map['del_flag']=array('eq',1);
		$data=M('lession')->field('id,name')->where($map)->select();
		return $data;
	}
	//根据学院id获取学院名称
	function getcollege($id){
		if($id!=0){
			$map['id']=array('eq',$id);
		}
		$map['del_flag']=array('eq',1);
		$data=M('college')->field('id,name')->where($map)->select();
		// dump($data);die();
		return $data;
	}
	/**
	 * 添加日志的公共方法
	 * @Author   taolei
	 * @DateTime 2017-02-16
	 * @param    [array]     $log [传入的日志记录数组]
	 */
	/**
	 * 调用方法  addlog(__ACTION__);
	 */
	function addLog($handMethod){
	    $data = array(
	        "user_account" => getCurrentUser(),
	        "handlemethod" =>  $handMethod,
	        "createip"   => get_client_ip(),
	    );
	    D('Log')->addLog($data);
	}

	/**
	 * @function 获取对应字典字段的值和标签
	 * @Author   许加明
	 * @DateTime 2017-3-2 16:14:04
	 * @param mixed $type 标签的类型【数组】【字符串】 ,mixed $value 标签的值【数组】【字符串】
	 * @return array 一般取array[0]
	 */
	function dict($type='', $value='')
	{
		$data = null;
		$dict = M('Dict');
		if('' === $type && '' === $value){					//都为空时查询全部标签
			$data = $dict->where('del_flag=1')->select();
		}elseif($type!=='' && $value === ''){				//只有$type存在时查询符合的type标签
			$map['type'] = array('in',$type);
			$map['del_flag']=array('eq',1);
			$data = $dict->where($map)->select();
		}elseif($type!=='' && $value !== ''){				//都不为空时查询一种$type类型,且符合$value值的标签
			$map['type'] = $type;
			$map['value'] = array('in',$value);
			$data = $dict->where($map)->select();
		}else{

		}
		return $data;
	}

	/**
	 * @function 输出xls文件
	 * @Author   许加明
	 * @DateTime 2017-4-2 09:30:10
	 * @param
	 * 		$title string 输出的文件名
	 * 		$cellName  array 类似 array('A','B','C');
	 * 		$cellHeader array 类似 array('章节编号','所属学科','章节名称');
	 * 		$data='' array[1...i][array] 二维数组 数据类型一般为通过select()方法获得
	 * @waring 注意$cellName $cellHeader数组大小一致 $data 的列数也与 $cellName一致
	 * @return  execl文件
	 */
	function outputExcel($title,$cellName,$cellHeader,$data=''){
	//导入Excel
	Vendor("PHPExcel.PHPExcel");
	Vendor("PHPExcel.PHPExcel.IOFactory");
	$objExcel = new \PHPExcel();

//        //设置文件名
//        $title = '知识点导入模板';

	//设置基本信息
	$objExcel->getProperties()->setCreator("Chengdu University of Information Technology")
		->setLastModifiedBy("Chengdu University of Information Technology")
		->setTitle("Chengdu University of Information Technology")
		->setSubject("knowledge list")
		->setDescription("")
		->setKeywords("knowledge list")
		->setCategory("");

	$objSheet = $objExcel->getActiveSheet();


	//设置文件默认水平垂直居中
	$objSheet->getDefaultStyle()->getAlignment()
		->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER)
		->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

	//设置每列宽度
	foreach($cellName as $value){
		$objSheet->getColumnDimension($value)->setWidth(16);
	}



	$i = 0;
	//填充表头
	foreach($cellHeader as $value){
		$objSheet->setCellValue($cellName[$i].'1',$cellHeader[$i]);
		$i+=1;
	}

	//添加数据
	$j = 2;
	if($data != ''){
		foreach($data as $item => $value){
			$i2 = 0;
			foreach($value as $key => $value2){
				$objSheet->setCellValue($cellName[$i2].$j,$value2);
				$i2++;
			}
			$j++;
		}
	}

	$objWriter = new \PHPExcel_Writer_Excel2007($objExcel);
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
	header('Content-Disposition:attachment;filename='.$title.'.xlsx');
	header("Content-Transfer-Encoding:binary");
	//文件通过浏览器下载
	$objWriter->save('php://output');
        exit(); //跳转结束，否则文件会报错

}

	/**
	 * @function 从excel文件获取数据
	 * @Author   许加明
	 * @DateTime 2017-4-2 22:35:45
	 * @param
	 * 		$filename string 文件名称（包括路劲） 列如： './Uploads/student_import/2017-03-15/1489583713309.xls'
	 * 		$exts string 文件后缀 列如：xls
	 * 		$dataKey array 返回数据对应的键 如：array('id','name','dept_id','comment','del_flag');
	 * @return array[1...i][array]	与select()方法获得 其中一个元素 $key=>$value $key为$dataKey的元素
	 */
	function getDataFromUpExcel($filename,$exts,$dataKey){
	//定义列名
	$cellName = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
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

	$data = array();
	//从第二行开始取数据,将数据统一转化为String类型，便于处理
	for($i = 2;$i <= $maxRow;$i++){
		$temp = array();
		$j = 0;
		foreach($dataKey as $key){
			$temp[$key] = (String)$objPHPExcel->getActiveSheet()->getCell($cellName[$j] . $i)->getValue();
			$j++;
		}
		array_push($data,$temp);
	}

	return $data;
}

/**
 * @Function: 判断角色
 * @Author: xuanhao
 * @DateTime: ${DATE} ${TIME}
 * 角色判断：1--超级管理员；2--系统管理员；3--学院管理员；4--老师；
 * 如需增添角色，修改此处
 */
    function judgeRole() {
        $accInfo = session('accInfo');
        $roleNumber = $accInfo['role'];     //获取角色代号
        $role_auth = '';
        switch ($roleNumber) {
            case 1:
                $role_auth = 1;
                break;
            case 2:
                $role_auth = 2;
                break;
            case 3:
                $role_auth = 3;
                break;
            case 4:
                $role_auth = 4;
                break;
        }
        return $role_auth;

    }

