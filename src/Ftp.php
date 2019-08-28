<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 2018/11/2
 * Time: 2:29 PM
 */

namespace Z1px\Tool;


class Ftp
{

    //属性值为对象,默认为null
    private static $instance = null;

    //FTP 连接资源
    private $link;

    //FTP连接时间
    public $link_time;

    //错误代码
    private $err_code = 0;

    //默认配置
    private $config  = [
        'host' => '', //服务器
        'username' => '', //用户名
        'password' => '', //密码
        'port' => 21, //端口
        'pasv'=>true, // 是否开启被动模式
        'ssl'=>false, // 是否使用SSL连接
        'timeout' => 180, //超时时间
        'mode' => FTP_BINARY, //传送模式{文本模式:FTP_ASCII, 二进制模式:FTP_BINARY}
    ];

    /**
     * 构造函数
     * __construct()方法到实例化时自动加载function
     * Ftp constructor.
     * @param $config
     */
    public function __construct($config=[]) {
        if(is_array($config) && !empty($config)){
            $this->config = array_merge($this->config, $config);
            self::connect();
        }
        unset($config);
    }

    /**
     * 单例获取对象
     * @param $config
     * @return null|Ftp
     */
    public static function instance($config) {
        if (is_null(self::$instance)) {
            self::$instance = new static($config);
        }
        return self::$instance;
    }

    /**
     * 修改配置重新连接
     * @param $config
     * @return $this
     */
    public function setConfig($config)
    {
        if(is_array($config) && !empty($config)){
            $this->config = array_merge($this->config, $config);
        }
        unset($config);

        self::connect();

        return $this;
    }

    //__get()方法用来获取私有属性
    public function __get($name){
        if(isset($this->config[$name])) {
            return $this->config[$name];
        }
        return null;
    }

    //__set()方法用来设置私有属性
    public function __set($name,$value){
        if(isset($this->config[$name])) {
            $this->config[$name] = $value;
        }
    }

    //__isset()方法用来检测私有成员属性是否被设定
    public function __isset($name){
        return isset($this->config[$name]);
    }

    //__unset()方法用来删除私有成员属性
    public function __unset($name){
        unset($this->config[$name]);
    }

    //__call()方法用来获取没有定义的function
    public function __call($name, $param){
        return false;
    }

    //__toString()方法用来获取类名
    public function __toString() {
        return __CLASS__;
    }

    // 覆盖__clone()方法，禁止克隆
    public function __clone(){
        return false;
    }

    /**
     * 析构函数
     * __destruct()删除类对象时自动会调用
     */
    public function __destruct() {
        self::close();
        return true;
    }

    /**
     * 连接FTP服务器
     * param string $host    　　 服务器地址
     * param string $username　　　用户名
     * param string $password　　　密码
     * param integer $port　　　　   服务器端口，默认值为21
     * param boolean $pasv        是否开启被动模式
     * param boolean $ssl　　　　 　是否使用SSL连接
     * param integer $timeout     超时时间　
     */
    protected function connect() {
        $start = time();
        if ($this->ssl) {
            if (!$this->link = @ftp_ssl_connect($this->host, $this->port, $this->timeout)) {
                $this->err_code = 1;
                return false;
            }
        } else {
            if (!$this->link = @ftp_connect($this->host, $this->port, $this->timeout)) {
                $this->err_code = 1;
                return false;
            }
        }

        if (@ftp_login($this->link, $this->username, $this->password)) {
            if ($this->pasv)
                @ftp_pasv($this->link, true);
            $this->link_time = time() - $start;
            return true;
        } else {
            $this->err_code = 1;
            return false;
        }
        register_shutdown_function([&$this, 'close']);
    }

    /**
     * 创建文件夹
     * param string $dirname 目录名，
     */
    public function mkdir($dirname) {
        if (!$this->link) {
            $this->err_code = 2;
            return false;
        }
        $dirname = self::ck_dirname($dirname);
        $nowdir = '/';
        foreach ($dirname as $v) {
            if ($v && !self::chdir($nowdir . $v)) {
                if ($nowdir){
                    $this->chdir($nowdir);
                }
                @ftp_mkdir($this->link, $v);
            }
            if ($v)
                $nowdir .= $v . '/';
        }
        return true;
    }

    /**
     * 上传文件
     * param string $remote 远程存放地址
     * param string $local 本地存放地址
     */
    public function put($remote, $local) {
        if (!$this->link) {
            $this->err_code = 2;
            return false;
        }
        $dirname = pathinfo($remote, PATHINFO_DIRNAME);
        if (!$this->chdir($dirname)) {
            $this->mkdir($dirname);
        }
        if (@ftp_put($this->link, $remote, $local, $this->mode)) {
            return true;
        } else {
            $this->err_code = 7;
            return false;
        }
    }

    /**
     * 删除文件夹
     * param string $dirname  目录地址
     * param boolean $enforce 强制删除
     */
    public function rmdir($dirname, $enforce = false) {
        if (!$this->link) {
            $this->err_code = 2;
            return false;
        }
        $list = self::nlist($dirname);
        if ($list && $enforce) {
            $this->chdir($dirname);
            foreach ($list as $v) {
                self::delete($v);
            }
        } elseif ($list && !$enforce) {
            $this->err_code = 3;
            return false;
        }
        @ftp_rmdir($this->link, $dirname);
        return true;
    }

    /**
     * 删除指定文件
     * param string $filename 文件名
     */
    public function delete($filename) {
        if (!$this->link) {
            $this->err_code = 2;
            return false;
        }
        if (@ftp_delete($this->link, $filename)) {
            return true;
        } else {
            $this->err_code = 4;
            return false;
        }
    }

    /**
     * 返回给定目录的文件列表
     * param string $dirname  目录地址
     * return array 文件列表数据
     */
    public function nlist($dirname) {
        if (!$this->link) {
            $this->err_code = 2;
            return false;
        }
        if ($list = @ftp_nlist($this->link, $dirname)) {
            return $list;
        } else {
            $this->err_code = 5;
            return false;
        }
    }

    /**
     * 在 FTP 服务器上改变当前目录
     * param string $dirname 修改服务器上当前目录
     */
    public function chdir($dirname) {
        if (!$this->link) {
            $this->err_code = 2;
            return false;
        }
        try{
            if (@ftp_chdir($this->link, $dirname)) {
                return true;
            } else {
                $this->err_code = 6;
                return false;
            }
        }catch (\Exception $e){
            $this->err_code = 8;
            return false;
        }
    }

    /**
     * 获取错误信息
     */
    public function get_error() {
        if (!$this->err_code){
            return false;
        }
        $err_msg = [
            1 => 'Server can not connect',
            2 => 'Not connect to server',
            3 => 'Can not delete non-empty folder',
            4 => 'Can not delete file',
            5 => 'Can not get file list',
            6 => 'Can not change the current directory on the server',
            7 => 'Can not upload files',
            8 => 'Directory not found',
        ];
        return $err_msg[$this->err_code];
    }

    /**
     * 检测目录名
     * param string $url 目录
     * return 由 / 分开的返回数组
     */
    private function ck_dirname($url) {
        $url = str_replace('', '/', $url);
        $urls = explode('/', $url);
        return $urls;
    }

    /**
     * 关闭FTP连接
     */

    public function close() {
        return @ftp_close($this->link);
    }

}