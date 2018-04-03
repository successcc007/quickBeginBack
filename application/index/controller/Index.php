<?php
namespace app\index\controller;

use think\Db;
use think\Request;

class Index
{
    /*
     * 查询所有课程
     * */
    public function GetClasses()
    {
        $classInfo = Db::table('tclass')->select();
        if (empty($classInfo)) {
            $result = array(
                "code" => "1001",
                "msg" => "没有课程"
            );
            echo json_encode($result);
            die;
        } else {
            $result = array(
                "msg" => "查询成功",
                "info" => $classInfo
            );
            echo json_encode($result);
            die;
        }
    }

    /*
     * 查询单个课程
     * */
    public function  GetClassSingle(Request $request)
    {
        $cId = $_GET['cId'];
        $res = Db::table("tclass")->where("id", "=", "$cId")->find();
        if (!empty($res)) {
            $result = array(
                "msg" => "查询成功",
                "info" => $res
            );
            echo json_encode($result);
            die;
        } else {
            $result = array(
                "msg" => "查询失败"
            );
            echo json_encode($result);
            die;
        }
    }


    /*
     * 新建课程
     * */
    public function AddClass(Request $request)
    {
        if ($request->isPost()) {
            $name = $_POST['name'];
            $adress = $_POST['adress'];
            $stime = $_POST['time'];
            $tId = $_POST['tId'];
            //$qcodeImage=$_POST['qcodeImage'];
            $res = Db::table("tclass")->insert(['name' => $name, 'adress' => $adress, 'stime' => $stime, 'tId' => $tId]);
            // $res = 1;
            if ($res) {
                $id = Db::table('tclass')->getLastInsID();
                $result = array(
                    "code" => "10005",
                    "msg" => "添加成功",
                    "info" => $id
                );
                echo json_encode($result);
                die;
            } else {
                $result = array(
                    "code" => "10005",
                    "msg" => "添加失败"
                );
                echo json_encode($result);
                die;
            }
        } else {
            $method = $request->method();
            $msg = array(
                "code" => "10005",
                "msg" => "不支持" . $method . "请求"
            );
            echo json_encode($msg);
            die;
        }

    }

    /*
     * 计算距离
     * */
    private function getDistance($lat1, $lng1, $lat2, $lng2)
    {
        $earthRadius = 6367000; //approximate radius of earth in meters
        $lat1 = ($lat1 * pi()) / 180;
        $lng1 = ($lng1 * pi()) / 180;
        $lat2 = ($lat2 * pi()) / 180;
        $lng2 = ($lng2 * pi()) / 180;
        $calcLongitude = $lng2 - $lng1;
        $calcLatitude = $lat2 - $lat1;
        $stepOne = pow(sin($calcLatitude / 2), 2) + cos($lat1) * cos($lat2) * pow(sin($calcLongitude / 2), 2);
        $stepTwo = 2 * asin(min(1, sqrt($stepOne)));
        $calculatedDistance = $earthRadius * $stepTwo;
        return round($calculatedDistance);
    }

    /*
     * 更新位置
     * */
    public function SetPosition(Request $request)
    {
        $longitude = $_GET['longitude'];
        $latitude = $_GET['latitude'];
        $id = $_GET['cId'];
        $type = $_GET['type'];
        $res="";
        if ($type == 1) {
            $res = Db::table("tclass")->where("id", "=", $id)->update(['positionX' => $longitude, 'positionY' => $latitude]);
        } elseif ($type == 0) {
            $res1 = DB::table("tclass")->where("id", "=", $id)->find();
            $longitudeC = $res1["positionX"];
            $latitudeC = $res1["positionY"];
            $miles = $this->getDistance($longitudeC,$latitudeC,$longitude,$latitude);
            $status= "正常";
            if($miles>500){
                $status="迟到";
            }
            $res = Db::table("stuandclass")->where("cId", "=", $id)->update(['status' => $status]);
        }
        if ($res) {
            $result = array(
                "msg" => "添加位置成功"
            );
            echo json_encode($result);
            die;
        } else {
            $result = array(
                "msg" => "添加位置失败"
            );
            echo json_encode($result);
            die;
        }
    }

