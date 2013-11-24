<?php
/**
 * PHP SDK for weibo.com (using OAuth2)
 * 
 * @author Elmer Zhang <freeboy6716@gmail.com>
 */

include_once('oauthException.php');

/**
 * 新浪微博 OAuth 认证类(OAuth2)
 *
 * 授权机制说明请大家参考微博开放平台文档：{@link http://open.weibo.com/wiki/Oauth2}
 *
 * @package sae
 * @author Elmer Zhang
 * @version 1.0
 */
class OAuthV2 {
	
	/**
	 * Contains the last HTTP status code returned. 
	 *
	 * @ignore
	 */
	protected $http_code;
	/**
	 * Contains the last API call.
	 *
	 * @ignore
	 */
	protected $url;
	/**
	 * Set up the API root URL.
	 *
	 * @ignore
	 */
	protected $host = "";
	/**
	 * Set timeout default.
	 *
	 * @ignore
	 */
	protected $timeout = 60;
	/**
	 * Set connect timeout.
	 *
	 * @ignore
	 */
	protected $connecttimeout = 30;
	/**
	 * Verify SSL Cert.
	 *
	 * @ignore
	 */
	protected $ssl_verifypeer = FALSE;
	/**
	 * Respons format.
	 *
	 * @ignore
	 */
	protected $format = 'json';
	/**
	 * Decode returned json data.
	 *
	 * @ignore
	 */
	protected $decode_json = TRUE;
	/**
	 * Contains the last HTTP headers returned.
	 *
	 * @ignore
	 */
	protected $http_info;
	/**
	 * Set the useragnet.
	 *
	 * @ignore
	 */
	protected $useragent = 'Sae T OAuth2 v0.1';

	/**
	 * print the debug info
	 *
	 * @ignore
	 */
	protected $debug = FALSE;
	
	public $log_file = null;


	/**
	 * boundary of multipart
	 * @ignore
	 */
	protected static $boundary = '';

	/**
	 * file data
	 * @ignore
	*/
	private static $file_data = null;
	
	
	/**
	 * construct WeiboOAuth object
	 */
	function __construct($log_file = '') {
		//$this->log_file = empty($log_file)?dirname(__FILE__).'/../../logs/http_auth.log':$log_file;
	}

	function setLogFile($log_file = ''){
		$this->log_file = $log_file;
	}
	
	/**
	 * GET wrappwer for oAuthRequest.
	 *
	 * @return mixed
	 */
	function get($url='', $parameters = array()) {
		$response = $this->oAuthRequest($url, 'GET', $parameters);
		if ($this->format === 'json' && $this->decode_json) {
			return json_decode($response, true);
		}
		return $response;
	}

	/**
	 * POST wreapper for oAuthRequest.
	 *
	 * @return mixed
	 */
	function post($url='', $parameters = array(), $multi = false) {
		$response = $this->oAuthRequest($url, 'POST', $parameters, $multi );
		if ($this->format === 'json' && $this->decode_json) {
			return json_decode($response, true);
		}
		return $response;
	}

	/**
	 * DELTE wrapper for oAuthReqeust.
	 *
	 * @return mixed
	 */
	function delete($url, $parameters = array()) {
		$response = $this->oAuthRequest($url, 'DELETE', $parameters);
		if ($this->format === 'json' && $this->decode_json) {
			return json_decode($response, true);
		}
		return $response;
	}

	/**
	 * Format and sign an OAuth / API request
	 *
	 * @return string
	 * @ignore
	 */
	function oAuthRequest($url, $method, $parameters, $multi = false) {

		if (strrpos($url, 'http://') !== 0 && strrpos($url, 'https://') !== 0) {
			$url = "{$this->host}{$url}.{$this->format}";
		}

		switch ($method) {
			case 'GET':
				$url = $url . '?' . http_build_query($parameters);
				return $this->http($url, 'GET');
			default:
				$headers = array();
				if (!$multi && (is_array($parameters) || is_object($parameters)) ) {
					$body = http_build_query($parameters);
				} else {
					$body = self::build_http_query_multi($parameters,$multi,$headers);
				}
				return $this->http($url, $method, $body, $headers);
		}
	}

