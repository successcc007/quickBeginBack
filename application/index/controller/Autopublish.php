<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/14
 * Time: 6:55
 */

namespace app\index\controller;
include_once "CurlMethod.php";


class AutoPublish
{
    /*
     * 登录
     * 参数：用户名,密码
     * */
    public function LoginIn(Request $request)
    {
        $curl = new CurlMethod();
        if ($request->isPost()) {

            $username = isset($_POST['username']) ? trim($_POST['username']) : '';
            $password = isset($_POST['password']) ? trim($_POST['password']) : '';
            $data_login = array(
                'loginAttempt' => 'true',
                'lang' => 'en-us',
                'return' => '',
                'site' => '',
                'ad' => '',
                //'rememberMe' => 'remember',
                //'email' => 'bp1@biteveryday.com',
                //'password' => 'Ped.,_eo123'
                'email' => $username,
                'password' => $password
            );
            $cookie = dirname(__FILE__) . "/" . 'cookieSave.txt'; //设置cookie保存的路径
            $url_login = "https://my.backpage.com/classifieds/central/index";
            $rs_login = $curl->login_post($url_login, $cookie, $data_login);
            if (!$rs_login) {
                $result = array(
                    "code" => "10001",
                    "msg" => "login fail"
                );
            } else {
                $result = array(
                    "code" => "10002",
                    "msg" => "login succeed"
                );
            }
            echo json_encode($result);
            die;
        }
    }

    /*
     * 城市选择
     * 参数：城市，cookie
     * $city = "Seattle"
     * $cookie = $_POST['cookie']
     * */
    public function  CitySelect(Request $request)
    {
        $curl = new CurlMethod();
        $url_allCity = "http://www.backpage.com/";
        if ($request->isPost()) {
            $city = $_POST['city'];
            $cookie = $_POST['cookie'];
            $content_allCity = $curl->get_content($url_allCity, $cookie);
            if (empty($content_allCity)) {
                $result = array(
                    "code" => "20001",
                    "msg" => "allcity fail"
                );
            } else {
                $pattern_City = "/<a href=\"(.*)?\">" . $city . "<\/a>/";
                preg_match_all($pattern_City, $content_allCity, $arr_City);
                $url_city = $arr_City[1][0];
                if (empty($url_city)) {
                    $result = array(
                        "code" => "21001",
                        "msg" => "city url fail"
                    );
                } else {
                    $result = array(
                        "code" => "21002",
                        "msg" => "city url success",
                        "data" => $url_city
                    );
                }
            }
        } else {
            $result = array(
                "code" => "20000",
                "msg" => "非post请求"
            );
        }
        echo json_encode($result);
        die;
    }

    /*
     * ad
     *parameter：cookie，url_city
    */
    public function AdPost(Request $request)
    {
        $curl = new CurlMethod();
        if ($request->isPost()) {
            $url_city = $_POST['url_city'];
            $cookie = $_POST['cookie'];
            $content_city = $curl->get_content($url_city, $cookie);
            if (empty($content_city)) {
                $result = array(
                    "code" => "30001",
                    "msg" => "city fail"
                );
            } else {
                $pattern_ad_url = "/<form name=\"formPost\" id=\"formPost\" action=\"(.*)?\" method=\"get\">/";
                $pattern_ad_u = "/<input type=\"hidden\" name=\"u\" value=\"(.*)?\">/";
                $pattern_ad_serverName = "/<input type=\"hidden\" name=\"serverName\" value=\"(.*)?\">/";
                preg_match_all($pattern_ad_url, $content_city, $arr_ad);
                preg_match_all($pattern_ad_u, $content_city, $arr_ad_u);
                preg_match_all($pattern_ad_serverName, $content_city, $arr_ad_serverName);
                $url_ad = $arr_ad[1][0];
                $data_u = $arr_ad_u[1][0];
                $data_serverName = $arr_ad_serverName[1][0];
                $data_ad = array(
                    'u' => $data_u,
                    'serverName' => $data_serverName
                );
                $data = array(
                    'url_ad' => $url_ad,
                    'data_ad' => $data_ad
                );
                $result = array(
                    "code" => "30002",
                    "msg" => "city succeed",
                    "data" => $data
                );
            }
        } else {
            $result = array(
                "code" => "20000",
                "msg" => "非post请求"
            );
        }
        echo json_encode($result);
        die;
    }

    /*
     * dating
     * parameter:cookie,url_ad,data_ad,section
     * $section = 'dating';
     * */

