<?php

namespace Common\Tag;
use Think\Template\TagLib;
 /**
  * 自定义标签库类
  * @Author   taolei
  * @DateTime 2017-02-16
  *
*/
class mytag extends TagLib {
    // 定义标签名称
    protected $tags=array(
        'icons'      =>array('','close'=>0),
        'layuicss'   =>array('','close'=>0),
        'layuijs'    =>array('','close'=>0),
        'indexjs'    =>array('','close'=>0),
        'aliicon'    =>array('','close'=>0),
        );
 
     /**
      * 引入font awsone和阿里的 在线图标库
      * @Author   taolei
      * @DateTime 2017-02-16
      * @return   [String]     图标库的远程连接
      */
    public function _icons(){
        $link = <<<php
       <link href="//netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
       <link href="//at.alicdn.com/t/font_1uad1y953bfyldi.css" rel="stylesheet">

php;
        return $link;
    }

    /**
     * 引入 layui的 css文件
     * @Author   taolei
     * @DateTime 2017-02-16
     * @return   [String]   layui的样式文件地址
     */
    public function _layuicss(){
        $link = <<<php
          <link rel="stylesheet" type="text/css" href="__PUBLIC__/static/layui/css/layui.css">
php;
        return $link;
    }
    /**
     * 引入layui的js文件
     * @Author   taolei
     * @DateTime 2017-02-16
     * @return   String     layui的js文件地址
     */
    public function _layuijs(){
        $link = <<<php
        <script src="__PUBLIC__/static/layui/layui.js"></script>
php;
		return $link;
    }

    /**
     * 引入主页的js
     * @Author   taolei
     * @DateTime 2017-02-16
     * @return   String     主页的js文件地址
     */
    public function _indexjs(){
        $link = <<<php
        <script src="__PUBLIC__/static/js/index.js"></script>
php;
		return $link;
    }
}