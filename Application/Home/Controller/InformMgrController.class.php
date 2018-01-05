<?php
namespace Home\Controller;
use Common\Controller\HomeBaseController;
/**
 * 功能：通知管理控制器
 * 作者：luochao
 * 日期：2017/4/2
 */
class InformMgrController extends HomeBaseController {

	public function informList(){
		$requestPage = I('requestPage', 1);	//获取请求的页码数
		$beginDate = I('beginDate', '');	//获取发布时间
		$endDate = I('endDate', '');		//获取最大时间
		$rows = 10;							//每页展示的数据
		$collegekey=I('collegekey','');
		$targetkey=I('targetkey','');

		$data=D('inform')->getInformList($requestPage,$endDate,$beginDate,$rows,$targetkey,$collegekey);

		$data_college=D('courseclass')->getcollgelist();

		$this->assign('collgelst',$data_college);
		// p($data['list']);die();
		$this->assign('pages',$data['pages']);
		$this->assign('informlist',$data['list']);
		$this->assign('targetkey',$targetkey);

		$this->assign('requestPage',$requestPage);
		$this->assign('beginDate',$beginDate);

	    $this->display();
	}
	public function informPush(){
		$dept_id=D('inform')->getdeptid();
		// p($dept_id);die();
		$this->assign('deptid',$dept_id);
		$this->display();
	}
	public function informEdit(){
		$id = I('id');
	
         // p($data);die();
		$data = D('inform')->getinformId($id);
			// dump($data); die();
		$this->assign('informedit', $data);
		$this->display();
	}
	/**
	 * 功能：通知公告修改方法
 	 * 作者：罗钞
 	 * 日期：2017/4/7
	 */
	public function updateinform(){
		if (!IS_POST){
			$data = array('success'=>false, 'msg'=>'提交方式不正确');
		}else{
        // p($_POST['filename']);die();
         	$data = D('inform')->updateinform();
		
		}

		$this->ajaxReturn($data, 'json');
	}
	/**
	 * 功能：通知公告附件上传
 	 * 作者：罗钞
 	 * 日期：2017/4/11
	 */
	public function attachment(){
		$upload = new \Think\Upload();
		//设置上传文件大小，此处为 3M
		$upload->maxSize = 3145728;
		//上传文件类型
		$upload->exts = array('xlsx','xls','doc','docx','txt');
		//设置时区为中国
		date_default_timezone_set('prc');
		$time = time();
		// p($upload);die();
		$tempName = $time."".rand(1,10000);
		//设置文件名保存为时间加随机数
		$upload->saveName = $tempName;
		//设置文件保存路径
		$upload->savePath = 'fujian_file/';
		$info   =   $upload->uploadOne($_FILES['filename']);
		$file_name=$_FILES['filename']['name'];
        if (!$info) {// 上传错误提示错误信息
			$result['code']=1;
      	    $result['msg']="上传附件失败，请重新上传！";
        } else {// 上传成功
            $filename=$upload->rootPath.$info['savepath'].$info['savename']; 
            // p($filename);die();
            $result['msg']="上传附件成功";
            $result['name']=array('name'=>$file_name);
            $result['data'] = array('src' => $filename);
        }
        return $this->ajaxReturn($result, 'json');
	}
	/**
	 * 功能：通知公告附件下载
 	 * 作者：罗钞
 	 * 日期：2017/4/15
	 */
	public function informdown(){
		$id=I('id');
		$map['id'] =$id;
		$result= M('inform')->field('file_url,file_name')->where($map)->find();
		// p($result['file_name']);die();
		iconv("UTF-8","GB2312",$result['file_name']); //将utf-8编码转换为gb2312编码
		$http = new \Org\Net\Http();
		$http->download($result['file_url'],$result['file_name']);
		// import('ORG.Net.Http');
  		// Http::download($result['file_url'],$result['file_name']);
	}
	public function informAdd(){
		// p($_POST);die();
		if (!IS_POST){
			$data = array('success'=>false, 'msg'=>'提交方式不正确');
		}else{
		    $data = D('inform')->add_inform();	     
		}
		$this->ajaxReturn($data, 'json');
	}
	/**
	 * 功能：通知公告删除
 	 * 作者：罗钞
 	 * 日期：2017/4/7
	 */
	public function informdelete(){
		$id=I('id');
		$data=D('inform')->informdelete($id);
		$this->ajaxReturn($data,'json');
	}
	public function informShow(){
		$id=I('id');
		$data=D('inform')->informPreShow($id);
		$this->assign('informShow',$data);
		$this->display();
	}
}
?>
