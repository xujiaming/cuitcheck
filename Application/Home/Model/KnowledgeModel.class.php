<?php
namespace Home\Model;
use Think\Model;

class KnowledgeModel extends Model{
    /**
     * 开启批量验证
     * */
    protected $patchValidate = true;
    /**
     * 自动验证设置
     */
    protected $_validate = array(

        array('id','checkID','id不存在！',0,'callback'),
        array('name','require','名称不能为空！'),
        array('chapter_id','checkChapter','章节范围有误！',0,'callback'),
    );

    /**
     * 使用自动完成来填写部分默认数据项
     */
    protected $_auto = array(
        array('create_date', 'getTime', 1, 'callback'),		//新增数据时将创建时间设为当前时间
        array('update_date', 'getTime', 2, 'callback'),		//更新数据时将更新时间设为当前时间
        array('create_by', 'getAccount', 1, 'callback'),//新增数据时将创建人设为当前用户account
        array('update_by', 'getAccount', 2, 'callback')	//更新时将更新人设为当前用户account
    );

    /**
     * @function 验证部门范围
     * @Author   许加明
     * @DateTime 2017-3-1 14:14:19
     * @param    $value 范围的值
     * @return   boolean
     */
    public function checkChapter($value){
        $arrStr = '';
        $temp = M('Chapter')->where(array('del_flag'=>1))->select();
        foreach($temp as $item){
            $arrStr = $arrStr.$item['id'];
        }
        if(strpos($arrStr,$value) === false){
            return false;
        }
        return true;
    }

    public function checkID($value){
        $arrStr = '';
        $temp = $this->where(array('del_flag'=>1))->select();
        foreach($temp as $item){
            $arrStr = $arrStr.$item['id'];
        }
        if(strpos($arrStr,$value) === false){
            return false;
        }
        return true;
    }

    /**
     * @function 获取session中的用户
     * @Author   许加明
     * @DateTime 2017-2-26 16:31:34
     * @param    null
     * @return   string  账号
     */
    public function getAccount(){
        $account = session('account');

        return $account;
    }

    /**
     * @function 获取当前时间
     * @Author   许加明
     * @DateTime 2017-2-26 16:32:43
     * @param    null
     * @return   string 时间
     */
    public function getTime(){
        return date('Y-m-d H:i:s', time());
    }

    /**
     * @function 打包需要返回的信息
     * @Author   许加明
     * @DateTime 2017-3-24 19:01:09
     * @param    boolean 状态值
     * @return   array 打包的数据
     */
    public function packResult($status,$msg=''){
        if($msg !== ''){
            $arr['msg'] = $msg;
        }else{
            if($status){
                $arr['msg'] = '操作成功！';
            }else{
                $arr['msg'] = '操作失败！';
            }
        }

        $arr['success'] = $status;

        return $arr;
    }

    /**
     * @function 管理员更新知识点信息
     * @Author   许加明
     * @DateTime 2017-3-24 19:01:14
     * @param    null
     * @return   array $info 结果信息
     */
    public function updateKnowledge(){
        //获得当前用户信息
        $user = D('Sysuser')->getMyselfInfo(session('account'));
        $info = null;
        if(!$this->create()){
            foreach($this->getError() as $key => $value){
                if($value['success'] === false){
                    $info = $this->packResult(false,$value['msg']);
                    break;
                }
            }
        }else{
            if($user['role'] != 1 && $user['role'] != 3 && $user['role'] != 4 ){
                $info = $this->packResult(false,'权限不够！');
            }else{
                if($this->save() === false){
                    $info = $this->packResult(false);
                }else{
                    $info = $this->packResult(true);
                }
            }
        }
        return $info;
    }

    /**
     * @function 管理员添加单个知识点
     * @Author   许加明
     * @DateTime 2017-3-25 00:37:17
     * @param    null
     * @return   array $info 结果信息
     */
    public function addKnowledge(){
        $info = null;
        $user = D('Sysuser')->getMyselfInfo(session('account'));

        if(!$this->create()){
            foreach(($this->getError()) as $key => $value){
                if($value['success'] === false){
                    $info = $this->packResult(false,$value['msg']);
                    break;
                }
            }
        }else{
            if($user['role'] != 1 && $user['role'] != 3 && $user['role'] != 4 ){
                $info = $this->packResult(false,'权限不够！');
            }else{
                if($this->add() === false){
                    $info = $this->packResult(false,'添加失败！');
                }else{
                    $info = $this->packResult(true,'添加成功！');
                }
            }
        }
        return $info;
    }

