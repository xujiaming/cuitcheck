<?php
namespace Home\Controller;
use Common\Controller\HomeBaseController;

class SysuserGroupController extends HomeBaseController{
    /**
     * @function 系统用户组管理        
     * @Author   罗钞
     * @DateTime 2017-7-18 
     */
    //******************用户组*******************
    public function sysGroupList(){
        $requestPage = I('requestPage', 1); //获取请求的页码数
        $keyword = I('keyword', '');        //获取查询关键词
        $rows = 10;                 
        $data = D('authGroup')->getAllList($requestPage,$keyword, $rows);


        $this->assign('pages', $data['pages']);
        $this->assign('grouplst', $data['list']);
        $this->assign('requestPage', $requestPage);
        $this->assign('keyword', $keyword);


        $this->display();
    }
    
    public function editSysGroup(){

        $type=I('type');
        // dump($type);die();
        $id = I('id');
        if ($type=='updategroup') {
            $map['id']=array('eq',$id);
            $data=M('auth_group')->where($map)->find();
            $this->assign('groupedit',$data);
        }
        // dump($type);die();
        $this->assign('type',$type);
        $this->display();


    }

    public function addSysGroup(){
        if (!IS_POST){
            $data = array('success'=>false, 'msg'=>'提交方式不正确');
        }else{
            // dump($_POST);die();
            $data=D('authGroup')->addgroup();
        }

        $this->ajaxReturn($data, 'json');
    }

    public function updateSysGroup(){
        if (!IS_POST){
            $data = array('success'=>false, 'msg'=>'提交方式不正确');
        }else{
            $data = D('authGroup')->updateGroup();
        }

        $this->ajaxReturn($data, 'json');
    }
    public function deleteSysGroup(){
        if (!IS_POST){
            $data = array('success'=>false, 'msg'=>'提交方式不正确');
        }else{
            $id=I('id');
            // dump($id);die();
            $data = D('authGroup')->deleteGroup($id);
        }

        $this->ajaxReturn($data, 'json');
    }
    //******************权限-用户组*******************
    public function rule_group(){
        $id=I('id');
         $group_data=M('Auth_group')->where(array('id'=>$id))->find();
         $group_data['rules']=explode(',', $group_data['rules']);
         $rule_data=D('AuthRule')->getTreeData();
         // dump($rule_data);die();
         // dump($group_data);die();
        $assign=array(
            'group_data'=>$group_data,
            'rule_data'=>$rule_data
        );

        // dump($assign);die();
        $this->assign($assign);
        $this->display();
    }
    public function changerule(){
        $temp=$_POST;
        $data['id']=$temp['id'];
        unset($temp['id']);
        $data['rules']=implode(',',$temp);
        // dump($data['rules']);die();
        $group=M('Auth_group');
          if (false!==$group->save($data)) {
                    $res=array('status' => success);
                }else{
                    $res=array('status' => false, 'msg'=>'分配失败');
         }
          $this->ajaxReturn($res, 'json');
    }
}