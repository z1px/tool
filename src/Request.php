<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 2018/9/29
 * Time: 上午9:03
 */

namespace Z1px\Tool;


/**
 * 请求封装
 * Class Request
 * @package tool
 */
class Request
{

    // curl 请求错误码
    protected static $list_errno = [
        1 => 'CURLE_UNSUPPORTED_PROTOCOL (1) – 您传送给 libcurl 的网址使用了此 libcurl 不支持的协议。 可能是您没有使用的编译时选项造成了这种情况（可能是协议字符串拼写有误，或没有指定协议 libcurl 代码）。',
        2 => 'CURLE_FAILED_INIT (2) – 非常早期的初始化代码失败。 可能是内部错误或问题。',
        3 => 'CURLE_URL_MALFORMAT (3) – 网址格式不正确。',
        5 => 'CURLE_COULDNT_RESOLVE_PROXY (5) – 无法解析代理服务器。 指定的代理服务器主机无法解析。',
        6 => 'CURLE_COULDNT_RESOLVE_HOST (6) – 无法解析主机。 指定的远程主机无法解析。',
        7 => 'CURLE_COULDNT_CONNECT (7) – 无法通过 connect() 连接至主机或代理服务器。',
        8 => 'CURLE_FTP_WEIRD_SERVER_REPLY (8) – 在连接到 FTP 服务器后，libcurl 需要收到特定的回复。 此错误代码表示收到了不正常或不正确的回复。 指定的远程服务器可能不是正确的 FTP 服务器。',
        9 => 'CURLE_REMOTE_ACCESS_DENIED (9) – 我们无法访问网址中指定的资源。 对于 FTP，如果尝试更改为远程目录，就会发生这种情况。',
        11 => 'CURLE_FTP_WEIRD_PASS_REPLY (11) – 在将 FTP 密码发送到服务器后，libcurl 需要收到正确的回复。 此错误代码表示返回的是意外的代码。',
        13 => 'CURLE_FTP_WEIRD_PASV_REPLY (13) – libcurl 无法从服务器端收到有用的结果，作为对 PASV 或 EPSV 命令的响应。 服务器有问题。',
        14 => 'CURLE_FTP_WEIRD_227_FORMAT (14) – FTP 服务器返回 227 行作为对 PASV 命令的响应。如果 libcurl 无法解析此行，就会返回此代码。',
        15 => 'CURLE_FTP_CANT_GET_HOST (15) – 在查找用于新连接的主机时出现内部错误。',
        17 => 'CURLE_FTP_COULDNT_SET_TYPE (17) – 在尝试将传输模式设置为二进制或 ascii 时发生错误。',
        18 => 'CURLE_PARTIAL_FILE (18) – 文件传输尺寸小于或大于预期。当服务器先报告了一个预期的传输尺寸，然后所传送的数据与先前指定尺寸不相符时，就会发生此错误。',
        19 => 'CURLE_FTP_COULDNT_RETR_FILE (19) – ‘RETR’ 命令收到了不正常的回复，或完成的传输尺寸为零字节。',
        21 => 'CURLE_QUOTE_ERROR (21) – 在向远程服务器发送自定义 “QUOTE” 命令时，其中一个命令返回的错误代码为 400 或更大的数字（对于 FTP），或以其他方式表明命令无法成功完成。',
        22 => 'CURLE_HTTP_RETURNED_ERROR (22) – 如果 CURLOPT_FAILONERROR 设置为 TRUE，且 HTTP 服务器返回 >= 400 的错误代码，就会返回此代码。 （此错误代码以前又称为 CURLE_HTTP_NOT_FOUND。）',
        23 => 'CURLE_WRITE_ERROR (23) – 在向本地文件写入所收到的数据时发生错误，或由写入回调 (write callback) 向 libcurl 返回了一个错误。',
        25 => 'CURLE_UPLOAD_FAILED (25) – 无法开始上传。 对于 FTP，服务器通常会拒绝执行 STOR 命令。错误缓冲区通常会提供服务器对此问题的说明。 （此错误代码以前又称为 CURLE_FTP_COULDNT_STOR_FILE。）',
        26 => 'CURLE_READ_ERROR (26) – 读取本地文件时遇到问题，或由读取回调 (read callback) 返回了一个错误。',
        27 => 'CURLE_OUT_OF_MEMORY (27) – 内存分配请求失败。此错误比较严重，若发生此错误，则表明出现了非常严重的问题。',
        28 => 'CURLE_OPERATION_TIMEDOUT (28) – 操作超时。 已达到根据相应情况指定的超时时间。',
        30 => 'CURLE_FTP_PORT_FAILED (30) – FTP PORT 命令返回错误。 在没有为 libcurl 指定适当的地址使用时，最有可能发生此问题。 请参阅 CURLOPT_FTPPORT。',
        31 => 'CURLE_FTP_COULDNT_USE_REST (31) – FTP REST 命令返回错误。如果服务器正常，则应当不会发生这种情况。',
        33 => 'CURLE_RANGE_ERROR (33) – 服务器不支持或不接受范围请求。',
        34 => 'CURLE_HTTP_POST_ERROR (34) – 此问题比较少见，主要由内部混乱引发。',
        35 => 'CURLE_SSL_CONNECT_ERROR (35) – 同时使用 SSL/TLS 时可能会发生此错误。您可以访问错误缓冲区查看相应信息，其中会对此问题进行更详细的介绍。可能是证书（文件格式、路径、许可）、密码及其他因素导致了此问题。',
        36 => 'CURLE_FTP_BAD_DOWNLOAD_RESUME (36) – 尝试恢复超过文件大小限制的 FTP 连接。',
        37 => 'CURLE_FILE_COULDNT_READ_FILE (37) – 无法打开 FILE:// 路径下的文件。原因很可能是文件路径无法识别现有文件。 建议您检查文件的访问权限。',
        38 => 'CURLE_LDAP_CANNOT_BIND (38) – LDAP 无法绑定。LDAP 绑定操作失败。',
        39 => 'CURLE_LDAP_SEARCH_FAILED (39) – LDAP 搜索无法进行。',
        41 => 'CURLE_FUNCTION_NOT_FOUND (41) – 找不到函数。 找不到必要的 zlib 函数。',
        42 => 'CURLE_ABORTED_BY_CALLBACK (42) – 由回调中止。 回调向 libcurl 返回了 “abort”。',
        43 => 'CURLE_BAD_FUNCTION_ARGUMENT (43) – 内部错误。 使用了不正确的参数调用函数。',
        45 => 'CURLE_INTERFACE_FAILED (45) – 界面错误。 指定的外部界面无法使用。 请通过 CURLOPT_INTERFACE 设置要使用哪个界面来处理外部连接的来源 IP 地址。 （此错误代码以前又称为 CURLE_HTTP_PORT_FAILED。）',
        47 => 'CURLE_TOO_MANY_REDIRECTS (47) – 重定向过多。 进行重定向时，libcurl 达到了网页点击上限。请使用 CURLOPT_MAXREDIRS 设置上限。',
        48 => 'CURLE_UNKNOWN_TELNET_OPTION (48) – 无法识别以 CURLOPT_TELNETOPTIONS 设置的选项。 请参阅相关文档。',
        49 => 'CURLE_TELNET_OPTION_SYNTAX (49) – telnet 选项字符串的格式不正确。',
        51 => 'CURLE_PEER_FAILED_VERIFICATION (51) – 远程服务器的 SSL 证书或 SSH md5 指纹不正确。',
        52 => 'CURLE_GOT_NOTHING (52) – 服务器未返回任何数据，在相应情况下，未返回任何数据就属于出现错误。',
        53 => 'CURLE_SSL_ENGINE_NOTFOUND (53) – 找不到指定的加密引擎。',
        54 => 'CURLE_SSL_ENGINE_SETFAILED (54) – 无法将选定的 SSL 加密引擎设为默认选项。',
        55 => 'CURLE_SEND_ERROR (55) – 无法发送网络数据。',
        56 => 'CURLE_RECV_ERROR (56) – 接收网络数据失败。',
        58 => 'CURLE_SSL_CERTPROBLEM (58) – 本地客户端证书有问题',
        59 => 'CURLE_SSL_CIPHER (59) – 无法使用指定的密钥',
        60 => 'CURLE_SSL_CACERT (60) – 无法使用已知的 CA 证书验证对等证书',
        61 => 'CURLE_BAD_CONTENT_ENCODING (61) – 无法识别传输编码',
        62 => 'CURLE_LDAP_INVALID_URL (62) – LDAP 网址无效',
        63 => 'CURLE_FILESIZE_EXCEEDED (63) – 超过了文件大小上限',
        64 => 'CURLE_USE_SSL_FAILED (64) – 请求的 FTP SSL 级别失败',
        65 => 'CURLE_SEND_FAIL_REWIND (65) – 进行发送操作时，curl 必须回转数据以便重新传输，但回转操作未能成功',
        66 => 'CURLE_SSL_ENGINE_INITFAILED (66) – SSL 引擎初始化失败',
        67 => 'CURLE_LOGIN_DENIED (67) – 远程服务器拒绝 curl 登录（7.13.1 新增功能）',
        68 => 'CURLE_TFTP_NOTFOUND (68) – 在 TFTP 服务器上找不到文件',
        69 => 'CURLE_TFTP_PERM (69) – 在 TFTP 服务器上遇到权限问题',
        70 => 'CURLE_REMOTE_DISK_FULL (70) – 服务器磁盘空间不足',
        71 => 'CURLE_TFTP_ILLEGAL (71) – TFTP 操作非法',
        72 => 'CURLE_TFTP_UNKNOWNID (72) – TFTP 传输 ID 未知',
        73 => 'CURLE_REMOTE_FILE_EXISTS (73) – 文件已存在，无法覆盖',
        74 => 'CURLE_TFTP_NOSUCHUSER (74) – 运行正常的 TFTP 服务器不会返回此错误',
        75 => 'CURLE_CONV_FAILED (75) – 字符转换失败',
        76 => 'CURLE_CONV_REQD (76) – 调用方必须注册转换回调',
        77 => 'CURLE_SSL_CACERT_BADFILE (77) – 读取 SSL CA 证书时遇到问题（可能是路径错误或访问权限问题）',
        78 => 'CURLE_REMOTE_FILE_NOT_FOUND (78) – 网址中引用的资源不存在',
        79 => 'CURLE_SSH (79) – SSH 会话中发生无法识别的错误',
        80 => 'CURLE_SSL_SHUTDOWN_FAILED (80) – 无法终止 SSL 连接'
    ];

