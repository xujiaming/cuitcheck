<?php 
namespace Home\Model;
use Think\Model\ViewModel;
	/**
	 * @function: 查询学生信息的视图模型，学生的信息，以及院校ID所对应表中的名字
	 * @Author: 梁轩豪
	 * @DateTime:  2017-03-11 10:37:51
	 */
class StudentSearchModel extends ViewModel {
	//定义查询表以及字段
	public $viewFields = array(
		'Student' => array('id'=>'stu_id','account','name'=>'stu_name','sex','photo','email','phone','create_date'=>'stu_create_date','remarks','status','dept_id','del_flag'=>'stu_del_flag'),
		'College' => array('id'=>'col_id','name'=>'col_name','_on'=>'College.id=Student.dept_id'),
		'Major' => array('id'=>'maj_id','name'=>'maj_name','_on'=>'Major.id=Student.major_id'),
		'Grade' => array('id'=>'gra_id','name'=>'gra_name','_on'=>'Grade.id=Student.grade_id'),
		'Class' => array('id'=>'cla_id','name'=>'cla_name','_on'=>'Class.id=Student.class_id'),
		);
}
?>