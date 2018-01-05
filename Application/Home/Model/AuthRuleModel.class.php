<?php
namespace Home\Model;
use Think\Model;

/**
 * 功能：对auth_rule表的操作
 * 作者：罗钞
 * 日期：2017-7-20
 */
class AuthRuleModel extends Model{

    protected $_validate = array(
        // array('title','',array('success'=>false,'msg'=>'权限名已经存在!'),1,'unique',3),
        // array('name','',array('success'=>false,'msg'=>'权限已经存在!'),1,'unique',3),
    ); 
    
    public function GetSontree($id){
        $SonMenu = M('Auth_rule')->field('id,name,title,status')->where(array('pid'=>$id))->select();
        return $SonMenu;
    } 
    public function getTreeData(){
        $lists = array();
        //获取所有的顶级的操作
        $topmenus = M('Auth_rule')->field('id,name,title,status')->where(array('pid'=>0,'status'=>1))->select();
        for($i=0;$i<sizeof($topmenus);++$i){
            $lists[$i]['id'] = $topmenus[$i]['id'];
            $lists[$i]['name'] = $topmenus[$i]['name'];
            $lists[$i]['title'] = $topmenus[$i]['title'];
            // $lists[$i]['spread'] = false;
            $SonMenu = $this->GetSontree($topmenus[$i]['id']);
            for($j=0;$j<sizeof($SonMenu);++$j){
                $lists[$i]['children'][$j]['id'] = $SonMenu[$j]['id'];
                $lists[$i]['children'][$j]['name'] = $SonMenu[$j]['name'];
                $lists[$i]['children'][$j]['title'] = $SonMenu[$j]['title'];
            }
        }
        // dump($lists);die();
        return $lists;
    }
 public function getData(){
        $lists = array();
        //获取所有的顶级的操作
        $topmenus = M('Auth_rule')->field('id,name,title,status')->where(array('pid'=>0))->select();
        for($i=0;$i<sizeof($topmenus);++$i){
            $lists[$i]['id'] = $topmenus[$i]['id'];
            $lists[$i]['name'] = $topmenus[$i]['name'];
            $lists[$i]['title'] = $topmenus[$i]['title'];
            $lists[$i]['status'] = $topmenus[$i]['status'];
            // $lists[$i]['spread'] = false;
            $SonMenu = $this->GetSontree($topmenus[$i]['id']);
            for($j=0;$j<sizeof($SonMenu);++$j){
                $lists[$i]['children'][$j]['id'] = $SonMenu[$j]['id'];
                $lists[$i]['children'][$j]['name'] = $SonMenu[$j]['name'];
                $lists[$i]['children'][$j]['title'] = $SonMenu[$j]['title'];
                $lists[$i]['children'][$j]['status'] = $SonMenu[$j]['status'];
            }
        }
        // dump($lists);die();
        return $lists;
    }
    public function getAllList($keyword){

        if ($keyword != ''){
            $map['kh_auth_rule.title'] = array('like', '%'.$keyword.'%');
        }
         // dump($limit);die();
        $total = M('Auth_rule')       //获取全部符合条件的数据条数
        ->field('id, title, status,name')
        ->where($map)->count();

        $list = $this->getData();

        $data = array(
            "pages" => $pages,
            "list" => $list,
        );

        return $data;
    }
    public function addrule(){
        if (!$this->create()){
            return $this->getError();
        }else{
            $this->add();
            return $this->getret(true);
        }
    }

    private function getret($status){
        $arr['success'] = $status;
        return $arr;
    }
    public function updaterule(){
      if (!$this->create()){
            return $this->getError();
        }else{
            $this->save();
            return $this->getret(true);
        }
    }
    public function deleterule($id){
        $map['id']=array('eq',$id);
        $data=M('Auth_rule')->where($map)->delete();
        return $this->getret(true);
    }
    public function addsonrule(){
        if (!$this->create()){
            return $this->getError();
        }else{
             $this->add();
            return $this->getret(true);
        }
    }

}