    /**
     * curl模拟提交
     * @param $url
     * @param array $params
     * @param array $header
     * @param string $method
     * @param array $options
     * @return array|mixed
     */
    public static function curl_request($url, $params=[], $header=[], $method='post', $options=[])
    {

        if(!function_exists('curl_init')){
            return [
                'code' => 0,
                'message' => 'Do not support CURL function.',
                'data' => [],
            ];
        }
        if(!filter_var($url, FILTER_VALIDATE_URL)){
            return [
                'code' => 0,
                'message' => self::$list_errno[3],
                'data' => [],
            ];
        }

        // 请求参数格式转换成数组
//        if('get' === strtolower($method)){
//            $params = self::format_params($params);
//        }

        // 头部格式转换
        $header = self::format_header($header);

        // 参数格数转换与请求头设置
        switch (strtolower($method)){
            case 'get':
                $options[CURLOPT_POST] = false; //GET提交方式
                if(!empty($params)){
                    $params = is_array($params) ? http_build_query($params) : $params;
                    $url .= (strstr($url,'?') ? '&' : '?') . $params;
                    $params = [];
                }
                break;
            case 'post':
                $options[CURLOPT_POST] = true; //post提交方式
                break;
            case 'json':
                $options[CURLOPT_POST] = true; //post提交方式
                if(!empty($params) && is_array($params)){
                    $params = json_encode($params);
                }
                if(!empty($params)){
                    array_unshift($header, 'Content-Length: '.strlen($params));
                }
                array_unshift($header, 'Accept: application/json');
                array_unshift($header, 'Content-Type: application/json; charset=utf-8');
                break;
            case 'form':
                $options[CURLOPT_POST] = true; //post提交方式
                // 伪造来源referer
                if(!isset($options[CURLOPT_REFERER])){
                    $options[CURLOPT_REFERER] = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : (isset($_SERVER['REQUEST_SCHEME']) ? $_SERVER['REQUEST_SCHEME'] : (((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && 'https' === $_SERVER['HTTP_X_FORWARDED_PROTO'])) ? 'https' : 'http')) . '://'. $_SERVER['HTTP_HOST'];
                }
                if(!empty($params) && is_array($params)) $params = http_build_query($params);
                if(!empty($params)){
                    array_unshift($header, 'Content-Length: '.strlen($params));
                }
                array_unshift($header, 'Content-Type: application/x-www-form-urlencoded; charset=utf-8');
                break;
            default:
                $options[CURLOPT_POST] = true; //post提交方式
        }

        $ch = curl_init(); //初始化curl

        $setopt = [];
        if(false !== stripos($url,'https://')){
            $setopt[CURLOPT_SSL_VERIFYPEER] = false; // 禁止curl验证证书
            $setopt[CURLOPT_SSL_VERIFYHOST] = false; // 禁止检测主机
            $setopt[CURLOPT_SSLVERSION] = CURL_SSLVERSION_TLSv1_1; // CURL_SSLVERSION_TLSv1 CURL_SSLVERSION_TLSv1_1 CURL_SSLVERSION_TLSv1_2
        }
        $setopt[CURLOPT_URL] = $url; //抓取指定网页
        $setopt[CURLOPT_POST] = true; //是否POST提交方式
        $setopt[CURLOPT_USERAGENT] = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_13_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/68.0.3440.106 Safari/537.36'; //模拟浏览器代理
        $setopt[CURLOPT_RETURNTRANSFER] = true; //设置获取的信息以文件流的形式返回，而不是直接输出
        $setopt[CURLOPT_NOBODY] = false; //不显示body
        $setopt[CURLOPT_HEADER] = false; //设置头文件的信息作为数据流输出

//        $header[] = 'CLIENT-IP: 8.8.8.8';
//        $header[] = 'X-FORWARDED-FOR: 8.8.8.8'; // 伪造IP来源

        if(!empty($header)){
            $setopt[CURLOPT_HTTPHEADER] = array_unique($header); //请求头设置
        }

//        $setopt[CURLOPT_CONNECTTIMEOUT] = 60; //超时设置
        $setopt[CURLOPT_FOLLOWLOCATION] = true; //根据服务器返回 HTTP 头中的'Location:'重定向
        $setopt[CURLOPT_MAXREDIRS] = 3; //最大重定向次数

//        $setopt[CURLOPT_HTTPPROXYTUNNEL] = true; //是否通过http代理来传输
//        $setopt[CURLOPT_PROXY] = 'ip:端口号'; //是否通过http代理来传输
//        $setopt[CURLOPT_PROXYUSERPWD] = 'user:password'; //如果要密码的话，加上这个

        // cookie设置
//        $setopt[CURLOPT_COOKIE] = ''; //cookie

        //curl上传或者下载，获取curl传输进度
//        $setopt[CURLOPT_NOPROGRESS] = false; //是否关闭传输进度，默认是true
//        $setopt[CURLOPT_PROGRESSFUNCTION] = 'callback'; //回调函数，curl传输过程中，会每隔一段时间自动调用该函数

        if(!empty($params)){
            $setopt[CURLOPT_POSTFIELDS] = $params; //提交数据
        }
        if (function_exists('curl_file_create')){ // php >= 5.5
            $setopt[CURLOPT_SAFE_UPLOAD] = true; //文件上传
        }else {
            if (defined('CURLOPT_SAFE_UPLOAD')){
                $setopt[CURLOPT_SAFE_UPLOAD] = false; //文件上传
            }
        }
        if(!empty($options) && is_array($options)) $setopt = $options + $setopt;

        curl_setopt_array($ch, $setopt);
        unset($url, $params, $header, $method, $setopt);

        $response = curl_exec($ch); //运行curl
        $errno = curl_errno($ch); //返回最后一次的错误号
        $http_code = curl_getinfo($ch,CURLINFO_HTTP_CODE); //状态码
        curl_close($ch);

        if (0 !== $errno) {
            return [
                'code' => 0,
                'message'   => isset(self::$list_errno[$errno]) ? self::$list_errno[$errno] : $errno,
                'data'  => $response,
            ];
        }

        try {
            $result = json_decode($response,true) ?: (200 === $http_code ? $response : ['code' => 0, 'message' => "请求状态码为：{$http_code}", 'data' => $response]);
        } catch (\Exception $e) {
            $result = $response;
        }

        unset($response, $errno, $http_code);

        return $result;
    }

