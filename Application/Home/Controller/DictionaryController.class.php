<?php
namespace Home\Controller;
use Common\Controller\HomeBaseController;
/**
 * 功能：系统管理控制器
 * 作者：pujinyao
 * 日期：2017/3/17
 */
class DictionaryController extends HomeBaseController {


	/**
	 * 展示列表信息
	 * @return 无
	 */
	public function DictionaryList(){

	 	$requestPage = I('requestPage', 1);	//获取请求的页码数
        $beginDate = I('beginDate', '');	//获取开始时间
        $endDate = I('endDate', '');		//获取结束时间
        $keyword = I('keyword', '');		//获取查询关键词
        $rows = 10;		//每页展示的数据

		$data = D('Dict')->getAllList($requestPage,$beginDate,$endDate,$keyword,$rows);

		// exit(var_dump($data));
		$this->assign('pages',$data['pages']);
		$this->assign('DictList',$data['list']);
		$this->assign('requestPage', $requestPage);
        $this->assign('beginDate', $beginDate);
        $this->assign('endDate', $endDate);
        $this->assign('keyword', $keyword);
		$this->display();
	}


	/**
	 * 打开编辑页面
	 * @return [type]
	 */
	public function editDict() {
		$id = I('id');
		$type = I('type');
		$data = D('Dict')->getInfoById($id);
		$this->assign('info', $data);
		$this->assign('type', $type);
		$this->display();
	}

	/**
	 * 添加信息
	 */
	public 	function addDict() {
		if(!IS_POST) {
			$data =  array('success'=>false, 'msg'=>'提交方式不正确');
		}else{
            $data = D('Dict')->addInfo($_POST);
        }
        $this->ajaxReturn($data,'json');
	}


	/**
	 * 更新字典信息
	 * @return [type]
	 */
	public function updateDict() {
		if(!IS_POST) {
			$data =  array('success'=>false, 'msg'=>'提交方式不正确');
		}else{
            $data = D('Dict')->updateInfo($_POST);
        }
        $this->ajaxReturn($data,'json');
	}


	/**
	 *删除该信息
	 * { function_description }
	 */
	public function deleteDict() {
		if (!IS_POST){
            $data = array('success'=>false, 'msg'=>'提交方式不正确');
        }else{
            $data = D('Dict')->deleteById();
        }

        $this->ajaxReturn($data, 'json');
	}

}
