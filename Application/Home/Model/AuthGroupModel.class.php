<?php
namespace Home\Model;
use Think\Model;

/**
 * 功能：对auth_group表的操作
 * 作者：罗钞
 * 日期：2017-7-18
 */
class AuthGroupModel extends Model{

    /**
     * 自动验证设置
     */
    protected $_validate = array(
        array('title','',array('success'=>false,'msg'=>'用户组已经存在!'),1,'unique',3),
    ); 

    public function getAllList($requestPage,$keyword,$rows){

        if ($keyword != ''){
            $map['kh_auth_group.title'] = array('like', '%'.$keyword.'%');
        }
         $limit = ($requestPage-1)*$rows.','.$rows;

        $total = M('auth_group')       //获取全部符合条件的数据条数
        ->field('id, title, status,rules')
        ->where($map)->count();

        $list = M('auth_group') //多表联合查询
        ->field('id, title,status, rules')
        ->where($map)
        ->order('id asc')
        ->limit($limit)->select();

        $pages = 0;
        if ($total%$rows == 0){
            $pages = $total/$rows;
        }else{
            $pages = intval($total/$rows + 1);
        }

        $data = array(
            "pages" => $pages,
            "list" => $list,
        );

        return $data;
    }
	public function addgroup(){
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
    public function updateGroup(){
      if (!$this->create()){
            return $this->getError();
        }else{
            $this->save();
            return $this->getret(true);
        }
    }
    public function deleteGroup($id){
        $map['id']=array('eq',$id);
        $data=M('auth_group')->where($map)->delete();
        return $this->getret(true);
    }
}