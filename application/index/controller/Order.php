<?php
namespace app\index\controller;
use think\Db;
use think\Paginator;
use think\Request;
class Order
{
    /*
     * @request    请求对象
     * @customer_id   客户id
     * @provider_id    技师id
     * @service_time   服务时间
     * @start_time     服务开始时间
     * @type           服务类型  in/ out
     * @order_price    下单金额
     *function       生成订单
     */
    public function order(Request $request){
        $order=array();
        $result=array();
        if($request->isPost()){
            $order['i_customer_id']=isset($_POST['customer_id'])?$_POST['customer_id']:'';//客户ID
            if(empty(trim($order['i_customer_id']))){
                $result['code']=30001;
                $result['msg']="缺少参数";
                echo json_encode($result);die;
            }
            $order['i_provider_id']=isset($_POST['provider_id'])?$_POST['provider_id']:'';
            if(empty(trim($order['i_provider_id']))){
                $result['code']=30001;
                $result['msg']="缺少参数";
                echo json_encode($result);die;
            }
            $order['t_service_time']=isset($_POST['service_time'])?$_POST['service_time']:'';
            if(empty(trim($order['t_service_time']))){
                $result['code']=30001;
                $result['msg']="缺少参数";
                echo json_encode($result);die;
            }
            $start_time=isset($_POST['start_time'])?$_POST['start_time']:'';//服务开始时间(时间戳)
            $order['dt_service_start_time']=date('Y-m-d H:i:s',$start_time);
            if(empty(trim($order['dt_service_start_time']))){
                $result['code']=30001;
                $result['msg']="缺少参数";
                echo json_encode($result);die;
            }
            $order['s_type']=isset($_POST['type'])?$_POST['type']:'in';
            if(empty(trim($order['s_type']))){
                $result['code']=30001;
                $result['msg']="缺少参数";
                echo json_encode($result);die;
            }
            $order['i_order_price']=isset($_POST['order_price'])?$_POST['order_price']:'';
            if(empty(trim($order['i_order_price']))){
                $result['code']=30001;
                $result['msg']="缺少参数";
                echo json_encode($result);die;
            }
            $order['dt_order_time']=date('Y-m-d H:i:s',time());  //下单时间
            $maxid=getMax('tbl_c_order',"i_id");
            $order['i_id']=$maxid+100;
            $res=Db::table('tbl_c_order')->insert($order);
            if($res){
                $order_info=Db::table('tbl_c_order')->where(array('i_customer_id'=>$order['i_customer_id'],'dt_order_time'=>$order['dt_order_time']))->select();
                $info=array();
                foreach($order_info as $v){
                    $provider_info=Db::table('tbl_c_provider')->where(array('i_id'=>$v['i_provider_id']))->find();//技师信息
                    if(empty($provider_info)){
                        $result['code']=20048;
                        $result['msg']="该技师不存在";
                        echo json_encode($result);die;
                    }
                    $studio_info=Db::table('tbl_c_studio')->where(array('i_id'=>$provider_info['i_studio_id']))->find();
                    $v['provider_name']=$provider_info['s_name'];//技师姓名
                    $v['studio_name']=$studio_info['s_name'];//门店名称
                    $info[]=$v;
                }
                $result['code']=20005;
                $result['msg']="下单成功";
                $result['info']=$info;
                echo json_encode($result);die;
            }else{
                $result['code']=10009;
                $result['msg']="下单失败";
                echo json_encode($result);die;
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
    * @request  请求对象
    * 更新技师出发时间
    * return  json
    */
    public function update_provider_start_time(Request $request){
        if($request->isGet()){
            $order_id=$_GET['order_id'];   //传过来的订单id
            $provider_start_time=date('Y-m-d H:i:s',time());
            $res=Db::table('tbl_c_order')->where('i_id',$order_id)->update(['dt_start_time'=>$provider_start_time,'status'=>2]);
            if($res){
                $info=Db::table('tbl_c_order')->where(array('i_id'=>$order_id))->find();
                $result=array(
                    "code"=>"20006",
                    "msg"=>"更新成功",
                    "info"=>$info
                );
                echo json_encode($result);die;
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
     * @request  请求对象
     * 更新技师到达时间
     * return  json
     */
    public function update_provider_arrived_time(Request $request){
        if($request->isGet()){
            $order_id=$_GET['order_id'];   //传过来的订单id
            $provider_arrive_time=date('Y-m-d H:i:s',time());
            $res=Db::table('tbl_c_order')->where('i_id',$order_id)->update(['dt_provider_arrived_time'=>$provider_arrive_time,'status'=>2]);
            if($res){
                $info=Db::table('tbl_c_order')->where(array('i_id'=>$order_id))->find();
                $result=array(
                    "code"=>"20006",
                    "msg"=>"更新成功",
                    "info"=>$info
                );
                echo json_encode($result);die;
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
     * @request  请求对象
     * 更新customer到达时间
     * return  json
     */
    public function update_customer_arrive_time(Request $request){
        if($request->isGet()){
            $order_id=$_GET['order_id'];   //传过来的订单id
            $provider_arrive_time=date('Y-m-d H:i:s',time());
            $res=Db::table('tbl_c_order')->where('i_id',$order_id)->update(['dt_customer_arrived_time'=>$provider_arrive_time,'status'=>3]);
            if($res){
                $result=array(
                    "code"=>"20007",
                    "msg"=>"更新成功"
                );
                echo json_encode($result);die;
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
     * @request  请求对象
     * function  客户取消订单
     * return  json
     * note   1、先判断提交过来的修改状态值  2、然后判断服务类型  3、如果外卖则判断技师是否出发
     */
    public function cancel_order(Request $request){
        if($request->isGet()){
            $order_id=$_GET['order_id'];   //传过来的订单id
            $customer_id=$_GET['customer_id'];   //传过来的客户id
            $status=$_GET['status'];   //0 取消  1正常
            $cancel_time=date('Y-m-d H:i:s',time());
            if($status==0){
                $order_info=Db::table('tbl_c_order')->where(array('i_id'=>$order_id,'i_customer_id'=>$customer_id))->find();
                if($order_info['status']==0){
                    $result=array(
                        "code"=>"30002",
                        "msg"=>"订单已经取消了"
                    );
                    echo json_encode($result);die;
                }
                if($order_info['s_type']=='in'){
                    $res=Db::table('tbl_c_order')->where(array('i_id'=>$order_id,'i_customer_id'=>$customer_id))->update(['dt_canceled_time'=>$cancel_time,'status'=>0]);
                    if($res){
                        $result=array(
                            "code"=>"20009",
                            "msg"=>"订单取消成功",
                            "info"=>$order_info
                        );
                        echo json_encode($result);die;
                    }
                }else if($order_info['s_type']=='out'){
                    if(strtotime(!empty($order_info['dt_provider_arrive_time']) || !empty($order_info['dt_start_time'])) ){
                        $result=array(
                            "code"=>"30003",
                            "msg"=>"技师已出发，无法取消"
                        );
                        echo json_encode($result);die;
                    }else{
                        $res=Db::table('tbl_c_order')->where(array('i_id'=>$order_id,'i_customer_id'=>$customer_id))->update(['dt_canceled_time'=>$cancel_time]);
                        if($res){
                            $result=array(
                                "code"=>"20009",
                                "msg"=>"订单取消成功"
                            );
                            echo json_encode($result);die;
                        }
                    }
                }
            }else{
                $result=array(
                    "code"=>"30001",
                    "msg"=>"无效操作"
                );
                echo json_encode($result);die;
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
     * @request  请求对象
     * function  客户此次订单发表评论
     * return    json
     */
    public function customer_comment(Request $request){
        if($request->isPost()){
            $order_id=isset($_POST['id'])?$_POST['id']:'';//订单id
            $content=isset($_POST['content'])?$_POST['content']:'';//评论内容
            $res=Db::table('tbl_c_order')->where(array('id'=>$order_id))->update(['customer_note'=>$content]);
            if($res){
                $result=array(
                    "code"=>"20008",
                    "msg"=>"发表成功"
                );
                echo json_encode($result);die;
            }else{
                $result=array(
                    "code"=>"30005",
                    "msg"=>"请不要重复发表相同内容"
                );
                echo json_encode($result);die;
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
     * @request  请求对象
     * function  技师此次订单发表评论
     * return    json
     */
    public function provider_comment(Request $request){
        if($request->isPost()){
            $order_id=isset($_POST['id'])?$_POST['id']:'';//订单id
            $content=isset($_POST['content'])?$_POST['content']:'';//评论内容
            $res=Db::table('tbl_c_order')->where(array('id'=>$order_id))->update(['provider_note'=>$content]);
            if($res){
                $result=array(
                    "code"=>"20009",
                    "msg"=>"发表成功"
                );
                echo json_encode($result);die;
            }else{
                $result=array(
                    "code"=>"30006",
                    "msg"=>"请不要重复发表相同内容"
                );
                echo json_encode($result);die;
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
     * @request 请求对象
     * function 设置最终付款价格
     * return    json
     */
    public function confirm_pay(Request $request){
        if($request->isPost()){
            $order_id=isset($_POST['order_id'])?$_POST['order_id']:'';
            $price=isset($_POST['price'])?$_POST['price']:'';
            $order_info=Db::table('tbl_c_order')->where(array('i_id'=>$order_id))->find();//查询订单信息
            if($order_info['i_final_price']<=0){  //判断是否付款
                $pay_time=date('Y-m-d H:i:s',time());
                $res=Db::table('tbl_c_order')->where(array('i_id'=>$order_id))->update(['i_final_price'=>$price,'dt_paid_time'=>$pay_time,'status'=>4]);
                if($res){
                    $result=array(
                        "code"=>"40001",
                        "msg"=>"付款成功",
                        "info"=>$order_info
                    );
                    echo json_encode($result);die;
                }
            }else{
                $result=array(
                    "code"=>"30007",
                    "msg"=>"已经付过款了，请不要重复设置"
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
     * @request 请求对象
     * function  查询用户所有订单
     * return  json
     */
    public function order_list(Request $request){
        $result=array();
        if($request->isGet()){
            $user_id=isset($_GET['uid'])?$_GET['uid']:'';
            if(empty($user_id)){      ///判断是否传用户id过来
                $result=array(
                    "code"=>"30009",
                    "msg"=>"缺少用户参数"
                );
                echo json_encode($result);die;
            }
            $olist=Db::table('tbl_c_order')->where(array('i_customer_id'=>$user_id))->order('dt_order_time desc')->select();//订单列表
            $info=array();
            if(empty($olist)){
                $result=array(
                    "code"=>"30008",
                    "msg"=>"您暂时还没有订单"
                );
                echo json_encode($result);die;
            }else{
                foreach($olist as $v){
                    $provider_info=Db::table('tbl_c_provider')->where(array('i_id'=>$v['i_provider_id']))->find();//技师信息
                    if(empty($provider_info)){
                        $result['code']=20048;
                        $result['msg']="该技师不存在";
                        echo json_encode($result);die;
                    }
                    $studio_info=Db::table('tbl_c_studio')->where(array('i_id'=>$provider_info['i_studio_id']))->find();
                    $v['provider_name']=$provider_info['s_name'];//技师姓名
                    $v['studio_name']=$studio_info['s_name'];//门店名称
                    $info[]=$v;
                }
                $result['code']=40002;
                $result['msg']="查询成功";
                $result['info']=$info;
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
     * @request 请求对象
     * function  查询技师所有订单
     * return  json
     */
    public function order_provider_list(Request $request){
        $result=array();
        if($request->isGet()){
            $user_id=isset($_GET['uid'])?$_GET['uid']:'';
            if(empty($user_id)){      ///判断是否传用户id过来
                $result=array(
                    "code"=>"30009",
                    "msg"=>"缺少用户参数"
                );
                echo json_encode($result);die;
            }
            $olist=Db::table('tbl_c_order')->where(array('i_provider_id'=>$user_id))->order('dt_order_time desc')->select();//订单列表
            $info=array();
            if(empty($olist)){
                $result=array(
                    "code"=>"30008",
                    "msg"=>"您暂时还没有订单"
                );
                echo json_encode($result);die;
            }else{
                foreach($olist as $v){
                    $provider_info=Db::table('tbl_c_provider')->where(array('i_id'=>$v['i_provider_id']))->find();//技师信息
                    if(empty($provider_info)){
                        $result['code']=20048;
                        $result['msg']="该技师不存在";
                        echo json_encode($result);die;
                    }
                    $studio_info=Db::table('tbl_c_studio')->where(array('i_id'=>$provider_info['i_studio_id']))->find();
                    $v['provider_name']=$provider_info['s_name'];//技师姓名
                    $v['studio_name']=$studio_info['s_name'];//门店名称
                    $info[]=$v;
                }
                $result['code']=40002;
                $result['msg']="查询成功";
                $result['info']=$info;
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
     * request 请求对象
     * order_id 订单ID
     * function 订单状态
     */
    public function order_status(Request $request){
        if($request->isGet()){
            $order_id=isset($_GET['order_id'])?$_GET['order_id']:'';
            if(empty($order_id) || $order_id==null){
                $result=array(
                    "code"=>"40050",
                    "msg"=>"没有请求参数"
                );
                echo json_encode($result);die;
            }
            $order_info=Db::table('tbl_c_order')->where(array('i_id'=>$order_id))->find();
            if($order_info['status']==2){ //订单状态 （技师已到达）
                $result=array(
                    "code"=>"40051",
                    "msg"=>"技师已到达",
                    "info"=>$order_info
                );
                echo json_encode($result);die;
            }
            if($order_info['status']==3){ //订单状态 （客户已经到达）
                $result=array(
                    "code"=>"40052",
                    "msg"=>"客户已经到达",
                    "info"=>$order_info
                );
                echo json_encode($result);die;
            }
            if($order_info['status']==4){ //订单状态 （客户已付款）
                $result=array(
                    "code"=>"40053",
                    "msg"=>"客户已付款",
                    "info"=>$order_info
                );
                echo json_encode($result);die;
            }
            if($order_info['status']==0){ //订单状态 （客户已取消订单）
                $result=array(
                    "code"=>"40054",
                    "msg"=>"订单已取消",
                    "info"=>$order_info
                );
                echo json_encode($result);die;
            }
            if($order_info['status']==5){ //订单状态 （技师已出发）
                $result=array(
                    "code"=>"40055",
                    "msg"=>"技师已出发",
                    "info"=>$order_info
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
     * request 请求对象
     * order_id  订单ID
     * function  客户给技师评分
     */
    public function provider_score(Request $request){
        if($request->isGet()){
            $order_id=isset($_GET['order_id'])?$_GET['order_id']:'';
            $provider_score=isset($_GET['provider_score'])?$_GET['provider_score']:'';
            if(empty($order_id) || $order_id==null){
                $result=array(
                    "code"=>"40060",
                    "msg"=>"确少请求参数"
                );
                echo json_encode($result);die;
            }
            $res=Db::table('tbl_c_order')->where(array('i_id'=>$order_id))->update(['i_provider_score'=>$provider_score]);
            if($res){
                $result=array(
                    "code"=>"40061",
                    "msg"=>"客户提交评论成功"
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
     * request 请求对象
     * order_id  订单ID
     * function  技师给客户评分
     */
    public function customer_score(Request $request){
        if($request->isGet()){
            $order_id=isset($_GET['order_id'])?$_GET['order_id']:'';
            $customer_score=isset($_GET['customer_score'])?$_GET['customer_score']:'';
            if(empty($order_id) || $order_id==null){
                $result=array(
                    "code"=>"40060",
                    "msg"=>"确少请求参数"
                );
                echo json_encode($result);die;
            }
            $res=Db::table('tbl_c_order')->where(array('i_id'=>$order_id))->update(['i_customer_score'=>$customer_score]);
            if($res){
                $result=array(
                    "code"=>"40062",
                    "msg"=>"技师提交评论成功"
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



}
