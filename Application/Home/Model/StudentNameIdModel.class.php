<?php 
namespace Home\Model;
use Think\Model\ViewModel;
/**
	 * @function: 查找导入的院校信息的名字的对应ID
	 * @Author: 梁轩豪
	 * @DateTime:  2017-03-14 22:30:51
	 */
class StudentNameIdModel extends ViewModel {
	//定义查询表以及字段
	public $viewFields = array(
		'College' => array('id'=>'dept_id','name'=>'col_name'),
		'Major' => array('id'=>'major_id','name'=>'maj_name'),
		'Grade' => array('id'=>'grade_id','name'=>'gra_name'),
		'Class' => array( 'id'=>'class_id','name'=>'cla_name'),
		);
}
?>