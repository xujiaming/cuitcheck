<?php
namespace Home\Controller;
use Common\Controller\HomeBaseController;

class SysuserManageController extends HomeBaseController{
    /**
     * @function 获取指定数据
     * @Author   许加明
     * @DateTime 2017-2-28 16:10:57
     * @param    无 
     * @return   无   
     */
    public function sysUserList(){
        $user = D('Sysuser')->getMyselfInfo(session('account'));

        $requestPage = I('requestPage', 1);	//获取请求的页码数
        $beginDate = I('beginDate', '');	//获取开始时间
        $endDate = I('endDate', '');		//获取结束时间
        $keyword = I('keyword', '');		//获取查询关键词
        $rows = 6;		                    //每页展示的数据
        $data = null;
        $dept = $user['dept_id'];

        if($user['role'] == '1'){           //判断是否是超级管理员
            $data = D('Sysuser')->getAllList($requestPage,$beginDate,$endDate,$keyword,$rows);
        }elseif($user['role'] == '3'){      //判断是否是管理员
            $data = D('Sysuser')->getAllList($requestPage,$beginDate,$endDate,$keyword,$rows,$dept);
        }else{      //其他可能为空
            $data = null;
        }
        $this->assign('sysUserList', $data['list']);
        $this->assign('pages', $data['pages']);
        $this->assign('requestPage', $requestPage);
//        $this->assign('beginDate', $beginDate);
//        $this->assign('endDate', $endDate);
        $this->assign('keyword', $keyword);
        $this->display();

    }
    
    /**
     * @function 打开编辑页面
     * @Author   许加明
     * @DateTime 2017-2-28 16:12:47
     * @param    type id
     * @return type, college
     */
    public function editSysUser(){

        $id = I('id');
        $data = D('Sysuser')->getSysUserById($id);      //获取单个信息
        $user = D('Sysuser')->getMyselfInfo(session('account'));    //获取当前用户信息
        $depts = null;
        $deptId = $user['dept_id'];     //当前用户所属部门


        /*获取部门标签*/
        if($user['role'] == '1'){
            $depts = D('College')->getCollegeIdAndNameList();
        }elseif($user['role'] == '3'){
            $depts = D('College')->getCollegeIdAndNameList($deptId);
        }else{
            $depts = D('College')->getCollegeIdAndNameList($data['dept_id']);
        }

        /*获取角色标签*/
        $roleSelect = null;//dict('sysRole');

        if($user['role'] == '1'){
            $string = '1,2,3,4';
            // $roleSelect = dict('sysRole','1,2,3,4');
        }elseif($user['role'] == '3'){
             $string = '3,4';
            // $roleSelect = dict('sysRole','3,4');
        }else{
             $string = '0';
            // $roleSelect = dict('sysRole','0');
        }
        $map['id']  = array('in',$string);
        $roleSelect=M('auth_group')->where($map)->select();

        if($id != $user['id']){
            $this->assign('sysuser', $data);
            $this->assign('roles',$roleSelect);
            $this->assign('depts',$depts);
            $this->display();
        }else{
            $string = <<<PHP
            <center><h1>自己不能操作自己</h1></center>
PHP;

            $this->show($string);
        }


    }

    /**
     * @function 打开编辑页面
     * @Author   许加明
     * @DateTime 2017-2-28 16:12:47
     * @param    type id
     * @return type, college
     */
    public function addSysUser(){
        $type = I('type');
        $user = D('Sysuser')->getMyselfInfo(session('account'));    //获取当前用户信息
        $string = '';
        $depts = null;

        if($user['role'] == '1'){
            $string = '1,2,3,4';
            $depts = D('College')->getCollegeIdAndNameList();
        }elseif($user['role'] == '3'){
            $string = '3,4';
            $depts = D('College')->getCollegeIdAndNameList($user['dept_id']);
        }else{
            $string = '0';
            $depts = D('College')->getCollegeIdAndNameList($user['dept_id']);
        }
        /*获取角色标签*/
        $map['id']  = array('in',$string);
        $roleSelect=M('auth_group')->field('id,title,status')->where($map)->select();
        // $roleSelect=Auth_group('id',$string);
        // dump($roleSelect);die();
        // $roleSelect = dict('sysRole',$string);
        /*获取部门标签*/

        $this->assign('roles',$roleSelect);
        $this->assign('depts',$depts);

        $this->assign('type', $type);
        $this->display();
    }

