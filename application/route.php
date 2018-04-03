<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
use think\Route;

Route::rule('/api/v1/index/GetClasses','Index/GetClasses');//查询所有课程
Route::rule('/api/v1/index/AddClass','Index/AddClass');//新增课程
Route::rule('/api/v1/index/GetClassSingle','Index/GetClassSingle');//查询某一课程
Route::rule('/api/v1/index/GetSignInfo','Index/GetSignInfo');//查询签到信息
Route::rule('/api/v1/index/GetMessages','Index/GetMessages');//查询留言
Route::rule('/api/v1/index/GetScores','Index/GetScores');//查询成绩
Route::rule('/api/v1/index/DeleteClass','Index/DeleteClass');//删除课程
Route::rule('/api/v1/index/Login','Index/Login');//登录
Route::rule('/api/v1/index/AttendClass','Index/AttendClass');//参加课程
Route::rule('/api/v1/index/AddImgQrPath','Index/AddImgQrPath');//二维码保存
Route::rule('/api/v1/index/SetPosition','Index/SetPosition');//二维码保存







