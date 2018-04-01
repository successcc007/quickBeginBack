<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
function getMax($table,$field,$where=""){
    $sql="select max($field) as id from $table";
    if($where!=""){
        $sql.=" where $where";
    }
	$conn=mysqli_connect("localhost","root","123456");
	mysqli_select_db($conn,"jishi");
	mysqli_query($conn,"set names utf-8");
    $query=mysqli_query($conn,$sql);
    $arr=mysqli_fetch_array($query);
    if($arr){
        $maxid=$arr['id'];
    }else{
        $maxid=0;
    }
    return $maxid;

}