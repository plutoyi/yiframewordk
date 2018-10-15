<?php 
// +----------------------------------------------------------------------
// | YiFramework
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://yisong.sinaapp.com
// +----------------------------------------------------------------------
// | Licensed
// +----------------------------------------------------------------------
// | Author: Devin.yang<yi.pluto@163.com>
// +----------------------------------------------------------------------

/**
 +------------------------------------------------------------------------------
 * Session���ƻ���
 +------------------------------------------------------------------------------
 * @category   Yi
 * @package  Yi
 * @subpackage  Core
 * @author    Devin.yang<yi.pluto@163.com>
 * @version   $Id$
 +------------------------------------------------------------------------------
 */
class YiSession{

	public $autoStart=true;
		
	public function __construct(){
		if($this->autoStart){
			$this->open();
		}
		register_shutdown_function(array($this,'close'));
	}	
	
	public function open(){
		@session_start();
	}
	
	public function close(){
		if(session_id()!==''){
			@session_write_close();
		}
	}

	public function destroy(){
		if(session_id()!==''){
			@session_unset();
			@session_destroy();
		}
	}
	
	/**
     * ��ȡSession id
     */
	public function getSessionID(){
		return session_id();
	}
	
	/**
     * ����Session id
     * @param string $id session id
     */
	public function setSessionID($id){
		session_id($id);
	}
	
	/**
     * ��ȡSession name
     * @param string $name session����
     */
	public function getSessionName(){
		return session_name();
	}
	
	/**
     * ����Session name
     * @param string $name session����
     */
	public function setSessionName($name){
		session_name($name);
	}
	
	/**
     * ��ȡ��ǰSession����·��
     */
	public function getSavePath(){
		return session_save_path();
	}
	
	/**
     * ���õ�ǰSession����·��
	 *
	 * @param string $path session save·��
     */
	public function setSavePath($path){
		if(is_dir($path)){
			session_save_path($path);
		}else{
			throw new YiException('savePath "{$path}" is not a valid directory.');
		}
	}
	
	/**
     * ��ȡcookieParams
     */
	public function getCookieParams(){
		return session_get_cookie_params();
	}
	
	/**
     * ���Session
     */
	public function clear(){
		$_SESSION = array();
	}
	
	/**
     * ����session
	 *
	 * @param string $name session����
	 * @param mix $value sessionֵ
     */
	public function set($name,$value){
		$_SESSION[$name] = $value;
	}
	
	/**
     * ��ȡsession
	 *
	 * @param string $name session����
	 * @return string|int
     */
	public function get($name){
		return $_SESSION[$name];
	}
	/**
     * ���Session ֵ�Ƿ��Ѿ�����
     */
    public function is_set($name){
        return isset($_SESSION[$name]);
    }
	
	
	/**
     * ����Session cookie_domain
     * ����֮ǰ����
    */
    public function setCookieDomain($sessionDomain = null){
        $return = ini_get('session.cookie_domain');
        if(!empty($sessionDomain)) {
            ini_set('session.cookie_domain', $sessionDomain);//�������Session
        }
        return $return;
    }
	
	/**
     * ����Session gc_maxlifetimeֵ
     * ����֮ǰ����
     */
    public function setGcMaxLifetime($gcMaxLifetime = null){
        $return = ini_get('session.gc_maxlifetime');
        if (isset($gcMaxLifetime) && is_int($gcMaxLifetime) && $gcMaxLifetime >= 1) {
            ini_set('session.gc_maxlifetime', $gcMaxLifetime);
        }
        return $return;
    }
	
	/**
     * ����Session gc_probability ֵ
     * ����֮ǰ����
     */
    public function setGcProbability($gcProbability = null){
        $return = ini_get('session.gc_probability');
        if (isset($gcProbability) && is_int($gcProbability) && $gcProbability >= 1 && $gcProbability <= 100) {
            ini_set('session.gc_probability', $gcProbability);
        }
        return $return;
    }
	
	/**
     * ��ǰSession�ļ���
     */
    public function getFilename(){
        return $this->getSavePath().'/sess_'.session_id();
    }
	
	/**
     * ����Session ����ʱ��
     */
    public function setExpire($time, $add = false){
        if ($add) {
            if (!isset($_SESSION['__HTTP_Session_Expire_TS'])) {
                $_SESSION['__HTTP_Session_Expire_TS'] = time() + $time;
            }
   
            // update session.gc_maxlifetime
            $currentGcMaxLifetime = Session::setGcMaxLifetime(null);
            $this->setGcMaxLifetime($currentGcMaxLifetime + $time);
   
        } elseif (!isset($_SESSION['__HTTP_Session_Expire_TS'])) {
            $_SESSION['__HTTP_Session_Expire_TS'] = $time;
        }
    }
	
	/**
     * ���Session �Ƿ����
     */
    public function isExpired(){
        if (isset($_SESSION['__HTTP_Session_Expire_TS']) && $_SESSION['__HTTP_Session_Expire_TS'] < time()) {
            return true;
        } else {
            return false;
        }
    }
	
	/**
     * ����Session �������л�ʱ��Ļص�����
     * ����֮ǰ����
     */
    public function setCallback($callback = null){
        $return = ini_get('unserialize_callback_func');
        if (!empty($callback)) {
            ini_set('unserialize_callback_func',$callback);
        }
        return $return;
    }
	
}