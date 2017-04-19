<?php
/**
 * UC通信接口控制器
 */
namespace Web\Controller;
use Think\Controller;
header("Content-Type: text/html; charset=utf-8");
define('UC_CLIENT_PATH', SITE_PATH . 'thinkphp/Core/Library/Vendor/uc_client');

class ApiController extends Controller{
    const UC_CLIENT_VERSION = '1.6.0';
    const UC_CLIENT_RELEASE = '20110501';

    const API_DELETEUSER           = 1; // note 用户删除 API 接口开关
    const API_RENAMEUSER           = 1; // note 用户改名 API 接口开关
    const API_GETTAG               = 1; // note 获取标签 API 接口开关
    const API_SYNLOGIN             = 1; // note 同步登录 API 接口开关
    const API_SYNLOGOUT            = 1; // note 同步登出 API 接口开关
    const API_UPDATEPW             = 1; // note 更改用户密码 开关
    const API_UPDATEBADWORDS       = 1; // note 更新关键字列表 开关
    const API_UPDATEHOSTS          = 1; // note 更新域名解析缓存 开关
    const API_UPDATEAPPS           = 1; // note 更新应用列表 开关
    const API_UPDATECLIENT         = 1; // note 更新客户端缓存 开关
    const API_UPDATECREDIT         = 1; // note 更新用户积分 开关
    const API_GETCREDITSETTINGS    = 1; // note 向 UCenter 提供积分设置 开关
    const API_GETCREDIT            = 1; // note 获取用户的某项积分 开关
    const API_UPDATECREDITSETTINGS = 1; // note 更新应用积分设置 开关
    const API_ADDFEED              = 1; // note FEED推送 开关; UCenter HOME专用，要用这个功能，请在UCENTER后台把应用类型改为UCenter HOME

    const API_RETURN_SUCCEED   = '1';
    const API_RETURN_FAILED    = '-1';
    const API_RETURN_FORBIDDEN = '-2';

    /**
     * 构造方法，检测相关配置
     */
    public function __construct() {
        require_cache(SITE_PATH . '/conf/uc_config.php');
        if (!defined('UC_API')) {
            E('未发现uc配置文件');
        }
        require_cache(UC_CLIENT_PATH.'/client.php'); // 加载uc客户端主脚本
        $this->initRequest(); // 初始化请求
    }

    /**
     * 解析请求
     * @return bool
     */
    private function initRequest() {

        $get  = $post = array();
        $code = @$_GET['code'];
        parse_str(_uc_authcode($code, 'DECODE', UC_KEY), $get);

        if (get_magic_quotes_gpc()) {
            $get = _uc_stripslashes($get);
        }

        if (time() - $get['time'] > 3600) {
            exit('请求有效期已过');
        }
        if (empty($get)) {
            exit('非法请求');
        }

        $action = parse_name($get['action'], '1'); // 命名转为JAVA风格

        $xml = file_get_contents('php://input');
        if ($xml) {
            $post = xml_unserialize($xml);
        }
        
        if (method_exists($this, $action)) {
            exit($this->$action($get, $post));
        } else {
            exit($action . '方法未定义');
        }
    }

    /**
     * 此接口供仅测试连接。当 UCenter 发起 test 的接口请求时，如果成功获取到接口返回的 API_RETURN_SUCCEED 值，表示 UCenter 和应用通讯正常
     * @param $get
     * @param $post
     * @return string
     */
    private function test($get, $post) {
        return self::API_RETURN_SUCCEED;
    }

    /**
     * 当 UCenter 删除一个用户时，会发起 deleteuser 的接口请求，通知所有应用程序删除相应的用户
     * @param $get
     * @param $post
     * @return mixed
     */
    private function deleteuser($get, $post) {
        if (!self::API_DELETEUSER) {
            return self::API_RETURN_FORBIDDEN;
        }
        $uids = str_replace("'", '', stripslashes($get['ids']));

        // TODO:
        return self::API_RETURN_SUCCEED;
    }