    /**
     * get提交
     * @param $url
     * @param array $params
     * @param array $header
     * @param int $timeout
     * @return mixed
     */
    public static function get($url, $params=[], $header=[], $timeout=60)
    {

        $response = self::curl_request($url, $params, $header, 'get', [CURLOPT_CONNECTTIMEOUT => $timeout]);

        unset($url, $params, $header, $timeout);

        return $response;
    }

    /**
     * post提交
     * @param $url
     * @param array $params
     * @param array $header
     * @param int $timeout
     * @return mixed
     */
    public static function post($url, $params=[], $header=[], $timeout=60)
    {

        $response = self::curl_request($url, $params, $header, 'post', [CURLOPT_CONNECTTIMEOUT => $timeout]);

        unset($url, $params, $header, $timeout);

        return $response;
    }

    /**
     * json提交
     * @param $url
     * @param array $params
     * @param array $header
     * @param int $timeout
     * @return mixed
     */
    public static function json($url, $params=[], $header=[], $timeout=60)
    {

        $response = self::curl_request($url, $params, $header, 'json', [CURLOPT_CONNECTTIMEOUT => $timeout]);

        unset($url, $params, $header, $timeout);

        return $response;
    }

    /**
     * form表单提交
     * @param $url
     * @param array $params
     * @param array $header
     * @param int $timeout
     * @return array|mixed
     */
    public static function form($url, $params=[], $header=[], $timeout=60){

        $response = self::curl_request($url, $params, $header, 'form', [CURLOPT_CONNECTTIMEOUT => $timeout]);

        unset($url, $params, $header, $timeout);

        return $response;
    }


