<?php
namespace Home\Controller;
use Common\Controller\HomeBaseController;
/**
 * 功能：系统管理控制器
 * 作者：taolei
 * 日期：2017/2/17
 */
class PersonCenterController extends HomeBaseController {

	public function index(){
	    $this->display();
	}

    /**
     * @function 获得资料修改页面
     * @Author   许加明
     * @DateTime 2017-2-24 15:22:18
     */
	public function Updateinfo(){
		$account = session('account');
		$sysUser = D('Sysuser')->getMyselfInfo($account);   //获取信息

        /*获取角色标签*/
        $roleSelect = dict('sysRole',$sysUser['role'])[0];
        /*获取部门标签*/
//        $depts = dict('dept',$sysUser['dept_id'])[0];
        $depts = D('College')->getCollegeIdAndNameList($sysUser['dept_id'])[0];

        $sysUser['role'] = $roleSelect['label'];
        $sysUser['dept_id'] = $depts['name'];

	    $this->assign('info',$sysUser)->display();
	}

	/**
	 * @function 用户修改信息
	 * @Author   许加明
	 * @DateTime 2017-2-26 00:32:04
	 * @param    无
	 * @return   [json]
	 */
    public function userSaveInfo(){
        $msg = D('Sysuser')->insertMyselfInfo();
        return $this->ajaxReturn($msg,'json');
    }

	/**
	 * @function 用户修改密码
	 * @Author   许加明
	 * @DateTime 2017-2-26 00:32:56
	 * @param    无
	 * @return   [json]
	 */
	public function userSavePass(){
		$msg = D('Sysuser')->fixPass();
		return $this->ajaxReturn($msg,'json');
	}

	/**
	 * @function 用户上传头像
	 * @Author   许加明
	 * @DateTime 2017-2-26 00:33:48
	 * @param    无
	 * @return   [json]
	 */
	public function updatePhoto()
    {
        $upload = new \Think\Upload();// 实例化上传类
        $upload->maxSize = 1048576; //最大1m
        $upload->exts = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->rootPath = './Uploads/UserPhoto/'; // 设置附件上传根目录
        $upload->savePath = ''; // 设置附件上传（子）目录
        $upload->saveName = 'sysuser_' . D('Sysuser')->getId();//'Sysuser_'+id
        $upload->saveExt = 'jpeg';//文件上传后缀
        $upload->replace = true; //直接覆盖
        $upload->autoSub = false;//不开启子目录

        $info = $upload->uploadOne($_FILES['photo']);

        if (!$info) {// 上传错误提示错误信息
            $arr['code'] = 0;
            $arr['msg'] = $upload->getError();
            $arr['data'] = array('src' => '');
        } else {// 上传成功
            $path = '/cuitcheck/Uploads/UserPhoto/'.$info['savepath'] . $info['savename'];
            if (D('Sysuser')->savePhoto($path)) {
                if (D('Sysuser')->savePhoto($path)) {
                    $arr['code'] = 1;
                    $arr['msg'] = '头像更改成功！';
                    $arr['data'] = array('src' => $path);
                } else {
                    $arr['code'] = 0;
                    $arr['msg'] = '头像保存异常';
                    $arr['data'] = array('src' => '');
                }
            }
        }
        return $this->ajaxReturn($arr, 'json');
    }

}
