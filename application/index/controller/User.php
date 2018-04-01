<?php
namespace app\index\controller;

use think\Db;
use think\Request;

class User
{
    /*
     * 用户注册
     * @username  用户名
     * @password  密码
     * @phone    联系电话
     * @sex     性别  默认男
     * @request   请求对象
     * return json
     * 备注：还缺少短信验证
     */
    public function register(Request $request){
        $user=array();
		if($request->isPost()){
//            $username=isset($_POST['username'])?trim($_POST['username']):'';
//            $password=isset($_POST['password'])?trim($_POST['password']):'';
            $phone=isset($_POST['phone'])?trim($_POST['phone']):'';
//            $sex=isset($_POST['sex'])?$_POST['sex']:0;
            $provider_id=isset($_POST['provider_id'])?trim($_POST['provider_id']):'';
            $type=isset($_POST['type'])?$_POST['type']:0;//1 技师  0 客户 （默认客户）
//            if(empty($username)){
//                $msg=array(
//                    "code"=>"10001",
//                    "msg"=>"用户名不能为空"
//                );
//                echo json_encode($msg);die;
//            }
//            $row=Db::table('tbl_c_customer')->where(array('s_user_name'=>$username))->find();  //查询用户名是否存在
//            if(!empty($row)){
//                $msg=array(
//                    "code"=>"30012",
//                    "msg"=>"该用户名已经被注册了"
//                );
//                echo json_encode($msg);die;
//            }
//            if(empty($password)){
//                $msg=array(
//                    "code"=>"10002",
//                    "msg"=>"密码不能为空"
//                );
//                echo json_encode($msg);die;
//            }
            if(empty($phone)){
                    $msg=array(
                    "code"=>"10003",
                    "msg"=>"联系电话能为空"
                );
                echo json_encode($msg);die;
            }
//            if(strlen($password)<6){
//                $msg=array(
//                    "code"=>"10004",
//                    "msg"=>"密码最少为6位"
//                );
//                echo json_encode($msg);die;
//            }
            $rst=Db::table('tbl_c_customer')->where(array('s_phone'=>$phone))->find();
            if(!empty($rst)){
                $msg=array(
                    "code"=>"30004",
                    "msg"=>"该手机号已经被注册了"
                );
                echo json_encode($msg);die;
            }
//            $user['s_user_name']=$username;
//            $user['s_password']=md5($password."jishi");
            $user['s_phone']=$phone;
            $user['s_password']=$provider_id;//存放技师注册填写的ID
//            $user['s_sex']=$sex;
            $user['s_type']=$type;
            $user['s_create_time']=time();
            $maxid=getMax('tbl_c_customer',"i_id");
            $user['i_id']=$maxid+100;
//            Db::connect('mysql://root:123@127.0.0.1:3306/jishi#utf8');
            $res=Db::table('tbl_c_customer')->insert($user);
            if($res){
                $msg=array(
                    "code"=>"20001",
                    "msg"=>"注册成功"
                );
                echo json_encode($msg);die;
            }
        }else{
            $method=$request->method();
            $msg=array(
                "code"=>"10005",
                "msg"=>"不支持".$method."请求"
            );
            echo json_encode($msg);die;
        }
	}

    /*
     * 用户登录
     * @username   登录用户名
     * @password  登录密码
     * @return json
     */

    public function login(Request $request){
        if($request->isPost()){
            $username=isset($_POST['username'])?trim($_POST['username']):'';
            $password=isset($_POST['password'])?trim($_POST['password']):'';
//            $code=isset($_POST['code'])?trim($_POST['code']):'';
            $userinfo=Db::table('tbl_c_customer')->where("s_user_name","=","$username")->find();
            if(!empty($userinfo)){
                if($userinfo['s_password']!=md5(trim($password)."jishi")){
                    $result=array(
                        "code"=>"10006",
                        "msg"=>"密码不正确"
                    );
                    echo json_encode($result);die;
                }
                $phone=$userinfo['s_phone'];
//                $verify=Db::table('sms')->where("phone","=","$phone")->order('screate_time')->find();
//                if($code!=$verify){
//                    $result=array(
//                        "code"=>"10008",
//                        "msg"=>"验证码不正确"
//                    );
//                    echo  json_encode($result);die;
//                }
                if($userinfo['s_user_name']==$username && $userinfo['s_password']==md5(trim($password)."jishi")){
                    if($userinfo['s_status']==1){
                        $result=array(
                            "code"=>"30011",
                            "msg"=>"已经登录，请不要重复登录"
                        );
                        echo json_encode($result);die;
                    }
                    $rst=Db::table('tbl_c_customer')->where(array('s_user_name'=>$username))->update(['s_status'=>1]);
                    if($rst){
						$_SESSION['user']=$username;
                        $result=array(
                            "code"=>"20002",
                            "msg"=>"登录成功",
                            "info"=>$userinfo
                        );
                        echo json_encode($result);die;
                    }
                }
            }else{
                $result=array(
                    "code"=>"10007",
                    "msg"=>"用户名不正确"
                );
                echo  json_encode($result);die;
            }
            }else{
                     $method=$request->method();
                     $result=array(
                        "code"=>"10005",
                        "msg"=>"不支持".$method."请求"
                     );
                echo json_encode($result);die;
            }
        }