    /**
     * 组装form表单跳转
     * @param $url
     * @param $params
     * @param string $method
     */
    public static function createForm($url, $params, $method='post'){
        $html = '<!doctype html>';
        $html .= '<html>';
        $html .= '<head>';
        $html .= '<meta charset="utf8">';
        $html .= '<title>正在跳转...</title>';
        $html .= '</head>';
        $html .= '<body onLoad="document.form.submit()">';
        $html .= '<form action="' . $url . '" method="' . $method . '" name="form">';
        if(is_array($params) && !empty($params)){
            foreach ($params as $key=>$value){
                $html .= '<input type="hidden" name="' . $key . '" value="' . $value . '">';
            }
        }
        $html .= '</form>';
        $html .= '</body>';
        $html .= '</html>';

        return $html;
    }

    /**
     * curl文件下载
     * @param $url
     * @param string $filename
     * @param bool $path
     * @param int $timeout
     * @return array
     */
    public static function download($url, $filename='', $path=false, $timeout=600)
    {

        if(empty($filename)){
            return [
                'code' => 0,
                'message' => '请传入下载文件名',
                'data' => [],
            ];
        }
        $filename = ltrim($filename, '/');

        if(false === $path){
            $path = 'download/';
        }
        $path = rtrim($path, '/') . '/';

        $path = dirname($path . $filename) . '/';
        $filename = basename($filename);

        if(!is_dir($path)){
            mkdir($path, 0755, true);
        }

        $response = self::curl_request($url, [], [], 'get', [CURLOPT_CONNECTTIMEOUT => $timeout]);

        file_put_contents($path.$filename, $response);

        unset($url, $timeout, $response);

        return [
            'code' => 1,
            'message' => '下载成功',
            'data' => [
                'filename' => $path.$filename,
                'url' => "{$_SERVER['REQUEST_SCHEME']}://{$_SERVER['SERVER_NAME']}/{$path}{$filename}",
            ],
        ];
    }

