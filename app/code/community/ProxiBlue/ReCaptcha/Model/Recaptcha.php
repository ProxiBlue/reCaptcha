<?php


/**
 * The reCaptcha Model
 *
 * @category   ProxiBlue
 * @package    ProxiBlue_reCaptcha
 * @author     Lucas van Staden (sales@proxiblue.com.au)
 */
class ProxiBlue_ReCaptcha_Model_Recaptcha extends Mage_Captcha_Model_Zend implements Mage_Captcha_Model_Interface
{

    /**
     * @var array
     *
     * normally recaptcha will not show for logged in users. Form ids listed here will also appear when logged in
     *
     */
    protected $_alwaysShow = array('user_wishlist', 'product_sendtofriend');
    /**
     * Key in session for captcha code
     */
    const SESSION_WORD = 'word';


    /**
     * Helper Instance
     *
     * @var Mage_Captcha_Helper_Data
     */
    protected $_helper = null;


    /**
     * Captcha form id
     *
     * @var string
     */
    protected $_formId;
    protected $_language = 'en';
    protected $_theme = 'invisible';
    protected $_private_key = null;
    protected $_public_key = null;
    protected $_position = 'bottomleft';


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

    public function generate()
    {
        $this->_language = $this->_getHelper()->getConfigNode('language');
        $this->_theme = $this->_getHelper()->getConfigNode('theme');
        $this->_private_key = $this->_getHelper()->getConfigNode('private_key');
        $this->_public_key = $this->_getHelper()->getConfigNode('public_key');
        $this->_position = $this->_getHelper()->getConfigNode('position');
        $this->_debugEnabled = Mage::getStoreConfigFlag('customer/captcha/debug');
    }

    public function getLanguage()
    {
        return $this->_language;
    }

    public function getTheme()
    {
        return $this->_theme;
    }

    public function getPosition()
    {
        return $this->_position;
    }

    public function getPrivateKey()
    {
        return $this->_private_key;
    }

    public function getPublicKey()
    {
        return $this->_public_key;
    }

    public function isCorrect($word)
    {
        try {
            $request = Mage::app()->getRequest();
            $this->generate();
            $this->_debug(print_r($request->getParams(),true),null,'recapctha.log');
            // is this the new 'I am not a robot'?
            if($request->getParam('gcr')) {
                $request->setParam('g-recaptcha-response', $request->getParam('gcr'));
                $this->_debug("gcr request was mapped to g-recaptcha-response");
            }
            if ($response = $request->getParam('g-recaptcha-response')) {
                $path = ProxiBlue_ReCaptcha_Helper_Data::RECAPTCHA_SITEVERIFY_PATH;
                $params = array('secret' => $this->_private_key,
                                'response' => $response
                );
                $this->_debug("sending to " . $path . " params of " . print_r($params, true));
                $result = $this->_sendRequest($path, $params);
                $this->_debug("result is : " . $result);
                $response = json_decode($result);

                if (is_object($response) && $response->success == true) {
                    return true;
                } elseif(is_object($response)) {
                    $this->_debug("error " . print_r($response,true));
                    Mage::throwException(print_r($response,true));
                }
            } else {
                $this->_debug("No 'g-recaptcha-response' in request! - building ");
                $params = array('privatekey' => $this->_private_key,
                                'challenge' => $request->getParam('recaptcha_challenge_field'),
                                'response' => $request->getParam('recaptcha_response_field'),
                );
                $path = ProxiBlue_ReCaptcha_Helper_Data::RECAPTCHA_VERIFY_PATH;
                $this->_debug("sending to " . $path . " params of " . print_r($params, true));
                $result = $this->_sendRequest($path, $params);
                $this->_debug("result is : " . $result);
                $answers = explode("\n", $result);
                if (is_array($answers) && array_key_exists('0', $answers)) {
                    return (trim($answers[0]) == 'true') ? true : false;
                }
            }
        } catch (Exception $e) {
            $this->_debug("Exception fail : " . $e->getMessage());
            //Mage::log($e->getMessage());
        }

        return false;
    }

    public function getUrl()
    {
        return
            ProxiBlue_ReCaptcha_Helper_Data::RECAPTCHA_API_SERVER . ProxiBlue_ReCaptcha_Helper_Data::RECAPTCHA_API_PATH
            . '/challenge?k=' . $this->_public_key;
    }

    private function _sendRequest($path, $params)
    {
        $httpRequest = new Zend_Http_Client(
            ProxiBlue_ReCaptcha_Helper_Data::RECAPTCHA_API_SERVER
            . '/'
            . ProxiBlue_ReCaptcha_Helper_Data::RECAPTCHA_API_PATH
            . '/'
            . $path
        );
        $httpRequest->setParameterPost(array_merge(array('remoteip' => $_SERVER['REMOTE_ADDR']), $params));
        $response = $httpRequest->request('POST');
        if ($response->getStatus() != 200) {
            $this->_debug('Bad response from captcha gateway. we got ' . $response->getStatus());
            Mage::throwException('Bad response from captcha gateway. we got ' . $response->getStatus());
        }

        return $response->getBody();

    }

    /**
     * Create a unique form id
     * https://github.com/ProxiBlue/reCaptcha/issues/2
     * @return string
     */
    public function getElementId($type = 'input')
    {
        return 'captcha-' . rand(0,1000) . '-' . $type . '-box-' . trim($this->_formId);
    }

    /**
     * Whether captcha is required to be inserted to this form
     *
     * @param null|string $login
     * @return bool
     */
    public function isRequired($login = null)
    {
        if(!$this->_isEnabled() || !in_array($this->_formId, $this->_getTargetForms())){
            return false;
        }

        if (in_array($this->_formId, $this->_alwaysShow)) {
            return true;
        }

        return ($this->_isShowAlways() || $this->_isOverLimitAttempts($login)
            || $this->getSession()->getData($this->_getFormIdKey('show_captcha'))
        );
    }

    private function _debug($message) {
        if($this->_debugEnabled) {
            $message = "Form ID: ". $this->_formId . "=>" . $message;
            Mage::log($message, null, 'recapctha.log');
        }
    }
}
