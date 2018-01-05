<?php 


namespace Student\Model;
use Think\Model;

/**
 * 试卷详情
 */
class PaperCourserclassModel extends Model{






	/**
	 * 获得考试记录相关详情
	 * @function
	 * @AuthorPJY
	 * @DateTime  2017-07-18T10:16:23+0800
	 * @param     [type]                   $account_id [description]
	 * @return    [type]                               [description]
	 */
	public function getRecordInfo($account_id, $type, $requestPage, $rows){

                
		$map['account'] = $account_id;

		// 根据学生账户,加入行课班级获得所有试卷id列表
		$testpaper_ids = $this
			->field(array('testpaper_id,end_time'))
                        ->join('kh_class_student ON kh_class_student.courseclass_id= kh_paper_courserclass.courserclass_id')
                        ->where($map)
                        ->select();
                        
                $i = 0;
                $result_ids="";
                // 根据试卷id获取大于结束时间
                foreach ($testpaper_ids as $key=>$testpaper) {
                	$end_time = $testpaper['end_time'];
                	$end_time = strtotime($end_time);		//将字符串转换为时间戳
                        if (time() > $end_time){
                		$result_ids[$i++] = $testpaper['testpaper_id'];
                	}
                }

                $total = count($result_ids);                                                    // 获得数组总数
                $result_ids = array_splice($result_ids, ($requestPage-1)*$rows, $rows);         // 将数组进行分页处理
                // $limit = ($requestPage-1)*$rows.','.$rows;

                // 获取试卷信息
                $list = [];
                foreach ($result_ids as $key=>$id) {
                        $map1['testpaper_id'] = $id;
                        // 查询全部
                        if($type != "" && $type != "all"){
                                $map1['type_id'] = array('eq',$type);
                        }

                 	$data   = $this
        			->field('testpaper_id,start_time,kh_testpaper.name,kh_testpaper.pass_score,kh_testpaper.full_score,kh_paper_courserclass.create_by')
        			->join('kh_testpaper ON kh_testpaper.id = kh_paper_courserclass.testpaper_id')
        			->where($map1)
        			->select();
                	$list[$key] = $data[0];
                }


                $data = [
                        'pages' => ceil($total / $rows),
                        'list' => $list,
                ];
                return $data;
	}



    /**
     * 检验是否在考试时间之内
     * @function
     * @AuthorPJY
     * @DateTime  2017-07-21T11:54:27+0800
     * @return    boolean                  [description]
     */
    public function isIntime($paper_id, $course_id){
        $map['testpaper_id'] = $paper_id;
        $map['courserclass_id'] = $course_id;
        $map['is_use'] = 1;

        // 获得考试时间
        $info = $this->field('start_time,end_time')->where($map)->select();
        $start_time = strtotime($info[0]['start_time']);     //将字符串转换为时间戳
        $end_time = strtotime($info[0]['end_time']);     //将字符串转换为时间戳

        if(time() > $start_time && time() < $end_time) {
            return true;
        }else{
            return false;
        }

    }



    /**
     * 根据试卷id及班级id获得试卷相关信息
     * @function
     * @AuthorPJY
     * @DateTime  2017-07-21T19:00:26+0800
     * @param     [type]                   $paper_id  [description]
     * @param     [type]                   $course_id [description]
     * @return    [type]                              [description]
     */
    public function getPapaerInfo($paper_id, $course_id){
        $map['testpaper_id'] = $paper_id;
        $map['courserclass_id'] = $course_id;
        $time = $this->field('end_time')->where($map)->select();
        $testpaper = M('testpaper')->field('name, full_score, pass_score,id')->where(array('id'=>$paper_id))->select();
        $testpaper[0]['end_time'] =$time[0]['end_time'];
        return $testpaper[0];
    }



}