    /**
     * 文件上传
     * @param $url
     * @param $file
     * @param array $header
     * @param int $timeout
     * @return bool|mixed
     */
    public static function upload($url, $file, $header=[], $timeout=60)
    {

        if(!is_string($file) || !is_file($file)){
            return [
                'code' => 0,
                'message' => '文件不存在',
                'data' => [],
            ];
        }
        $file = realpath($file);

        $params = [self::curl_file_create($file, self::mime_type($file), basename($file))];

        $response = self::curl_request($url, $params, $header, 'post', [CURLOPT_CONNECTTIMEOUT => $timeout]);

        unset($url, $file, $params, $header, $timeout);

        return $response;
    }

    /**
     * 多文件上传
     * @param $url
     * @param $files
     * @param array $header
     * @param int $timeout
     * @return bool|mixed
     */
    public static function uploads($url, $files, $header=[], $timeout=60)
    {

        if(!is_array($files)){
            return [
                'code' => 0,
                'message' => '文件不存在',
                'data' => [],
            ];
        }

        $params = [];
        foreach ($files as $file){
            if(!is_file($file)) continue;
            $file = realpath($file);
            array_push($params, self::curl_file_create($file, self::mime_type($file), basename($file))); //>=5.5
        }
        if(empty($params)){
            return [
                'code' => 0,
                'message' => '没有文件上传',
                'data' => [],
            ];
        }

        $response = self::curl_request($url, $params, $header, 'post', [CURLOPT_CONNECTTIMEOUT => $timeout]);

        unset($url, $files, $params, $header, $timeout);

        return $response;
    }