    /**
     * @function 管理员删除知识点
     * @Author   许加明
     * @DateTime 2017-3-25 00:52:52
     * @param    null
     * @return   array $info 结果信息
     */
    public function deleteKnowledge(){
        $info = null;
        $user = D('Sysuser')->getMyselfInfo(session('account'));
        $id = I('post.id');

        if($user['role'] != 1 && $user['role'] != 3 && $user['role'] != 4 ){
            $info = $this->packResult(false,'权限不够！');
        }else{
            $flag2 = M('question_know')->where(array('del_flag'=>1,'knowledge_id'=>$id))->find();
            //判断知识点是否已经关联了题库
            if(!empty($flag2)) {
                $info = $this->packResult(false, '该知识点在题库中有对用题目存在！');
            }else{
                $flag1 = $this->where(array('id'=>$id))->setField('del_flag',0);
                if($flag1 === false){
                    $info = $this->packResult(false,'删除失败');
                }else{
                    $info = $this->packResult(true,'删除成功');
                }
            }

        }
        return $info;
    }

    /**
     * @function 获得章节列表
     * @Author   许加明
     * @DateTime 2017-3-24 19:19:20
     * @param    null 
     * @return   array 章节数组
     * @ModifyAuthor 梁轩豪
     */
    public function getChapterList(){
        $dept = getdeptId();
        if($dept == 0) {
            $defaultDept = M('college')->field('id')->find();  //返回查找的第一个学院的id
            $choiceDept = I('choiceDept', $defaultDept['id']);          //超级管理员可选择学院进行查看，默认为查询的一个学院的数据
            $map1 = array(
                'del_flag' => 1,
                'college_id' => $choiceDept
            );
        }else {
            $map1 = array(
                'del_flag' => 1,
                'college_id' => $dept
            );
        }

        $course = M('course')->field('id,name')->where($map1)->select();
        //组织json
        //$i,$j,$k的意义：默认第一个展开
        $i = 0;
        $k = 0;
        foreach ($course as &$value1) {
            $k == 0? $value1['spread'] = true :$value1['spread'] = false;
            //获取学科下的课程
            $children1 = M('lession')->field('id,name,course_id')->where(array('course_id'=>$value1['id'], 'del_flag'=>1))->select();
            //获取章节
            foreach($children1 as &$value2){
                $i == 0? $value2['spread'] = true :$value2['spread'] = false;

                $children2 = M('Chapter') -> where(array('lession_id'=>$value2['id'],'del_flag'=>1))
                    -> order('sortnumber') -> select();

                $j = 0;
                //组装章节
                foreach($children2 as &$value3){
                    $j++;
                    $value3['name'] = '第'.$j.'章:'.$value3['name'].'('.$value3['sortnumber'].')';
                    $value3['homePart'] = 'kids';
                }
                unset($value3);

                $value2['children'] = $children2; //压入章节到课程
                $value2['homePart'] = 'parent';
                $i++;
            }
            unset($value2);
            $value1['children'] = $children1;       //压入课程到学科
            $value1['homePart'] = 'stop';
            $k++;

        }
        unset($value1);
        
        return $course;
    }

