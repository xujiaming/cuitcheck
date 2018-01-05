<?php 
namespace Home\Model;
use Home\Model\BaseModel;
class MenuModel extends BaseModel {

	/**
	 * 获取菜单列表
	 * @Author   taolei
	 * @return   [array]     logList and pageData
	 */
	public function getMenuList(){
        $map['del_flag'] = array('eq', '1');
      	$rootList = $this
      	->field('id,name,url,sort,icon')
      	->where(array('tree_code'=>1,'del_flag'=>1,'parent_id'=>0))
      	->order('sort asc')
      	->select();
      	$rootSize = sizeof($rootList);
      	for($i=0;$i<$rootSize;++$i){
      		$sonList = $this
      		->field('id,name,url,sort,icon')
      		->where(array('tree_code'=>2,'del_flag'=>1,'parent_id'=>$rootList[$i]['id']))
      		->order('sort asc')
      		->select();
      		$rootList[$i]['sonList'] = $sonList;      	}
        return $rootList;
	}

	/**
	 * 删除一个菜单节点
	 * @Author   taolei
	 * @return   status
	 */
	public function deleteNode($type,$id){
		$new['del_flag'] = 0;
		if($type=='root'){
			$rst1 = $this->where(array('id'=>$id))->save($new);
			$rst2 = $this->where(array('parent_id'=>$id))->save($new);
		}else{
			$rst1 = $this->where(array('id'=>$id))->save($new);
		}
		if($rst1!=0){
			return true;
		}else{
			return false;
		}
	}

	/**
	 * 新增一个节点
	 * @Author   taolei
	 * @return   status
	 */
	public function addNode($data){

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
	 * 更新一个节点信息
	 * @Author   taolei
	 * @return   status
	 */
	public function updateNode($newdata){
		if(!$this->create($newdata)) {
			return $this->getError();
		}else{
			if($this->save($newdata) === false) {
				return false;
			}else {
				return true;
			}
		}
	}
}
?>