    /**
     * @function 修改用户
     * @Author   许加明
     * @DateTime 2017-3-1 16:55:25
     * @param    null
     * @return   null
     */
    public function updateSysUser(){
        if (!IS_POST){
            $data = array('success'=>false, 'msg'=>'提交方式不正确');
        }else{
            $temp=$_POST;
            $groupinfo['id']=$temp['id'];
            $groupinfo['role']=$temp['role'];
            $this->changeacess($groupinfo);
         $data = D('Sysuser')->updateInfoByManage();
          
        }

        $this->ajaxReturn($data, 'json');
    }

    public function changeacess($groupinfo){
        // dump($groupinfo);die();
        $data['uid']=$groupinfo['id'];
        $data['group_id']=$groupinfo['role'];
        // dump($data);die();
        $map['uid']=array('eq',$groupinfo['id']);
        $groupAcess=M('auth_group_access');
        $groupAcess->where($map)->delete();
        $groupAcess->add($data);

    }
    /**
     * @function 添加用户
     * @Author   许加明
     * @DateTime 2017-3-1 16:56:11
     * @param    null
     * @return   null
     */
    public function addSysUserByjson(){
        if (!IS_POST){
            $data = array('success'=>false, 'msg'=>'提交方式不正确');
        }else{
            $temp=$_POST;
            $data = D('Sysuser')->addInfoByManage($temp);
        }

        $this->ajaxReturn($data, 'json');
    }