    /**
     * 异步提交（不用关心服务器返回结果）
     * @param $url
     * @param $params
     * @param array $header
     * @return array|bool
     */
    public static function sock_request($url, $params=[], $header=[], $method = 'post')
    {

        $parts = parse_url($url);
        unset($url);
        if(!isset($parts['host'])){
            return [
                'code' => 0,
                'message' => self::$list_errno[3],
                'data' => [],
            ];
        }
        if(!isset($parts['scheme'])) $parts['scheme'] = 'http';
        if(!isset($parts['port'])) $parts['port'] = 80;
        if(!isset($parts['path'])) $parts['path'] = '';
        if(isset($parts['query'])){
            $parts['query'] = self::format_params($parts['query']);
        }
        $params = self::format_params($params);
        $header = self::format_header($header);
        $parts['query'] = isset($parts['query']) ? array_merge($parts['query'], $params) : $params;
        unset($params);
        $parts['query'] = empty($parts['query']) ? '' : http_build_query($parts['query']);
        $params['method'] = strtoupper($method);
        unset($method);
        if(!in_array($params['method'], ['GET', 'POST'])) $params['method'] = 'POST';

        try {
            $fp = fsockopen($parts['host'], $parts['port'], $errno, $errstr, 1);
        } catch (\Exception $e) {
            return [
                'code' => 0,
                'message' => $e,
                'data' => [],
            ];
        }
        if(empty($fp)){
            return [
                'code' => 0,
//                'message' => 'Failed to open socket to localhost',
                'message' => "{$errstr} ({$errno})",
                'data' => []
            ];
        }
        fputs($fp, "{$params['method']} {$parts['path']}?{$parts['query']} HTTP/1.1\r\n");
        fputs($fp, "Host: {$parts['host']}\r\n");
        fputs($fp, "Referer: {$parts['scheme']}://{$parts['host']}{$parts['path']}\r\n");
        fputs($fp, "Content-type: application/x-www-form-urlencoded\r\n");
        fputs($fp, "Content-Length: ".strlen(trim($parts["query"]))."\r\n");
        if(!empty($header) && is_array($header)){
            foreach ($header as $value){
                fputs($fp, "{$value}\r\n");
            }
        }
        unset($header);
        fputs($fp, "\r\n");
        fputs($fp, trim($parts['query']));

        usleep(1000); // 这一句也是关键，如果没有这延时，可能在nginx服务器上就无法执行成功
        fclose($fp);
        unset($parts);

        return [
            'code' => 1,
            'message' => '数据已提交',
            'data' => []
        ];
    }