    /**
     * 当 UCenter 更改一个用户的用户名时，会发起 renameuser 的接口请求，通知所有应用程序改名
     * @param $get
     * @param $post
     * @return mixed
     */
    private function renameuser($get, $post) {
        if (!self::API_RENAMEUSER) {
            return self::API_RETURN_FORBIDDEN;
        }

        // TODO:
        return self::API_RETURN_SUCCEED;
    }

    /**
     * 当用户更改用户密码时，此接口负责接受 UCenter 发来的新密码
     * @param $get
     * @param $post
     * @return string
     */
    private function updatepw($get, $post) {
        if (!self::API_UPDATEPW) {
            return self::API_RETURN_FORBIDDEN;
        }

        // TODO:
        return self::API_RETURN_SUCCEED;
    }

    /**
     * 如果应用程序存在标签功能，可以通过此接口把应用程序的标签数据传递给 UCenter
     * @param $get
     * @param $post
     * @return mixed
     */
    private function gettag($get, $post) {
        if (!self::API_GETTAG) {
            return self::API_RETURN_FORBIDDEN;
        }

        return $this->_serialize(array($get['id'], array()), 1);
    }

    private function _serialize($arr, $htmlon = 0) {
        return xml_serialize($arr, $htmlon);
    }

    /**
     * 如果应用程序需要和其他应用程序进行同步登录，此部分代码负责标记指定用户的登录状态
     * @param $get
     * @param $post
     * @return mixed
     */
    private function synlogin($get, $post) {
        //todo 你可以在这里直接写登陆该应用的代码，不必要用下面这种COOKIE方式
        if (!self::API_SYNLOGIN) {
            return self::API_RETURN_FORBIDDEN;
        }

        header('P3P: CP="CURa ADMa DEVa PSAo PSDo OUR BUS UNI PUR INT DEM STA PRE COM NAV OTC NOI DSP COR"');

        $uid      = intval($get['uid']);
        $username = $get['username'];
        
        $dir = dirname(__FILE__).'/test.txt';
        $re = fopen($dir, 'a');
        fwrite($re, json_encode(array($uid,$username, $get)));//将内容写入文件
        fwrite($re, "\r\n");//将内容写入文件
        fclose($re);
        
        if(($member = searchuser($username))) {
            session('user.sdkuser',$username);
            session('user.xsst_id',$member['id']);
            
            cookie('auth', _uc_authcode($uid . "\t" . $username, 'ENCODE'), array('prefix' => 'ucenter_'));
        } else {
            if (!function_exists('uc_get_user')) {
                loaducenter();
            }
            $user = uc_get_user($uid, 1);
            if ($user) {
                
                $m_model = M('members');
                $data['username'] = $user[1];
                $data['password'] = md5(random(10));
                $data['pay_pwd'] = 	$data['password'];
                $data['email'] = $user[2];
                $data['reg_time'] = time();
                $data['agentgame'] = 'default';
                $data['nickname'] = $user[1];
                $rs = $m_model->add($data);
                
                if (($member = searchuser($username))) {
                        session('user.sdkuser',$username);
                        session('user.xsst_id',$member['id']);
                        cookie('auth', _uc_authcode($uid . "\t" . $username, 'ENCODE'), array('prefix' => 'ucenter_'));
                }
            }
        }
        
        session('user.sdkuser',$username);
//         $userdate = searchuser($username);
//         session('user.xsst_id',$userdate['id']);
        session('user.xsst_id',$uid);
        
        cookie('auth', _uc_authcode($uid . "\t" . $username, 'ENCODE'), array('prefix' => 'ucenter_'));
    }

    /**
     * 如果应用程序需要和其他应用程序进行同步退出登录，此部分代码负责撤销用户的登录的状态
     * @param $get
     * @param $post
     * @return string
     */
    private function synlogout($get, $post) {
        if (!self::API_SYNLOGOUT) {
            return self::API_RETURN_FORBIDDEN;
        }

        header('P3P: CP="CURa ADMa DEVa PSAo PSDo OUR BUS UNI PUR INT DEM STA PRE COM NAV OTC NOI DSP COR"');
        session(null);
        
        cookie('auth', '', array('prefix' => 'ucenter_'));
    }