    /*
     * function  退出登录状态
     * note   根据s_status字段判断当前用户的登录状态
     */
    public function loginout(Request $request){
        if($request->isPost()){
            $status=isset($_POST['status'])?$_POST['status']:'';
            $phone=isset($_POST['phone'])?$_POST['phone']:'';
            $cus_info=Db::table('tbl_c_customer')->where(array('s_phone'=>$phone))->find();
            $username=$cus_info['s_user_name'];
            if($cus_info['s_status']==0){  //判断当前用户的登录状态
                $result=array(
                    "code"=>"30010",
                    "msg"=>"已经退出登录了"
                );
                echo json_encode($result);die;
            }
            if($status==0){  //判断提交过来要修改的状态
                $res=Db::table('tbl_c_customer')->where(array('s_phone'=>$phone))->update(['s_status'=>0]);
                if($res){
					session('user',null);
                    $result=array(
                        "code"=>"40002",
                        "msg"=>"登出成功"
                    );
                    echo json_encode($result);die;
                }
            }else{
                $result=array(
                    "code"=>"30011",
                    "msg"=>"请求参数错误"
                );
                echo json_encode($result);die;
            }

        }else{
            $method=$request->method();
            $result=array(
                "code"=>"10005",
                "msg"=>"不支持".$method."请求"
            );
            echo json_encode($result);die;
        }
    }

    /*
     * request  请求对象
     * username  用户名
     * note  s_type 用户类型字段
     * function 判断用户类型（技师/客户）
     */
    public  function  check_user(Request $request){
        if($request->isPost()){
            $username=isset($_POST['username'])?$_POST['username']:'';
            $reg='/(\d{3}(\.\d+)?)/is';//匹配数字的正则表达式
            preg_match_all($reg,$username,$rst);
            $user_id=$rst[0][0];
            if(!empty($username)){
                $userinfo=Db::table('tbl_c_customer')->where(array('s_user_name'=>$username))->find();
                if(empty($userinfo)){
                    $result=array(
                        "code"=>"40040",
                        "msg"=>"该用户不存在"
                    );
                    echo json_encode($result);die;
                }else{
                    if($userinfo['s_type']=1 && $user_id==$userinfo['i_id']){
                        $result=array(
                            "code"=>"50010",
                            "msg"=>"该用户是技师"
                        );
                        echo json_encode($result);die;
                    }else{
                        $result=array(
                            "code"=>"40042",
                            "msg"=>"该用户是客户"
                        );
                        echo json_encode($result);die;
                    }
                }
            }
        }else{
            $method=$request->method();
            $result=array(
                "code"=>"10005",
                "msg"=>"不支持".$method."请求"
            );
            echo json_encode($result);die;
        }
    }


    /*
	 *
	 *request 请求对象
	 *user   用户名
	 *function  用户的登陆状态
	 */
    public function login_status(Request $request){
        if($request->isPost()){
			$user=isset($_POST['username'])?$_POST['username']:'';
			if(empty($user)){
				$result=array(
                "code"=>"50001",
                "msg"=>"用户名为空"
                   );
				echo json_encode($result);die;
				}
				if($user==$_SESSION['user']){
				  $res=Db::table('tbl_c_customer')->where(array('s_user_name'=>$user))->find();
				  $result=array(
                "code"=>"40020",
                "msg"=>"已经是登陆状态",
					  "info"=>$res
            );
				  echo json_encode($result);die;
				}else{
				$result=array(
                "code"=>"50002",
                "msg"=>"用户已经退出"
                   );
				echo json_encode($result);die;
				}

		}else{
		$method=$request->method();
            $result=array(
                "code"=>"10005",
                "msg"=>"不支持".$method."请求"
            );
            echo json_encode($result);die;
		}
	}


    /*
     * 短信找回密码
     */
    public function find_password(){
        echo "找回密码";
    }



}