    public function SectionSelect(Request $request)
    {
        $curl = new CurlMethod();
        if ($request->isPost()) {
            $cookie = $_POST['cookie'];
            $section = $_POST['section'];
            $url_ad = $_POST['url_ad'];
            $data_ad = $_POST['data_ad'];
            $content_ad = $curl->get_content_post($url_ad, $cookie, $data_ad);
            if (empty($content_ad)) {
                $result = array(
                    "code" => "40001",
                    "msg" => "ad fail"
                );
            } else {
                $pattern_dating_url = "/<a href=\"(.*)?\" data-section=\"(.*)?\" data-name=\"" . $section . "\">" . $section . "<\/a>/";
                preg_match_all($pattern_dating_url, $content_ad, $arr_dating);
                $url_dating = substr($url_ad, 0, strpos($url_ad, '.com') + 4) . $arr_dating[1][0];
                $result = array(
                    "code" => "40002",
                    "msg" => "ad susccess",
                    "data" => $url_dating
                );
            }
        } else {
            $result = array(
                "code" => "40000",
                "msg" => "非post请求"
            );
        }
        echo json_encode($result);
        die;
    }

    /*
     * Category
     * women man
     * parameter:category,cookie,url_dating,url_ad
     *$category = 'women seeking men';
    */
    public function CategorySelect(Request $request)
    {
        $curl = new CurlMethod();
        if ($request->isPost()) {
            $cookie = $_POST['cookie'];
            $url_ad = $_POST['url_ad'];
            $category = $_POST['category'];
            $url_dating = $_POST['url_dating'];
            $content_dating = $curl->get_content($url_dating, $cookie);
            if (empty($content_dating)) {
                $result = array(
                    "code" => "50001",
                    "msg" => "dating fail"
                );
            } else {
                echo 'dating succeed<br>';
                $pattern_women_men_url = "/<a href=\"(.*)?\" data-category=\"(.*)?\" data-name=\"" . $category . "\" data-useRegions=\"yes\" data-disclaimer=\"yes\">" . $category . "<\/a>/";
                preg_match_all($pattern_women_men_url, $content_dating, $arr_women_men);
                $url_women_men = substr($url_ad, 0, strpos($url_ad, '.com') + 4) . $arr_women_men[1][0];
                $result = array(
                    "code" => "50002",
                    "msg" => "dating success",
                    "data" => $url_women_men
                );
            }
        } else {
            $result = array(
                "code" => "50000",
                "msg" => "非post请求"
            );
        }
        echo json_encode($result);
        die;
    }

    /*
     * location
     * parameter:cookie,url_women_men,location,url_ad
     * $location = 'Bellingham';
    */
    public function LocationSelect(Request $request)
    {
        $curl = new CurlMethod();
        if ($request->isPost()) {
            $cookie = $_POST['cookie'];
            $url_ad = $_POST['url_ad'];
            $location = $_POST['location'];
            $url_women_men = $_POST['url_women_men'];
            $content_women_men = $curl->get_content($url_women_men, $cookie);
            if (empty($content_women_men)) {
                $result = array(
                    "code" => "60001",
                    "msg" => "location fail "
                );
            } else {
                $pattern_location = "/<a href=\"(.*)?\" data-superRegion=\"" . $location . "\" data-multiple=\"no\">" . $location . "<\/a>/";
                preg_match_all($pattern_location, $content_women_men, $arr_location);
                $url_local = substr($url_ad, 0, strpos($url_ad, '.com') + 4) . $arr_location[1][0];
                $result = array(
                    "code" => "60002",
                    "msg" => "location success",
                    "data" => $url_local
                );
            }
        } else {
            $result = array(
                "code" => "60000",
                "msg" => "非post请求"
            );
        }
        echo json_encode($result);
        die;
    }

    /*
     * continue_1
     * parameter:cookie,url_local
     * */
    public function continue_1(Request $request)
    {
        $curl = new CurlMethod();
        if ($request->isPost()) {
            $cookie = $_POST['cookie'];
            $url_local = $_POST['url_local'];
            $content_local_1 = $curl->get_content($url_local, $cookie);
            if (empty($content_local_1)) {
                $result = array(
                    "code" => "70001",
                    "msg" => "local_1 fail"
                );
            } else {
                $pattern_continue_1_url = "/<a href=\"(.*)?\">here<\/a>/is";
                preg_match_all($pattern_continue_1_url, $content_local_1, $arr_continue_1);
                $url_continue_1 = $arr_continue_1[1][0];
                $result = array(
                    "code" => "70002",
                    "msg" => "local_1 success",
                    "data" => $url_continue_1
                );
            }
        } else {
            $result = array(
                "code" => "70000",
                "msg" => "非post请求"
            );
        }
        echo json_encode($result);
        die;
    }

