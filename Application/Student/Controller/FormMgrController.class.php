<?php
namespace Student\Controller;
use Think\Controller;
use Common\Controller\StudentBaseController;

/*
罗钞
2017.7.16
功能:公告列表以及公告详情
 **/
class FormMgrController extends StudentBaseController {
    public function gonggao(){
        $inform = M('inform'); // 实例化User对象
        $map['kh_inform.del_flag']=array('eq','1');
        $map['sendtype']=array('neq' , '2' );
        $count      = $inform->where($map)->count();// 查询满足要求的总记录数
        $Page       = new \Think\Page($count,5);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $Page->setConfig('header', '<li class="rows">&nbsp;&nbsp;&nbsp; 第<b>%NOW_PAGE%</b>页/共<b>%TOTAL_PAGE%</b>页</li>');  
        $Page->setConfig('prev', '上一页');  
        $Page->setConfig('next', '下一页');  
        $Page->setConfig('last', '末页');  
        $Page->setConfig('first', '首页');  
        $Page->setConfig('theme', '%FIRST%%UP_PAGE%%LINK_PAGE%%DOWN_PAGE%%END%%HEADER%');  
        $Page->lastSuffix = false;//最后一页不显示为总页数  
        $show       = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $inform->where($map)->order('kh_inform.greatedate desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('list',$list);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
        $this->display(); // 输出模板
    }
    public function testInform(){
    	$data=D('inform')->getInformList();
    	$this->assign('informlist',$data);
    	$this->display();
    }
    public function gg_detail(){
        $id=I('id');
        $data=D('inform')->informPreShow($id);
        // dump($data);die();
        $this->assign('inform',$data);
        $this->display();
    }
    public function formdown(){
        $id=I('id');
        // dump($id);die();
        $map['id'] =$id;
        $result= M('inform')->field('file_url,file_name')->where($map)->find();
        // p($result['file_name']);die();
        iconv("UTF-8","GB2312",$result['file_name']); //将utf-8编码转换为gb2312编码
        $http = new \Org\Net\Http();
        $http->download($result['file_url'],$result['file_name']);
    }
}