    /**
     * @function 根据筛选条件获取全部数据
     * @Author   许加明
     * @DateTime 2017-2-27 15:19:15
     * @param int $requestPage 请求页 , string $beginDate 开始时间, string $endDate 结束时间, string $keyword 关键词, int $rows 行数, int $dept 查询的学院
     * @return  array 知识点数据
     */
//    public function getAllList($requestPage, $keyword, $rows,$course_id='',$chapter_id=''){
//
//        $map['kh_knowledge.del_flag'] = array('eq', '1');
//        if($chapter_id !== ''){
//            $map['kh_knowledge.chapter_id'] = array('eq',$chapter_id);
//            $map['kh_chapter.del_flag'] = array('eq','1');
//        }
//        if($course_id !== ''){
//            $map['kh_course.id'] = array('eq',$course_id);
//            $map['kh_course.del_flag'] = array('eq','1');
//        }
//        if ($keyword != ''){
//            $map['kh_knowledge.id|kh_knowledge.name|kh_knowledge.comment|kh_chapter.name|kh_course.name'] = array('like', '%'.$keyword.'%');
//        }
////        if ($beginDate != '' && $endDate != ''){
////            $map['kh_knowledge.create_date'] = array(array('egt', $beginDate), array('elt', $endDate));
////        }
//
//        $limit = ($requestPage-1)*$rows.','.$rows;      //查找行数
//
//        $total = $this		//获取全部符合条件的数据条数
//        ->join('LEFT join kh_chapter  ON kh_chapter.id = kh_knowledge.chapter_id
//                LEFT join kh_course  ON kh_course.id = kh_chapter.course_id')
//        ->where($map)->count();
//
//
//        $list = $this
//            ->join('LEFT join kh_chapter ON kh_chapter.id = kh_knowledge.chapter_id
//                    LEFT join kh_course ON kh_course.id = kh_chapter.course_id')
//            ->field(array('kh_knowledge.id','kh_knowledge.name','kh_knowledge.comment','kh_chapter.name'=>'chaptername','kh_course.name'=>'coursename','kh_knowledge.create_date'))
//            ->where($map)
//            ->limit($limit)->select();
//
//        $pages = ($total % $rows === 0 ? intval(floor($total/$rows)) : intval(floor($total/$rows+1)));     //获取页数
//
//        $data = array(
//            "pages" => $pages,
//            "list" => $list,
//        );
//
//        return $data;
//    }
    public function getAllList($requestPage, $keyword, $rows,$lession_id='',$chapter_id=''){

        $map['kh_knowledge.del_flag'] = array('eq', '1');
        if($chapter_id !== ''){
            $map['kh_knowledge.chapter_id'] = array('eq',$chapter_id);
            $map['kh_chapter.del_flag'] = array('eq','1');
        }
        if($lession_id !== ''){
            $map['kh_lession.id'] = array('eq',$lession_id);
            $map['kh_lession.del_flag'] = array('eq','1');
        }
        if ($keyword != ''){
            $map['kh_knowledge.id|kh_knowledge.name|kh_knowledge.comment|kh_chapter.name|kh_lession.name'] = array('like', '%'.$keyword.'%');
        }
//        if ($beginDate != '' && $endDate != ''){
//            $map['kh_knowledge.create_date'] = array(array('egt', $beginDate), array('elt', $endDate));
//        }

        $limit = ($requestPage-1)*$rows.','.$rows;      //查找行数

        $total = $this		//获取全部符合条件的数据条数
        ->join('LEFT join kh_chapter  ON kh_chapter.id = kh_knowledge.chapter_id
                LEFT join kh_lession  ON kh_lession.id = kh_chapter.lession_id')
            ->where($map)->count();


        $list = $this
            ->join('LEFT join kh_chapter ON kh_chapter.id = kh_knowledge.chapter_id
                    LEFT join kh_lession ON kh_lession.id = kh_chapter.lession_id')
            ->field(array('kh_knowledge.id','kh_knowledge.name','kh_knowledge.comment','kh_chapter.name'=>'chaptername','kh_lession.name'=>'lessionname','kh_knowledge.create_date'))
            ->where($map)
            ->limit($limit)->select();

        $pages = ($total % $rows === 0 ? intval(floor($total/$rows)) : intval(floor($total/$rows+1)));     //获取页数

        $data = array(
            "pages" => $pages,
            "list" => $list,
        );

        return $data;
    }

    /**
     * @Function: 通过课程下的某一个章节来获取该课程下的所有的章节；或者通过课程号来获取该课程下的所有的掌机
     * @Author: xuanhao
     * @DateTime: ${DATE} ${TIME}
     * * @param $chapter_id 章节id  $lession_id 课程id
     */
    public function getTheChapterForLession($lession_id = '', $chapter_id = '') {
        $lession_chapter = '';
        if($lession_id == '') {
            $map = array(
                'del_flag' => 1,
                'id' => $chapter_id
            );
            $lession = M('chapter')->where($map)->field('lession_id')->find();
            $lession_chapter = M('chapter')->where(array('del_flag'=>1,'lession_id'=>$lession['lession_id']))->select();
        }else if($chapter_id == '') {
            $lession_chapter = M('chapter')->where(array('del_flag'=>1,'lession_id'=>$lession_id))->select();
        }
        return $lession_chapter;
    }


    /**
     * 根据课程id获得相应知识点
     * @function
     * @AuthorPJY
     * @DateTime  2017-10-15T16:20:15+0800
     * @return    [type]                   [description]
     */
    public function getKonwlegeByLession($lession_id){
        $map['kh_knowledge.del_flag'] = 1;
        $map['kh_lession.id'] = $lession_id;
        $list = $this->field('kh_knowledge.id, kh_knowledge.name')
                ->join('JOIN kh_chapter ON kh_chapter.id = kh_knowledge.chapter_id')
                ->join('JOIN kh_lession ON kh_chapter.lession_id = kh_lession.id')
                ->where($map)
                ->select();
        // var_dump($list);exit();
        return $list;
    }

}