    /**
     * 当 UCenter 的词语过滤设置变更时，此接口负责通知所有应用程序更新后的词语过滤设置内容
     * @param $get
     * @param $post
     * @return string
     */
    private function updatebadwords($get, $post) {
        if (!self::API_UPDATEBADWORDS) {
            return self::API_RETURN_FORBIDDEN;
        }
        @unlink(UC_CLIENT_PATH . '/data/cache/badwords.php');

        return self::API_RETURN_SUCCEED;
    }

    /**
     * 当 UCenter 的域名解析设置变更时，此接口负责通知所有应用程序更新后的域名解析设置内容
     * @param $get
     * @param $post
     * @return string
     */
    private function updatehosts($get, $post) {
        if (!self::API_UPDATEHOSTS) {
            return self::API_RETURN_FORBIDDEN;
        }
        @unlink(UC_CLIENT_PATH . '/data/cache/hosts.php');

        return self::API_RETURN_SUCCEED;
    }

    /**
     * 当 UCenter 的应用程序列表变更时，此接口负责通知所有应用程序更新后的应用程序列表
     * @param $get
     * @param $post
     * @return string
     */
    private function updateapps($get, $post) {
        if (!self::API_UPDATEAPPS) {
            return self::API_RETURN_FORBIDDEN;
        }
        @unlink(UC_CLIENT_PATH . '/data/cache/apps.php');

        return self::API_RETURN_SUCCEED;
    }

    /**
     * 当 UCenter 的基本设置信息变更时，此接口负责通知所有应用程序更新后的基本设置内容
     * @param $get
     * @param $post
     * @return string
     */
    private function updateclient($get, $post) {
        if (!self::API_UPDATECLIENT) {
            return self::API_RETURN_FORBIDDEN;
        }
        @unlink(UC_CLIENT_PATH . '/data/cache/settings.php');

        return self::API_RETURN_SUCCEED;
    }

    /**
     *当某应用执行了积分兑换请求的接口函数 uc_credit_exchange_request() 后，此接口负责通知被兑换的目的应用程序所需修改的用户积分值。
     * @param $get
     * @param $post
     * @return string
     */
    private function updatecredit($get, $post) {
        if (!self::API_UPDATECREDIT) {
            return self::API_RETURN_FORBIDDEN;
        }

        return self::API_RETURN_SUCCEED;
    }

    /**
     * 此接口负责把应用程序的积分设置传递给 UCenter，以供 UCenter 在积分兑换设置中使用
     * @param $get
     * @param $post
     * @return mixed|string
     */
    private function getcreditsettings($get, $post) {
        if (!self::API_GETCREDITSETTINGS) {
            return self::API_RETURN_FORBIDDEN;
        }

        $credits = array();

        // TODO:
        return $this->_serialize($credits);
    }

    /**
     * 此接口负责接收 UCenter 积分兑换设置的参数
     * @param $get
     * @param $post
     * @return string
     */
    private function updatecreditsettings($get, $post) {
        if (!self::API_UPDATECREDITSETTINGS) {
            return self::API_RETURN_FORBIDDEN;
        }

        // TODO:
        return self::API_RETURN_SUCCEED;
    }

    /**
     *此接口用于把应用程序中指定用户的积分传递给 UCenter
     * @param $get
     * @param $post
     * @return string
     */
    private function getcredit($get, $post) {
        if (!self::API_GETCREDIT) {
            return self::API_RETURN_FORBIDDEN;
        }

        return self::API_RETURN_SUCCEED;
    }

    /**
     * 此接口负责推送动态到所有应用程序里，UCHOME HOME专用
     * @param $get
     * @param $post
     * @return string
     */
    private function addfeed($get, $post) {
        if (!self::API_ADDFEED) {
            return self::API_RETURN_FORBIDDEN;
        }

        return self::API_RETURN_SUCCEED;
    }
}