    /**
     * @function 删除用户
     * @Author   许加明
     * @DateTime 2017-3-2 15:37:10
     * @param    null
     * @return   null
     */
    public function deleteSysUser(){
        if (!IS_POST){
            $data = array('success'=>false, 'msg'=>'提交方式不正确');
        }else{
            $data = D('Sysuser')->deleteSysUserByManage();
        }

        $this->ajaxReturn($data, 'json');
    }
    /**
     * @function 重置系统用户密码
     * @Author   罗钞
     * @DateTime 2017-7-26
     */
    public function resetpwd(){
        if(!IS_POST) {
            $data = array('success'=>false, 'msg'=>'提交方式不正确');
        }else{
            $data = D('Sysuser')->reset();
        }
        $this->ajaxReturn($data, 'json');

    }
    /**
     * @function 批量导入系统用户
     * @Author   罗钞
     * @DateTime 2017-7-26
     */
    public function importSysUserhtml(){
        $this->display();
    }
    /**
     * @function 导入模板生成下载
     * @Author   罗钞
     * @DateTime 2017-7-26
     */
    public function SysUserTemplate() {
        //创建Excel对象
        Vendor("PHPExcel.PHPExcel");
        Vendor("PHPExcel.PHPExcel.IOFactory");
        $objExcel = new \PHPExcel();
        //设置文件名
        $title = '系统用户导入模板';
        $objSheet = $objExcel->getActiveSheet();
        //列
        $cellName = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
        //设置文件默认水平垂直居中
        $objSheet->getDefaultStyle()->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER)->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        //设置每列的宽度
        $objExcel->getActiveSheet()->getColumnDimension('A')->setWidth(16);
        $objExcel->getActiveSheet()->getColumnDimension('B')->setWidth(16);
        $objExcel->getActiveSheet()->getColumnDimension('C')->setWidth(16);
        $objExcel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
        $objExcel->getActiveSheet()->getColumnDimension('E')->setWidth(30);
        $objExcel->getActiveSheet()->getColumnDimension('F')->setWidth(16);
        $objExcel->getActiveSheet()->getColumnDimension('G')->setWidth(16);
        $objExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
        $objExcel->getActiveSheet()->getColumnDimension('I')->setWidth(22);
        $objExcel->getActiveSheet()->getColumnDimension('J')->setWidth(32);
        $objExcel->getActiveSheet()->getColumnDimension('K')->setWidth(16);
        $objExcel->getActiveSheet()->getColumnDimension('L')->setWidth(16);
        $objExcel->getActiveSheet()->getColumnDimension('M')->setWidth(16);
        $objExcel->getActiveSheet()->getColumnDimension('N')->setWidth(16);
        $objExcel->getActiveSheet()->getColumnDimension('O')->setWidth(16);
        // 设置时间列的格式为文本串,防止phpexcel将日期自动转换
        $objExcel->getActiveSheet()->getStyle('D')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        //设置表头
        $cellHeader = array(
            '*账号','*姓名','*性别','*角色编号','*是否启用','*所属学院','手机号','邮箱','备注',
            );
        //填充表头
        for($i = 0;$i < sizeof($cellHeader);$i++) {
            $objExcel->getActiveSheet()->setCellValue($cellName[$i].'1',$cellHeader[$i]);
        }
        $objWriter = new \PHPExcel_Writer_Excel5($objExcel);
        //刷新缓冲
        ob_end_clean();
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
        header("Content-Type:application/force-download");
        header("Content-Type:application/vnd.ms-execl");
        header("Content-Type:application/octet-stream");
        header("Content-Type:application/download");
        //生成的文件名字
        //attachment新窗口打印inline本窗口打印
        header('Content-Disposition:attachment;filename='.$title.'.xls');
        header("Content-Transfer-Encoding:binary");
        //文件通过浏览器下载
        $objWriter->save('php://output');

    }
    /**
     * @function 系统用户的导入
     * @Author   罗钞
     * @DateTime 2017-7-26
     */
    public function importFileSysUser() {
        //实例化上传类
        $upload = new \Think\Upload();
        //设置上传文件大小，此处为 3M
        $upload->maxSize = 3145728;
        //上传文件类型
        $upload->exts = array('xlsx','xls');
        //设置时区为中国
        date_default_timezone_set('prc');
        $time = time();
        $tempName = $time."".rand(1,10000);
        //设置文件名保存为时间加随机数
        $upload->saveName = $tempName;
        //设置文件保存路径
        $upload->savePath = '/Sysuser_import/';
        //上传文件
        $info = $upload->uploadOne($_FILES['stu_import']);
        if(!$info) {
            $result = array(
                'code' => 1,
                'msg' => '上传失败！请仔细阅读注意事项！'
                );
        }else{
            //获取上传后的文件名
            $file_name = $upload->rootPath.$info['savepath'].$info['savename'];
            // dump($file_name);die();
            //获得文件的后缀名
            $exts = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
            // dump($exts);die();
            //调用函数，将数据保存到数据库，得到返回的错误集
            $temp = $this->saveStuToDb($file_name, $exts);
            if(sizeof($temp) == 0) {
                $result['msg'] = "全部学生信息导入成功！";
            }else {
                //定义错误提示格式
                for($i=1;$i<=8;$i++) {
                    $type[$i]['msg']='<tr><td>第 ';
                    $type[$i]['nums'] = 0;
                }
                for($i = 0;$i < sizeof($temp);$i++) {
                    if($temp[$i]['error_code'] == 1) {
                        $type[1]['nums']++;
                        $type[1]['msg'] .=$temp[$i]['rows']."，";
                    }
                    if($temp[$i]['error_code'] == 2) {
                        $type[2]['nums']++;
                        $type[2]['msg'] .=$temp[$i]['rows']."，";
                    }
                    if($temp[$i]['error_code'] == 3) {
                        $type[3]['nums']++;
                        $type[3]['msg'] .=$temp[$i]['rows']."，";
                    }
                    if($temp[$i]['error_code'] == 4) {
                        $type[4]['nums']++;
                        $type[4]['msg'] .=$temp[$i]['rows']."，";
                    }
                    if($temp[$i]['error_code'] == 5) {
                        $type[5]['nums']++;
                        $type[5]['msg'] .=$temp[$i]['rows']."，";
                    }
                   if($temp[$i]['error_code'] == 6) {
                        $type[6]['nums']++;
                        $type[6]['msg'] .=$temp[$i]['rows']."，";
                    }
                }
                $type[1]['msg'].='行</td><td>该行有必填项为空</td></tr>';
                $type[2]['msg'].='行</td><td>该行的系统用户已存在</p>';
                $type[3]['msg'].='行</td><td>数据错误</td></tr>';
                $type[4]['msg'].='行</td><td>该行的性别格式错误</td></tr>';
                $type[5]['msg'].='行</td><td>该行的手机格式错误</td></tr>';
                $type[6]['msg'].='行</td><td>该行的学院数据库中无记录</td></tr>';
                $str = '<div style="padding: 5px;"><table class="layui-table" style="text-align:center;"><thead><tr><th style="text-align:center;">行数</th><th style="text-align:center;">错误原因</th><tr></thead>';
                for($j = 1;$j <= 6;$j++) {
                    if($type[$j]['nums']) {
                        $str.=$type[$j]['msg'];
                    }
                }
                $str.='</table></div>';
                $result['msg'] = $str;
                $result['code'] = 2;
            }
        }
        $this->ajaxReturn($result, 'json');
        //echo json_encode($result);

    }
    /*
    对导入的文件数据进行验证导入数据库
     */
    public function saveStuToDb($filename, $exts) {
        //引入类
        Vendor("PHPExcel.PHPExcel");
        //判断导入的Excel文件格式
        if($exts == 'xlsx') {
            $objReader = \PHPExcel_IOFactory::createReader('Excel2007');
            $objPHPExcel = $objReader->load($filename, $encode = 'utf-8');
        }else if($exts == 'xls') {
            $objReader = \PHPExcel_IOFactory::createReader('Excel5');
            $objPHPExcel = $objReader->load($filename, $encode = 'utf-8');
        }
        //获取sheet，如果需要兼容多sheet，将这里改为遍历
        $sheet = $objPHPExcel->getSheet(0);
        //获得行数和列数
        $maxRow = $sheet->getHighestRow();
        $maxColumn = $sheet->getHighestColumn();
        // dump($maxColumn);die();
        $error = array();
        //从第二行开始取数据,将数据统一转化为String类型，便于处理
        for($i = 2;$i <= $maxRow;$i++) {
            $sys_account = (String)$objPHPExcel->getActiveSheet()->getCell("A" . $i)->getValue();
            $sys_name= (String)$objPHPExcel->getActiveSheet()->getCell("B" . $i)->getValue();
            $sys_sex = (String)$objPHPExcel->getActiveSheet()->getCell("C" . $i)->getValue();
            $sys_role = (String)$objPHPExcel->getActiveSheet()->getCell("D" . $i)->getValue();
            $sys_status = (String)$objPHPExcel->getActiveSheet()->getCell("E" . $i)->getValue();
            $sys_college = (String)$objPHPExcel->getActiveSheet()->getCell("F" . $i)->getValue();
            $sys_phone = (String)$objPHPExcel->getActiveSheet()->getCell("G" . $i)->getValue();
            $sys_email = (String)$objPHPExcel->getActiveSheet()->getCell("H" . $i)->getValue();
            $sys_remarks = (String)$objPHPExcel->getActiveSheet()->getCell("I" . $i)->getValue();
            //  1.判断是否必填项为空，如果有一项必填项为空，将错误信息追加至错误数组中，然后跳出本次循环，进行下一次循环
            if($sys_account==''||$sys_name==''||$sys_sex==''||$sys_role==''||$sys_status==''||$sys_college=='') {
                $miss = $this->getError_array(1, $i);
                //将错误信息数组压入$error末尾
                array_push($error, $miss);
                continue;
            }                       
            //4.验证性别字段，性别表示，男1女0
            if($sys_sex=='男') {
                $sex = 1;
            }else if($sys_sex=='女') {
                $sex = 0;
            }else {
                $miss = $this->getError_array(4,$i);
                array_push($error, $miss);
                continue;
            }
            $isset = $this->getId_ByName($sys_college);
            if(!$isset['isset']) {
                $miss = $this->getError_array(6, $i);
                array_push($error, $miss);
                continue;
            }   
            //  5.如果填写了手机号进行手机号的验证，判断手机号格式是否正确
            if(!empty($sys_phone)) {
                if(!$this->checkPhone($sys_phone)) {
                $miss = $this->getError_array(5, $i);
                array_push($error, $miss);
                continue;
                }
            }    

            /*********************验证完成***********************/
            //判断该系统用户账号是否已经存在,如果已经存在则跳出当前本次循环，如果不存在，则进行添加
            if(!$this->isset_Sysuser($sys_account)) {
                $miss = $this->getError_array(2, $i);
                array_push($error, $miss);
                continue;
            }else{
                //添加系统用户信息
                $password=123456;
                $stu_info = array(
                    'account' => $sys_account,
                    'password' => md5(sha1($password)),
                    'name' => $sys_name,
                    'sex' => $sex,
                    'role'=>$sys_role,
                    'dept_id' => $isset['ids']['dept_id'],
                    'phone' => $sys_phone,
                    'email' => $sys_email,
                    'remarks' => $sys_remarks,
                    'status' => '1',
                    'create_date' => date('Y-m-d H:i:s', time()),
                    'create_by' => session('account'),
                    );
                $isSuccess = M('Sysuser')->add($stu_info);
                if($isSuccess) {
                    $map['account']=$sys_account;
                    $sysUser=M('Sysuser')->field(array('id'))->where($map)->find();
                    $data['uid']=$sysUser['id'];
                    $data['group_id']=$sys_role;
                    $groupAcess=M('auth_group_access');
                    $groupAcess->add($data);
                }else{
                    $miss = $this->getError_array(3, $i);
                    array_push($error,$miss);
                    continue;
                }
            }

        }
        //返回错误数组
        return $error;
    }
    /**
     * 功能:根据错误代码，获取错误信息
     * 参数:$error_code 错误代码  $row 错误的行号
     * 返回值:$error 错误信息数组
     */
    public function getError_array($error_code,$row){
        $error['rows'] = $row;
        $error['success'] = false;
        if($error_code==1){
            $error['error_code'] = 1;
            $error['msg'] =  '该行有必填项为空!';
        }
        if($error_code==2){
            $error['error_code'] = 2;
            $error['msg'] = '该行的系统用户已存在!';
        }
        if($error_code==3){
            $error['error_code'] = 3;
            $error['msg'] = '该行添加时出现错误!';
        }
        if($error_code==4) {
            $error['error_code'] = 4;
            $error['msg'] = '系统用户性别格式错误！';
        }
        if($error_code==5) {
            $error['error_code'] = 5;
            $error['msg'] = '该行手机格式错误！';
        }
        if($error_code==6) {
            $error['error_code'] = 6;
            $error['msg'] = '该行的学院数据库中无记录';
        }
        if($error_code==0){
            $error['msg'] = '该行导入成功!';
            $error['success'] = true;
        }

        return $error;
    }
    /**
     * 功能：根据Excel表中的院校名称，获取院校对应信息的id
     * 参数：院校的名称
     * 返回值:院校的id数组,或者错误编号
     */ 
    public function getId_ByName($college_name){
        $idArray = array();
        $map['name'] = $college_name;
        $data = M('college')->where($map)->find();
        //p($data);
        if(sizeof($data)==0){
            $idArray['isset'] = false;
        }else{
            $idArray['isset'] = true;
            //如果查询成功，将各个对应id保存
            
            $ids['dept_id'] = $data['id'];
            $idArray['ids'] = $ids;
        }
        return $idArray;
    }
    /**
     * 功能：判断是否存在学生账号信息  存在返回false
     * 参数：学生学号
     * 返回值：存在 false 不存在  true
     */
    public function isset_Sysuser($account){
        $result = M('Sysuser')->where(array('account'=>$account,'del_flag'=>1))->count();
        return $result==0?true:false;
    }
    /**
     * 功能：判断手机号的格式是否正确
     * 参数：手机号
     * 返回值：正确true 错误false
     */
    public function checkPhone($phoneNum) {
        if(!is_numeric($phoneNum)) {
            return false;
        }
        return preg_match('#^13[\d]{9}$|^14[5,7]{1}\d{8}$|^15[^4]{1}\d{8}$|^17[0,6,7,8]{1}\d{8}$|^18[\d]{9}$#', $phoneNum) ? true : false;
    }
    
}