	/**
	 * Make an HTTP request
	 *
	 * @return string API results
	 * @ignore
	 */
	function http($url, $method, $postfields = NULL, $headers = array()) {
		$this->http_info = array();
		$ci = curl_init();
		/* Curl settings */
		curl_setopt($ci, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
		curl_setopt($ci, CURLOPT_USERAGENT, $this->useragent);
		curl_setopt($ci, CURLOPT_CONNECTTIMEOUT, $this->connecttimeout);
		curl_setopt($ci, CURLOPT_TIMEOUT, $this->timeout);
		curl_setopt($ci, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ci, CURLOPT_ENCODING, "");
		curl_setopt($ci, CURLOPT_SSL_VERIFYPEER, $this->ssl_verifypeer);
		curl_setopt($ci, CURLOPT_SSL_VERIFYHOST, false);
		//curl_setopt($ci, CURLOPT_HEADERFUNCTION, array($this, 'getHeader'));
		curl_setopt($ci, CURLOPT_HEADER, FALSE);

		switch ($method) {
			case 'POST':
				curl_setopt($ci, CURLOPT_POST, TRUE);
				if (!empty($postfields)) {
					curl_setopt($ci, CURLOPT_POSTFIELDS, $postfields);
					$this->postdata = $postfields;
					$headers[] = 'Expect: ';
				}
				break;
			case 'DELETE':
				curl_setopt($ci, CURLOPT_CUSTOMREQUEST, 'DELETE');
				if (!empty($postfields)) {
					$url = "{$url}?{$postfields}";
				}
		}

		if ( isset($this->access_token) && $this->access_token )
			$headers[] = "Authorization: OAuth2 ".$this->access_token;

		$headers[] = "API-RemoteIP: " . $this->getClientIp();
		curl_setopt($ci, CURLOPT_URL, $url );
		curl_setopt($ci, CURLOPT_HTTPHEADER, $headers );
		curl_setopt($ci, CURLINFO_HEADER_OUT, TRUE );

		$response = curl_exec($ci);
		$this->http_code = curl_getinfo($ci, CURLINFO_HTTP_CODE);
		$this->http_info = array_merge($this->http_info, curl_getinfo($ci));
		$this->url = $url;
		$errno = curl_errno($ci);
		if ($this->debug && $this->log_file || $errno) {
			$tmp_error = "\r\n===== post data (".date('Y-m-d H:i:s').") ======\r\n";
			$tmp_error .= print_r( $postfields , true)."\r\n";
			$tmp_error .= "===== info ======\r\n";
			$tmp_error .= print_r( curl_getinfo($ci) , true)."\r\n";
			$tmp_error .= "===== response ======\r\n";
			$tmp_error .= print_r( $response , true)."\r\n";
            $tmp_error .= "errno:".$errno.'('.curl_error($ci).")\r\n";			
			$this->save_log($tmp_error);
		}
		curl_close ($ci);
		return $response;
	}

	function save_log($msg){		
		error_log($msg,3,$this->log_file);
	}

	/**
	 * Get the header info to store.
	 *
	 * @return int
	 * @ignore
	 */
	function getHeader($ch, $header) {echo 11;exit;
		$i = strpos($header, ':');
		if (!empty($i)) {
			$key = str_replace('-', '_', strtolower(substr($header, 0, $i)));
			$value = trim(substr($header, $i + 2));
			$this->http_header[$key] = $value;
		}
		return strlen($header);
	}

	/**
	 * @ignore
	 */
	public static function build_http_query_multi($params,$multi=false,&$headers=array()) {
		if (!$params) return '';

		uksort($params, 'strcmp');

		$pairs = array();
		if(!is_array($multi)){
			self::$boundary = $boundary = uniqid('------------------');
			$MPboundary = '--'.$boundary;
			$endMPboundary = $MPboundary. '--';
			$multipartbody = '';
			
			$headers[] = "Content-Type: multipart/form-data; boundary=" . self::$boundary;
			foreach ($params as $parameter => $value) {

				if( in_array($parameter, array('pic', 'image')) && $value{0} == '@' ) {
					$url = ltrim( $value, '@' );
					$array = explode( '?', basename( $url ) );
					$filename = $array[0];
					if(self::$file_data == null)
						$content = file_get_contents( $url );
					else
						$content = self::$file_data;

					$multipartbody .= $MPboundary . "\r\n";
					$multipartbody .= 'Content-Disposition: form-data; name="' . $parameter . '"; filename="' . $filename . '"'. "\r\n";
					$multipartbody .= "Content-Type: image/unknown\r\n\r\n";
					$multipartbody .= $content. "\r\n";
				} else {
					$multipartbody .= $MPboundary . "\r\n";
					$multipartbody .= 'content-disposition: form-data; name="' . $parameter . "\"\r\n\r\n";
					$multipartbody .= $value."\r\n";
				}

			}
			$multipartbody .= $endMPboundary;
			
			return $multipartbody;
		}else{
			return $params;
		}
	}
	
	//获取客户端IP
    public static function getClientIp()
    {
        if (getenv ( "HTTP_CLIENT_IP" ) && strcasecmp ( getenv ( "HTTP_CLIENT_IP" ), "unknown" ))
            $ip = getenv ( "HTTP_CLIENT_IP" );
        else if (getenv ( "HTTP_X_FORWARDED_FOR" ) && strcasecmp ( getenv ( "HTTP_X_FORWARDED_FOR" ), "unknown" ))
            $ip = getenv ( "HTTP_X_FORWARDED_FOR" );
        else if (getenv ( "REMOTE_ADDR" ) && strcasecmp ( getenv ( "REMOTE_ADDR" ), "unknown" ))
            $ip = getenv ( "REMOTE_ADDR" );
        else if (isset ( $_SERVER ['REMOTE_ADDR'] ) && $_SERVER ['REMOTE_ADDR'] && strcasecmp ( $_SERVER ['REMOTE_ADDR'], "unknown" ))
            $ip = $_SERVER ['REMOTE_ADDR'];
        else
            $ip = "unknown";
        return ($ip);
    }
}
