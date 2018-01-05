<?php
namespace Home\Controller;
use Common\Controller\HomeBaseController;
/**
 * 功能：行课班级管理控制器
 * 作者：罗钞
 * 日期：2017/3/3
 */
class CourseclassMgrController extends HomeBaseController {

	public function CourseclassList(){
		$requestPage = I('requestPage', 1);	//获取请求的页码数
		$beginDate = I('beginDate', '');	//获取开始时间
		$endDate = I('endDate', '');		//获取结束时间
		$keyword = I('keyword', '');		//获取查询关键词
		$rows = 10;							//每页展示的数据
		$gradekey=I('gradekey','');
		date_default_timezone_set('prc');
		$time = time();
		// dump($time);die();

		$data = D('courseclass')->getAllList($requestPage, $beginDate, $endDate, $keyword, $rows,$gradekey);
		$data_grade=D('courseclass')->getgradelist();
		// dump($data['list']);die();
		$this->assign('ptime',$time);
		$this->assign('gradelst',$data_grade);
		$this->assign('courseclasslst', $data['list']);
		$this->assign('pages', $data['pages']);
		$this->assign('requestPage', $requestPage);
		$this->assign('beginDate', $beginDate);
		$this->assign('endDate', $endDate);
		$this->assign('keyword', $keyword);
		$this->assign('gradekey', $gradekey);

		$this->display();
	}
	public function editcourseclass(){
		$type = I('type');
		if ($type == 'update'){
			$id = I('id');
			$data = D('courseclass')->getcourseClassId($id);
			// dump($data); die();
			$this->assign('courseclass', $data);
		}
		//获取老师的id
		$map['role']=array('eq',4);
		$map['del_flag']=array('eq',1);
		$map['status']=array('eq',1);
		$teacher=M('sysuser')->where($map)->select();
		// dump($teacher);die();
		$data_college=D('courseclass')->getcollgelist();
		$data_grade=D('courseclass')->getgradelist();

		$this->assign('collegelst',$data_college);
		$this->assign('gradelst',$data_grade);
		$this->assign('type', $type);
		$this->assign('teacherlist',$teacher);
		$this->display('editcourseclass');
	}
	/**
	 * 功能：行课班级添加方法
 	 * 作者：罗钞
 	 * 日期：2017/3/3
	 */
	public function add_courseclass(){
		if (!IS_POST){
			$data = array('success'=>false, 'msg'=>'提交方式不正确');
		}else{
			$data = D('courseclass')->add_courseclass();
		}

		$this->ajaxReturn($data, 'json');
	}
	/**
	 * 功能：根据学院id获取该学院下的课程信息
 	 * 作者：罗钞
 	 * 日期：2017/10/11
	 */
	public function getlession(){
		$college_id=I('college_id');
		// dump($college_id);die();
		$data['data']=getlession($college_id);
		// dump($data);die();
		$this->ajaxReturn($data,'json');
	}
	/**
	 * 功能：行政班级修改方法
 	 * 作者：罗钞
 	 * 日期：2017/3/3
	 */
	public function updatecourseclass(){

		if (!IS_POST){
			$data = array('success'=>false, 'msg'=>'提交方式不正确');
		}else{
			$data = D('courseclass')->updatecourseclass();
		}

		$this->ajaxReturn($data, 'json');
	}
	/**
	 * 功能：行课班级删除方法
 	 * 作者：罗钞
 	 * 日期：2017/3/3
	 */
	public function deletecourseclass(){
		$id = I('id');

		$data = D('courseclass')->daletecourseclass($id);

		$this->ajaxReturn($data, 'json');
	}
	/**
	 * 行课班级详细查看
	 * 作者:罗钞
	 * 日期:2017/3/15
	 */
	public function courseDetail(){
		$courseid=I('id');
		$keyword=I('keyword');
		$requestPage = I('requestPage', 1);
		$rows = 1;		
		// dump($keyword);die();
		$data=D('courseclass')->getallcourseclass($courseid,$keyword);
		// p($courseid);die();
		$this->assign('courseDetail', $data);
		$this->assign('courseid',$courseid);
		$this->display();
	}
	/**
	 * 导入页面
	 * 作者:罗钞
	 * 日期:2017/3/15
	 */
	public function courseLeadingIn(){
		$courseid=I('id');
		$this->assign('courseid',$courseid);
		$this->display();
	}
	/**
	 * 模板文件生成
	 * 作者:罗钞
	 * 日期:2017/3/17
	 */
	public function exportCourse(){
		Vendor("PHPExcel.PHPExcel");
	 	Vendor("PHPExcel.PHPExcel.IOFactory");  
	 	$excel = new \PHPExcel();
		$objSheet=$excel->getActiveSheet();
		$objSheet->getDefaultStyle()->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER)->
		setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);//设置excel文件默认水平垂直方向居中
		$excel->getActiveSheet()->getColumnDimension('A')->setWidth(20); 
		$letter = array('A');
		$tableheader = array('学号(*)');
		//填充表头信息
		for($i = 0;$i < sizeof($tableheader);$i++) {
			$excel->getActiveSheet()->setCellValue("$letter[$i]1","$tableheader[$i]");
		}
		$title = '行课班级学生导入模板.xls';
		$write = new \PHPExcel_Writer_Excel5($excel);
		ob_end_clean();
		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
		header("Content-Type:application/force-download");
		header("Content-Type:application/vnd.ms-execl");
		header("Content-Type:application/octet-stream");
		header("Content-Type:application/download");;
		header('Content-Disposition:attachment;filename="'.$title.'"');
		header("Content-Transfer-Encoding:binary");
		$write->save('php://output');
	}
	/**
	 * 文件导入的处理
	 * 作者:罗钞
	 * 日期:2017/3/17
	 */
	public function importCourse(){
				$id=I('courseid');
			// $id=p($_GET['courseid']);
			// dump($id);die();
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
			$upload->savePath = '/course_student/';
			$info   =   $upload->uploadOne($_FILES['up_btn']);
			$result=array();
			// dump($info);die();
			 if(!$info) {// 上传错误提示错误信息
      			  $result['code']=1;
      			  $result['msg']="上传失败，请参考导入注意事项。";
   			 }else{// 上传成功 获取上传文件信息
				$file_name=$upload->rootPath.$info['savepath'].$info['savename']; //excel文件导入后的操作$info['savepath'].$info['savename']
				$exts = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));//解析出文件后缀名
				// dump($file_name);die();
				$result['code'] = 2;//设置code为2表示导入成功
				$temp_ans= $this->judgeCourse($file_name,$exts,$id);//进一步判断出错原因
				// $str=array();
				if(sizeof($temp_ans)==0){
					$result['msg']="全部导入成功";
				}
				else{
					for($i=0;$i<sizeof($temp_ans);++$i){
						$result['msg'].=$temp_ans[$i]['msg'].'</br>';
						// array_push($result['msg'], $temp_ans[$i]);
					}
				}
    		}
    		$this->ajaxreturn($result,'json');
	}
	/**
	 * 
	 * 判断导入文件数据的正确性并存入数据库
	 * 作者:罗钞
	 * 日期:2017/3/17
	 */
	public function judgeCourse($file_name,$exts,$id){
		//引入phpexcel类
	    Vendor("PHPExcel.PHPExcel");
	    // Vendor("PHPExcel.PHPExcel.IOFactory");  
	    //判断导入的excel文件格式
	   	if ($exts == 'xlsx') {
	   		$objReader =\PHPExcel_IOFactory::createReader('Excel2007');
	   	}else if($exts == 'xls'){
	   		$objReader =\PHPExcel_IOFactory::createReader('Excel5');
	   	}
	    $objPHPExcel =$objReader->load($file_name, $encode = 'utf-8');
	   	$sheet =$objPHPExcel->getSheet(0);
	   	//获取总列数和总行数
	   	$maxRow = $sheet->getHighestRow();
	   	$maxColumn =$sheet->getHighestColumn();
	   	// dump($maxRow);die();
	   	$error  = array();
	   	for($i = 2; $i<=$maxRow; $i++){
	   		$account = 	(String)$objPHPExcel->getActiveSheet()->getCell("A" . $i)->getValue();
	   		// dump($account);die();
	   		if($account==''){
	   			$tip['msg']='第'.$i.'行为空!';
	   			array_push($error,$tip);
	   			// $error[$i]=$tip['msg'];
	   			continue;
	   		}
	   		// dump($error); die();	
	   		$isacount=$this->isacount($account);
	   		if(!$isacount){
	   			$tip['msg']='第'.$i.'行学号不存在或学号错误!';
	   			array_push($error,$tip);
	   			continue;
	   		}
	   		if($isacount){
	   			// 实例化User模型
				$User = M('class_student');
				// 创建数据后写入到数据库
				$data['account'] = $account;
				$data['courseclass_id'] = $id;
				//如果之前导入有一项或者两项错误则判断之前成功导入的是否存在
				$isExist=$User->where($data)->find();
				// p($isExist);die();
				if(sizeof($isExist)==0){
					$User->data($data)->add();
				}
				continue;
	   		}
	   	}
	   	// dump($error); die();
	   	  return $error;
	}
	//判断学号是否为空
	public function isacount($account){
		 $num = M('student')->where(array('account'=>$account))->count(); 
	    return $num==0?false:true;
	}
	public function corsetest(){
		// p($time);die();
		strtotime($vo['start_time']);
		$courseclassid=I('corseclassid');
		$form['courserclass_id']=array('eq',$courseclassid);
		$form['kh_paper_courserclass.del_flag']=array('eq','1');
		$college_id=I('college_id');
		// dump($college_id);die();
		$lession_id=I('lession_id');
		// dump($lession_id);die();
		$oldtest=M('paper_courserclass')
		->join('
				LEFT join kh_testpaper ON kh_paper_courserclass.testpaper_id = kh_testpaper.id 
			')
		->field('testpaper_id,start_time, kh_testpaper.name as paper_name,end_time,courserclass_id')
		->where($form)
		->order('start_time desc')
		->select();
		//获取已经考试，未开始考试的信息
		$nowtest=array();
		$pasttest=array();
		date_default_timezone_set('prc');
		$time = time();
		$k=0;
		for($i=0; $i<sizeof($oldtest); $i++){
			$stime=strtotime($oldtest[$i]['start_time']);
			$etime=strtotime($oldtest[$i]['end_time']);
			if ($time<$etime) {
					array_push($nowtest, $oldtest[$i]);
			}else{
				$k++;
				if($k<4){
					array_push($pasttest, $oldtest[$i]);
				}
			}
		}
		// p($nowtest);die();
		// p($pasttest);die();
		// p($oldtest);die();
		$tests['testpaper_id']=array_column($oldtest,'testpaper_id');
		// p($tests['testpaper_id']);die();
		$type=1;
		$map['college_id']=array('eq',$college_id);
		$map['lession_id']=array('eq',$lession_id);
		$map['type_id']=array('eq',$type);
		if(sizeof($tests['testpaper_id'])!=0){
			$map['id']=array('not in',$tests['testpaper_id']);
		}
        $data = M('Testpaper')->where($map)->select();
        // dump($data);die();
        if (sizeof($pasttest)==0) {
        	$ber=1;
        }
        if(sizeof($data)==0){
        	$ber2=1;
        }
        if (sizeof($nowtest)==0) {
        	$ber3=1;
        }
        $this->assign('ber',$ber);
        $this->assign('ber2',$ber2);
        $this->assign('ber3',$ber3);
        $this->assign('papersList', $data);
        $this->assign('oldtest', $pasttest);
        $this->assign('nowtest', $nowtest);
        $this->assign('courseclassid', $courseclassid);
		$this->display();
	}
	public function coursepaper(){
		if (!IS_POST){
			$data = array('success'=>false, 'msg'=>'提交方式不正确');
		}else{
			// dump($_POST);die();
			$data = D('PaperCourserclass')->addpaper();
		}
		$this->ajaxReturn($data, 'json');
	}
	public function istest(){
		$id=I('id');
		$start_time=I('time');
		date_default_timezone_set('prc');
		$time = time();
		$stime=strtotime($start_time);
		if($time>$stime){
			$data = array('success'=>false, 'msg'=>'考试正在进行，无法操作试卷哦！');
		}else{
			$data['success']=true;
		}
				// p($data);die();
		$this->ajaxReturn($data, 'json');
	}
	public function edittest(){
		$id=I('id');
		$courseclassid=I('courseclassid');
		$map['testpaper_id']=array('eq',$id);
		$map['del_flag']=array('eq','1');
		$map['courserclass_id']=array('eq',$courseclassid);
		$testinfo=M('paper_courserclass')->field('testpaper_id,start_time,end_time,courserclass_id')->where($map)->find();
		// p($testinfo);die();
		$this->assign('testinfo',$testinfo);
		$this->display();

	}
	//更新考试时间
	public function updatetest(){
		if (!IS_POST){
			$data = array('success'=>false, 'msg'=>'提交方式不正确');
		}else{
			$paper=M('paper_courserclass');
			$map['testpaper_id']=I('testpaper_id');
			$savedata['start_time']=I('start_time');
			$savedata['end_time']=I('end_time');
			$map['courserclass_id']=I('courserclass_id');
			// p($savedata);die();
			 if(false!==$paper->where($map)->save($savedata)){
                    $data['success']=true;
               }else{
                    $data = array('success'=>false, 'msg'=>'修改失败!');
              }
		}

		$this->ajaxReturn($data, 'json');
	}
	//删除试卷
	public function deletetest(){
		if (!IS_POST){
			$data = array('success'=>false, 'msg'=>'提交方式不正确');
		}else{
		$paper=M('paper_courserclass');
		$map['testpaper_id']=I('id');
		$map['courserclass_id']=I('courseclassid');
		$savedata['del_flag']='0';
		$paper->where($map)->save($savedata);
		$data['success']=true;
		}
		$this->ajaxReturn($data, 'json');
	}
	//添加单个学生
	public function addsinge(){
		$courseclassid=I('id');
		// p($courseclassid);die();
		$this->assign('courseclassid',$courseclassid);
		
		$this->display();
	}
	public function updatesinge(){
		$savedata['courseclass_id']=I('course_id');
		$account=I('account');
		$isacount=$this->isacount($account);
		if(!$isacount){
			$data=array('success'=>false,'msg'=>'该学生不存在!');
	   	}else{
	   		$savedata['account']=I('account');
			$singe = M("class_student"); 
			$isExist=$singe->where($savedata)->find();
			// p($isExist);die();
			if (sizeof($isExist)!=0) {
				$data=array('success'=>false,'msg'=>'该班已有该学生');	
			}else{
				if(false!==$singe->data($savedata)->add()){
					$data['success']=true;
				}else{
					$data=array('success'=>false,'msg'=>'添加失败！');
				}	
			}
	   	}
		// p($account);die();
		$this->ajaxReturn($data, 'json');
	}
	//删除学生
	public function delstu(){
		$map['account']=I('id');
		$map['courseclass_id']=I('courseid');
		$User = M("class_student");
		if (false!==$User->where($map)->delete()) {
			$data['success']=true;
		}else{
			$data=array('success'=>false,'msg'=>'删除失败！');
		}
		$this->ajaxReturn($data, 'json');
	}
}
?>