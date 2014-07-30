<?php


/**
 * The reCaptcha Model
 *
 * @category   ProxiBlue
 * @package    ProxiBlue_reCaptcha
 * @author     Lucas van Staden (sales@proxiblue.com.au)
 */
class ProxiBlue_ReCaptcha_Model_Recaptcha extends Mage_Captcha_Model_Zend implements Mage_Captcha_Model_Interface {

	/**
     * Key in session for captcha code
     */
    const SESSION_WORD = 'word';

    
    /**
     * Helper Instance
     * @var Mage_Captcha_Helper_Data
     */
    protected $_helper = null;

    
    /**
     * Captcha form id
     * @var string
     */
    protected $_formId;
	protected $_language = 'en';
	protected $_theme = 'clean';
	protected $_private_key = null;
	protected $_public_key = null;

	/**
	 * Get Block Name
	 *
	 * @return string
	 */
    public function getBlockName()
    {
        return 'proxiblue_recaptcha/captcha_recaptcha';
	}

    /**
     * Returns captcha helper
     *
     * @return Mage_Captcha_Helper_Data
     */
    protected function _getHelper()
    {
        if (empty($this->_helper)) {
            $this->_helper = Mage::helper('proxiblue_recaptcha');
		}
        return $this->_helper;
    }

	public function generate() {
		$this->_language = $this->_getHelper()->getConfigNode('language');
		$this->_theme = $this->_getHelper()->getConfigNode('theme');
		$this->_private_key = $this->_getHelper()->getConfigNode('private_key');
		$this->_public_key = $this->_getHelper()->getConfigNode('public_key');
	}

	public function getLanguage() {
		return $this->_language;
	}

	public function getTheme() {
		return $this->_theme;
	}

	public function getPrivateKey() {
		return $this->_private_key;
	}

	public function getPublicKey() {
		return $this->_public_key;
	}

	public function isCorrect($word) {
		try {
			$request = Mage::app()->getRequest();
			$this->generate();
			$params = http_build_query(array('privatekey' => $this->_private_key,
				'remoteip' => $_SERVER['REMOTE_ADDR'],
				'challenge' => $request->getParam('recaptcha_challenge_field'),
				'response' => $request->getParam('recaptcha_response_field'),
			'', '&amp;'));

			$host = ProxiBlue_ReCaptcha_Helper_Data::RECAPTCHA_VERIFY_SERVER;
			$path = ProxiBlue_ReCaptcha_Helper_Data::RECAPTCHA_VERIFY_PATH;

			$http_request = "POST $path HTTP/1.0\r\n";
			$http_request .= "Host: $host\r\n";
			$http_request .= "Content-Type: application/x-www-form-urlencoded;\r\n";
			$http_request .= "Content-Length: " . strlen($params) . "\r\n";
			$http_request .= "User-Agent: reCAPTCHA/PHP\r\n";
			$http_request .= "\r\n";
			$http_request .= $params;

			$response = '';
			if (false == ( $fs = @fsockopen($host, 80, $errno, $errstr, 10) )) {
				mage::log('reCaptcha - could not open socket to google api: ' . $errstr);
				return false;
			}

			fwrite($fs, $http_request);

			while (!feof($fs))
				$response .= fgets($fs, 1160); // One TCP-IP packet
			fclose($fs);
			$response = explode("\r\n\r\n", $response, 2);
			$answers = explode("\n", $response [1]);
			if (is_array($answers) && array_key_exists('0', $answers)) {
				return (trim($answers[0]) == 'true') ? true : false;
			}
		} catch (Exception $e) {
			Mage::log($e);
		}
		return false;
	}

	public function getUrl() {
		return ProxiBlue_ReCaptcha_Helper_Data::RECAPTCHA_API_SERVER . ProxiBlue_ReCaptcha_Helper_Data::RECAPTCHA_API_PATH . '/challenge?k=' . $this->_public_key;
	}

}