    /*
    * continue_1
    * parameter:cookie,url_continue_1
    * */
    public function continue_2(Request $request)
    {
        $curl = new CurlMethod();
        if ($request->isPost()) {
            $cookie = $_POST['cookie'];
            $url_continue_1 = $_POST['url_continue_1'];
            $content_local = $curl->get_content($url_continue_1, $cookie);
            if (empty($content_local)) {
                $result = array(
                    "code" => "80001",
                    "msg" => "local fail"
                );
            } else {
                $pattern_continue_url = "/<form name=\"formDisclaimer\" method=\"post\" action=\"(.*)?\">/";
                $pattern_continue_disc = "/<input type=\"hidden\" name=\"disc\" value=\"(.*)?\">/";
                $pattern_continue_category = "/<input type=\"hidden\" name=\"category\" value=\"(.*)?\">/";
                $pattern_continue_section = "/<input type=\"hidden\" name=\"section\" value=\"(.*)?\">/";
                $pattern_continue_serverName = "/ <input type=\"hidden\" name=\"serverName\" value=\"(.*)?\">/";
                $pattern_continue_superRegion = "/<input type=\"hidden\" name=\"superRegion\" value=\"(.*)?\">/";
                $pattern_continue_u = "/<input type=\"hidden\" name=\"u\" value=\"(.*)?\">/";
                preg_match_all($pattern_continue_url, $content_local, $arr_continue);
                preg_match_all($pattern_continue_disc, $content_local, $arr_disc);
                preg_match_all($pattern_continue_category, $content_local, $arr_category);
                preg_match_all($pattern_continue_section, $content_local, $arr_section);
                preg_match_all($pattern_continue_serverName, $content_local, $arr_serverName);
                preg_match_all($pattern_continue_superRegion, $content_local, $arr_superRegion);
                preg_match_all($pattern_continue_u, $content_local, $arr_u);
                $url_continue = $arr_continue[1][0];
                $data_disc = $arr_disc[1][0];
                $data_category = $arr_category[1][0];
                $data_section = $arr_section[1][0];
                $data_serverName = $arr_serverName[1][0];
                $data_superRegion = $arr_superRegion[1][0];
                $data_u = $arr_u[1][0];
                $data_continue = array(
                    'disc' => $data_disc,
                    'category' => $data_category,
                    'section' => $data_section,
                    'serverName' => $data_serverName,
                    'superRegion' => $data_superRegion,
                    'u' => $data_u
                );
                $data = array(
                    "url_continue" => $url_continue,
                    "data_continue" => $data_continue
                );
                $result = array(
                    "code" => "80002",
                    "msg" => "local success",
                    "data" => $data
                );
            }
        } else {
            $result = array(
                "code" => "80000",
                "msg" => "非post请求"
            );
        }
        echo json_encode($result);
        die;
    }

