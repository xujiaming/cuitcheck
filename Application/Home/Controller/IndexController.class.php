<?php
namespace Home\Controller;
use Common\Controller\HomeBaseController;

/**
 * 功能：管理员端的后台主页控制器
 * 作者：taolei
 * 日期：2017/2/5
 */
class IndexController extends HomeBaseController {

	/**
	 * 后台主页的模板渲染
	 * @Author   taolei
	 * @DateTime 2017-02-17
	 * @return   null     null
	 */
    public function index(){
        $accInfo = session('accInfo');
        $this->assign('accInfo', $accInfo);
        $this->display();
    }

    /**
     * 后台页面中控制台的模板渲染
     * @Author   taolei
     * @DateTime 2017-02-17
     * @return   null     null
     */
    public function main(){
        $teacher_count = M('sysuser')->where(array('role'=>4,'del_flag'=>1))->count();      //老师数量
        $stu_account = M('student')->where(array('del_flag'=>1))->count();              //通知公告
        $inform = D('Inform')->maingetInfo();
        $this->assign('teacher_count', $teacher_count);
        $this->assign('stu_count', $stu_account);
        $this->assign('informList', $inform);
    	$this->display();
    }

    /**
     * 获取侧边菜单的json数组
     * @Author   taolei
     * @DateTime 2017-02-17
     * @return   json     菜单的json数组
     */
    public function GetMenu(){

        $this->ajaxReturn($data,'json');
    }


	public function GetSonMenu($id){
	    $SonMenu = M('Menu')->field('id,name,url,sort,icon')->where(array('parent_id'=>$id))->order('sort asc')->select();
		return $SonMenu;
	}

	public function GetMenulist(){
		$lists = array();
		//获取所有的顶级的菜单
		$topmenus = M('Menu')->field('id,name,url,sort,icon')->where(array('parent_id'=>0,'del_flag'=>1))->order('sort asc')->select();
		// dump($topmenus);die();
		$info=D('Sysuser')->getMyselfInfo($_SESSION['account']);
		// dump($topmenus);die();
		for($i=0;$i<sizeof($topmenus);++$i){
			$lists[$i]['title'] = $topmenus[$i]['name'];
			$lists[$i]['icon'] = $topmenus[$i]['icon'];
			$lists[$i]['url'] = $topmenus[$i]['url'];
			$lists[$i]['spread'] = false;
			$SonMenu = $this->GetSonMenu($topmenus[$i]['id']);
			for($j=0;$j<sizeof($SonMenu);++$j){
				$lists[$i]['children'][$j]['title'] = $SonMenu[$j]['name'];
				$lists[$i]['children'][$j]['icon'] = $SonMenu[$j]['icon'];
				$lists[$i]['children'][$j]['href'] = $SonMenu[$j]['url'];
			}
		}
		// dump($lists);die();
		//根据权限判断是否显示菜单
		$auth=new \Think\Auth();
		foreach ($lists as $k => $v) {
			if (!$auth->check($v['url'],$info['id'])) {
				unset($lists[$k]);
			}else{
				foreach ($v['children'] as $m => $n) {
					if(!$auth->check($n['href'],$info['id'])){
						unset($lists[$k]['children'][$m]);
					}
				}
			}
		}
	// dump($lists);die();
		foreach ($lists as $key => $value) {
			if (empty($value['children'])) {
				unset($lists[$key]);
			}
		}

		$newArr = array();

		foreach($lists as $val) {
		    $tmp_array = $val;
		        $tmp_array['children'] = array_values($val['children']);
		    $newArr[] = $tmp_array;
		    unset($tmp_array);
		}

		// dump($newArr);die();
		$this->ajaxreturn($newArr,'json');
		 //$this->ajaxreturn($lists,'json');
	}


	/**
	 * 测试接口
	 */
	

	public function test(){
	    $info = M('Menu')->select();
	    p($info);
	}
}