    /**************************************************  **************************************************/

    /**
     * 请求参数格式转换成数组
     * @param $params
     * @return array|mixed
     */
    public static function format_params($data)
    {
        if(empty($data)) return [];
        if(is_array($data)) return $data;
        if(!is_string($data)) return [];
        // 判断是否是json格式
        $data = json_decode($data,true) ?: $data;
        if(!empty($data) && is_array($data)) return $data;
        // 判断是否是query
        if(false !== stripos($data,'=')){
            parse_str($data, $data);
        }
        return !empty($data) && is_array($data) ? $data : [];
    }

    /**
     * 头部格式转换
     * @param $data
     * @return array
     */
    public static function format_header($data)
    {
        $result = [];
        if(!empty($data) && is_array($data)){
            foreach($data as $key=>$value){
                if(is_numeric($key) && stripos($value,':')){
                    $result[] = $value;
                }else{
                    $result[] = "{$key}: {$value}";
                }
            }
        }
        return $result;
    }

    /**
     * 获取文件mime
     * @param $filename
     * @return mixed|string
     */
    public static function mime_type($filename)
    {
        if(!is_file($filename)){
            $mime_type = '';
        }elseif(function_exists('finfo_open')){
            $finfo = finfo_open(FILEINFO_MIME_TYPE);// 返回 mime 类型
            $mime_type = finfo_file($finfo, $filename);
            finfo_close($finfo);
        }elseif (function_exists('mime_content_type')){
            $mime_type = mime_content_type($filename);
        }else{
            $mime_type = '';
        }
        return $mime_type;
    }

    /**
     * 创建一个 CURLFile 对象
     * @param $filename 上传文件的路径
     * @param $mimetype 文件的Mimetype
     * @param $postname 文件名
     * @return \CURLFile|string
     */
    public static function curl_file_create($filename, $mimetype, $postname)
    {
        if (!function_exists('curl_file_create')) {
            function curl_file_create($filename, $mimetype, $postname) {
                return "@{$filename};filename=" . ($postname ?: basename($filename)) . ($mimetype ? ";type={$mimetype}" : '');
            }
        }

        return curl_file_create($filename, $mimetype, $postname);
    }

}
