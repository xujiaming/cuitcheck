<?php 
namespace Home\Model;
use Home\Model\BaseModel;
class LogModel extends BaseModel {

	//日志添加
	public function addLog($data){
		if(!$this->create($data)) {
			return $this->getError();
		}else{
			if($this->add() === false) {
				return false;
			}else {
				return true;
			}
		}
	}
	/**
	 * 获取日志列表
	 * @Author   taolei
	 * @return   [array]     logList and pageData
	 */
	public function getLogList($requestPage,$endDate,$beginDate,$rows){
        if ($beginDate != '' && $endDate != ''){
            $map['create_date'] = array(array('egt', $beginDate), array('elt', $endDate));
        }
        $map['del_flag'] = array('eq', '1');
        $limit = ($requestPage-1)*$rows.','.$rows;   
        $total = $this->where($map)->count();
        $list = $this
            	->field('user_account as account,handlemethod as method,createip as ip,create_date')
            ->where($map)->order('id desc')
            ->limit($limit)->select();
        $pages = ($total % $rows === 0 ? intval(floor($total/$rows)) : intval(floor($total/$rows+1)));     //获取页数
        $data = array(
            "pages" => $pages,
            "list" => $list,
        );
        return $data;
	}
}
?>