    /*
     * publish_1
     * parameter:cookie,url_continue,data_continue
     * */
    public function  publish_1(Request $request)
    {
        $curl = new CurlMethod();
        if ($request->isPost()) {
            $data_continue = $_POST['data_continue'];
            $url_continue = $_POST['url_continue'];
            $cookie = $_POST['cookie'];
            $content_continue = $curl->get_content_post($url_continue, $cookie, $data_continue);
            if (empty($content_continue)) {
                $result = array(
                    "code" => "90001",
                    "msg" => "continue fail"
                );
            } else {
                $pattern_publish_1_url = "/<form name=\"f\" method=\"post\" action=\"(.*)?\" enctype=\"multipart\/form-data\" onsubmit=\'(.*)?\'>/";
                $pattern_publish_1_u = "/<input type=\"hidden\" name=\"u\" value=\"(.*)?\">/";
                $pattern_publish_1_serverName = "/<input type=\"hidden\" name=\"serverName\" value=\"(.*)?\">/";
                $pattern_publish_1_lang = "/<input type=\"hidden\" name=\"lang\" value=\"(.*)?\">/";
                $pattern_publish_1_section = "/<input type=\"hidden\" name=\"section\" value=\"(.*)?\">/";
                $pattern_publish_1_category = "/<input type=\"hidden\" name=\"category\" value=\"(.*)?\">/";
                $pattern_publish_1_disc = "/<input type=\"hidden\" name=\"disc\" value=\"(.*)?\">/";
                $pattern_publish_1_region = "/<input type=\"hidden\" name=\"region\" value=\"(.*)?\">/";
                $pattern_publish_1_affiliate = "/<input type=\"hidden\" name=\"affiliate\" value=\"(.*)?\">/";
                $pattern_publish_1_pid = "/<input type=\"hidden\" name=\"pid\" value=\"(.*)?\">/";
                $pattern_publish_1_nextPage = "/<input type=\"hidden\" name=\"nextPage\" value=\"(.*)?\">/";
                $pattern_publish_1_contactPhone = "/<input type=\"tel\" name=\"contactPhone\" class=\"required mediumInput\" maxlength=\"40\" value=\"(.*)?\">/";
                $pattern_publish_1_socialMediaUrl = "/<input type=\"text\" name=\"socialMediaUrl\" class=\"mediumInput required\" value=\"(.*)?\">/";
                $pattern_publish_1_age = "/<input type=\"number\" name=\"age\" class=\"required smallInput\" value=\"(.*)?\">/";
                $pattern_publish_1_email = "/<input type=\"email\" name=\"email\" class=\"mediumInput\" value=\"(.*)?\" disabled=\"true\">/";
                $pattern_publish_1_allowReplies = "/ <input type=\"radio\" name=\"allowReplies\" value=\"(.*)?\" checked>/";
                $pattern_publish_1_baseMarket = "/<input type=\"checkbox\" name=\"baseMarket\" id=\"baseMarket\" value=\"(.*)?\" data-basePrice=\"1.00\" disabled checked>/";

                preg_match_all($pattern_publish_1_url, $content_continue, $arr_publish_1_url);
                preg_match_all($pattern_publish_1_u, $content_continue, $arr_publish_1_u);
                preg_match_all($pattern_publish_1_serverName, $content_continue, $arr_publish_1_serverName);
                preg_match_all($pattern_publish_1_lang, $content_continue, $arr_publish_1_lang);
                preg_match_all($pattern_publish_1_section, $content_continue, $arr_publish_1_section);
                preg_match_all($pattern_publish_1_category, $content_continue, $arr_publish_1_category);
                preg_match_all($pattern_publish_1_disc, $content_continue, $arr_publish_1_disc);
                preg_match_all($pattern_publish_1_region, $content_continue, $arr_publish_1_region);
                preg_match_all($pattern_publish_1_affiliate, $content_continue, $arr_publish_1_affiliate);
                preg_match_all($pattern_publish_1_pid, $content_continue, $arr_publish_1_pid);
                preg_match_all($pattern_publish_1_nextPage, $content_continue, $arr_publish_1_nextPage);
                preg_match_all($pattern_publish_1_contactPhone, $content_continue, $arr_publish_1_contactPhone);
                preg_match_all($pattern_publish_1_socialMediaUrl, $content_continue, $arr_publish_1_socialMediaUrl);
                preg_match_all($pattern_publish_1_age, $content_continue, $arr_publish_1_age);
                preg_match_all($pattern_publish_1_email, $content_continue, $arr_publish_1_email);
                preg_match_all($pattern_publish_1_allowReplies, $content_continue, $arr_publish_1_allowReplies);
                preg_match_all($pattern_publish_1_baseMarket, $content_continue, $arr_publish_1_baseMarket);

                $url_publish_1 = $arr_publish_1_url[1][0];
                $data_u = $arr_publish_1_u[1][0];
                $data_serverName = $arr_publish_1_serverName[1][0];
                $data_lang = $arr_publish_1_lang[1][0];
                $data_section = $arr_publish_1_section[1][0];
                $data_category = $arr_publish_1_category[1][0];
                $data_disc = $arr_publish_1_disc[1][0];
                $data_region = $arr_publish_1_region[1][0];
                $data_affiliate = $arr_publish_1_affiliate[1][0];
                $data_pid = $arr_publish_1_pid[1][0];
                $data_nextPage = $arr_publish_1_nextPage[1][0];
                $data_contactPhone = $arr_publish_1_contactPhone[1][0];
                $data_socialMediaUrl = $arr_publish_1_socialMediaUrl[1][0];
                $data_age = $arr_publish_1_age[1][0];
                $data_email = $arr_publish_1_email[1][0];
                $data_allowReplies = $arr_publish_1_allowReplies[1][0];
                $data_baseMarket = $arr_publish_1_baseMarket[1][0];

                $data_publish_1 = array(
                    'u' => $data_u,
                    'serverName' => $data_serverName,
                    'lang' => $data_lang,
                    'section' => $data_section,
                    'category' => $data_category,
                    'disc' => $data_disc,
                    'region' => $data_region,
                    'affiliate' => $data_affiliate,
                    'pid' => $data_pid,
                    'nextPage' => $data_nextPage,
                    'contactPhone' => $data_contactPhone,
                    'socialMediaUrl' => $data_socialMediaUrl,
                    'age' => $data_age,
                    'email' => $data_email,
                    'allowReplies' => $data_allowReplies,
                    'baseMarket' => $data_baseMarket,
                    'acceptTerms' => true
                );
                $data = array(
                    "url_publish_1" => $url_publish_1,
                    "data_publish_1" => $data_publish_1
                );
                $result = array(
                    "code" => "90002",
                    "msg" => "city url success",
                    "data" => $data
                );
                // $content_publish_1 = $curl->get_content_post($url_publish_1, $cookie, $data_publish_1);
                //todu,the last one
            }
        } else {
            $result = array(
                "code" => "90000",
                "msg" => "非post请求"
            );
        }
        echo json_encode($result);
        die;
    }
}