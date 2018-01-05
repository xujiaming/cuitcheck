<?php
/**
 * @Created by PhpStorm.
 * @User: xuanhao
 * @Date: 2017/7/16
 * @Time: 11:17
 * @Function: 数据信息处理模型
 */

namespace Student\Model;
use Think\Model;

class TestpaperModel extends Model{

    /**
     * @Function: 查找符合指定条件的试卷列表
     * @Author: xuanhao
     * @DateTime: ${DATE} ${TIME}
     * @param $canPaperIds ，能够使用 的试卷id集合
     * @param $requestPage ，当前页号
     * @param $rows ，分页限制
     * @param $type_id ，测试类型
     * @return array
     */
    public function getPaper($canPaperIds, $requestPage, $rows, $type_id) {
        $map['kh_dict.type'] = 'testType';      //字典类型
        $map['kh_testpaper.del_flag'] = array('eq', '1');
        $map['kh_testpaper.is_use'] = array('eq', '1');     //试卷是否启用
        $map['kh_testpaper.id'] = array('in',$canPaperIds);
        $limit = ($requestPage-1)*$rows.','.$rows;
        if($type_id == 0) {
            $type_id = '';
        }
        if($type_id != '') {
            $map['kh_testpaper.type_id'] = array('eq', $type_id);
        }
        $total = M('testpaper')	//获取符合条件的数据条数
        ->field('kh_testpaper.id, kh_testpaper.name, kh_dict.label as typename, kh_college.name as collegename, kh_testpaper.full_score, kh_testpaper.pass_score,kh_testpaper.is_use, kh_testpaper.create_by,kh_testpaper.create_date')
            ->join('JOIN kh_dict ON kh_dict.value = kh_testpaper.type_id')
            ->join('JOIN kh_college ON kh_college.id = kh_testpaper.college_id')
            ->where($map)->count();

        $list = M('testpaper')
            ->field('kh_testpaper.id, kh_testpaper.name, kh_dict.label as typename, kh_college.name as collegename, kh_testpaper.full_score, kh_testpaper.pass_score,kh_testpaper.is_use,kh_testpaper.create_by,kh_testpaper.create_date')
            ->join('JOIN kh_dict ON kh_dict.value = kh_testpaper.type_id')
            ->join('JOIN kh_college ON kh_college.id = kh_testpaper.college_id')
            ->where($map)
            ->limit($limit)->order('id asc')->select();

        //查找题量字段

        $i = 0;
        foreach($list as &$value){
            //获取每套试题的题量
            $value['number'] = M('PaperQuestion')->where(array('testpaper_id'=>$value['id']))->count();
            $i++;
        }
        unset($value);

        $pages = 0;
        if ($total%$rows == 0){
            $pages = $total/$rows;
        }else{
            $pages = intval($total/$rows + 1);
        }

        $data = array(
            "pages" => $pages,
            "list" => $list
        );
        return $data;
    }

    /**
     * @Function: 获取试卷的考试开始时间与结束时间
     * @Author: xuanhao
     * @DateTime: ${DATE} ${TIME}
     * * @param $paperListInfo1
     * @param $paperIds_temp
     * @return mixed
     */
    public function getPaperTime($paperListInfo1, $paperIds_temp) {
        foreach($paperIds_temp as &$x) {
            foreach($paperListInfo1['list'] as &$v) {
                if($x['testpaper_id'] == $v['id']) {
                    $v['start_time'] = $x['start_time'];
                    $v['end_time'] = $x['end_time'];
                    $v['courseclass_name'] = $x['courseclassname'];
                    $v['courseclass_id'] = $x['courserclass_id'];
                }
            }
        }
        return $paperListInfo1;
    }
    /**
     * @Function: 获取指定试卷的信息
     * @Author: xuanhao
     * @DateTime: ${DATE} ${TIME}
     *
     */
    public function paper_info() {
        $id = I('id');          //获取试卷的id
        $courseclass_id = I('courseclass_id');      //获取行课班级id
        //$id = 2;
        if($id == '') {
            $result['status'] = 0;
            $result['msg'] = '未选择试卷';
            echo json_encode($result);
            exit;
        }
        $map['kh_dict.type'] = 'testType';      //字典类型
        $map['kh_testpaper.del_flag'] = array('eq', '1');
        $map['kh_testpaper.is_use'] = array('eq', '1');     //试卷是否启用
        $map['kh_testpaper.id'] = array('eq', $id);
        $info = M('testpaper')
            ->field('kh_testpaper.id, kh_testpaper.id, kh_testpaper.name, kh_dict.label as typename, kh_college.name as collegename, kh_testpaper.full_score, kh_testpaper.pass_score,kh_testpaper.is_use,kh_testpaper.create_by,kh_testpaper.create_date,
            kh_paper_courserclass.start_time,kh_paper_courserclass.end_time')
            ->join('JOIN kh_dict ON kh_dict.value = kh_testpaper.type_id')
            ->join('JOIN kh_college ON kh_college.id = kh_testpaper.college_id')
            ->join('JOIN kh_paper_courserclass ON kh_paper_courserclass.testpaper_id = '.$id.' AND kh_paper_courserclass.courserclass_id = '.$courseclass_id.'')
            ->where($map)
            ->select();
        $info[0]['courseclass_id'] = $courseclass_id;
        $result = array(
            'status' => 1,
            'msg' => '获取信息成功',
            'info' => $info
        );
        return $result;
    }
}