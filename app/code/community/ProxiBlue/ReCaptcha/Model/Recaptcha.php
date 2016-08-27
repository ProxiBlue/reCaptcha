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

    public function generate()
    {
        $this->_language = $this->_getHelper()->getConfigNode('language');
        $this->_theme = $this->_getHelper()->getConfigNode('theme');
        $this->_private_key = $this->_getHelper()->getConfigNode('private_key');
        $this->_public_key = $this->_getHelper()->getConfigNode('public_key');
    }

    public function getLanguage()
    {
        return $this->_language;
    }

    public function getTheme()
    {
        return $this->_theme;
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
            // is this the new 'I am not a robot'?
            if ($response = $request->getParam('g-recaptcha-response')) {
                $path = ProxiBlue_ReCaptcha_Helper_Data::RECAPTCHA_SITEVERIFY_PATH;
                $params = array('secret' => $this->_private_key,
                                'response' => $response
                );
                $result = $this->_sendRequest($path, $params);
                $response = json_decode($result);
                if (is_object($response) && $response->success == true) {
                    return true;
                }
            } else {
                $params = array('privatekey' => $this->_private_key,
                                'challenge' => $request->getParam('recaptcha_challenge_field'),
                                'response' => $request->getParam('recaptcha_response_field'),
                );

                $path = ProxiBlue_ReCaptcha_Helper_Data::RECAPTCHA_VERIFY_PATH;
                $result = $this->_sendRequest($path, $params);
                $answers = explode("\n", $result);
                if (is_array($answers) && array_key_exists('0', $answers)) {
                    return (trim($answers[0]) == 'true') ? true : false;
                }
            }
        } catch (Exception $e) {
            Mage::log($e);
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
            mage::throwException('Bad response from cpatcha gateway. we got ' . $response->getStatus());
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
        return 'captcha-' . $type . '-box-' . trim($this->_formId);
    }
}