    /*
     * 上传二维码
     * todo fies上传了
     * */
    public function AddImgQrPath(Request $request)
    {
        $imgPath = $_POST['imgPath'];
        $id = $_POST['id'];
        $arrType = explode('.', $imgPath);
        $type = end($arrType);
        $newDir = "../../../../upload/QR" . $id . '.' . $type;
        if (true) {
            $res = Db::table("tclass")->where("id", "=", $id)->update(['qcodeImage' => $newDir]);
            if ($res) {
                $result = array(
                    "msg" => "添加二维码成功"
                );
                echo json_encode($result);
                die;
            } else {
                $result = array(
                    "msg" => "添加二维码失败"
                );
                echo json_encode($result);
                die;
            }
        }
    }


    /*
     * 删除课程
     * */
    public function DeleteClass()
    {
        $id = $_GET['id'];
        $res = DB::table("tclass")->where("id", "=", $id)->delete();
        if ($res) {
            $result = array(
                "msg" => "删除成功"
            );
            echo json_encode($result);
            die;
        } else {
            $result = array(
                "msg" => "删除失败"
            );
            echo json_encode($result);
            die;
        }
    }

    /*
     * 查询签到
     * */
    public function  GetSignInfo(Request $request)
    {
        $classid = $_GET['cId'];
        $signInfo = DB::table("signinfo")->where("cId", "=", $classid)->select();
        if (!empty($signInfo)) {
            $result = array(
                "msg" => "查询成功",
                "info" => $signInfo
            );
            echo json_encode($result);
            die;
        } else {
            $result = array(
                "msg" => "查询失败"
            );
            echo json_encode($result);
            die;
        }
    }

    /*
     * 查询留言
     * */
    public function  GetMessages(Request $request)
    {
        $cId = $_GET['cId'];
        $messages = DB::table('message')->where("cId", "=", "$cId")->select();
        if (empty($messages)) {
            $result = array(
                "msg" => "查询失败"
            );
            echo json_encode($result);
            die;
        } else {
            $result = array(
                "msg" => "查询成功",
                "info" => $messages
            );
            echo json_encode($result);
            die;
        }
    }

    /*
   * 查询成绩
   * */
    public function  GetScores(Request $request)
    {
        $cId = $_GET['cId'];
        $scores = DB::table('scores')->where("cId", "=", "$cId")->select();
        if (empty($scores)) {
            $result = array(
                "msg" => "查询失败"
            );
            echo json_encode($result);
            die;
        } else {
            $result = array(
                "msg" => "查询成功",
                "info" => $scores
            );
            echo json_encode($result);
            die;
        }
    }

    /*
     * login
     * */
    public function  Login(Request $request)
    {
        $uname = $_POST['uname'];
        $pword = $_POST['pword'];
        $res = DB::table("users")->where(array('uname' => $uname, 'pword' => $pword))->find();
        if (!empty($res)) {
            $result = array(
                "msg" => "登录成功",
                "info" => $res
            );
            echo json_encode($result);
            die;
        } else {
            $result = array(
                "msg" => "登录失败",
            );
            echo json_encode($result);
            die;
        }
    }

    /*
     * 学生加入课程
     * */
    public function AttendClass(Request $request)
    {
        $sId = $_GET['sId'];
        $cId = $_GET['cId'];
        if (empty($sId) || empty($cId)) {
            $result = array(
                "msg" => "学生id或者课程id为空"
            );
            echo json_encode($result);
            die;
        }
        $res = DB::table("stuandclass")->insert(['sId' => $sId, 'cId' => $cId]);
        if ($res) {
            $result = array(
                "msg" => "加入课程成功",
            );
            echo json_encode($result);
            die;
        } else {
            $result = array(
                "msg" => "加入失败",
            );
            echo json_encode($result);
            die;
        }
    }

}
