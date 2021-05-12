<?php

/**
 * Captcha adapter type model
 *
 * @category   ProxiBlue
 * @package    ProxiBlue_reCaptcha
 * @author     Lucas van Staden (sales@proxiblue.com.au)
 */
class ProxiBlue_ReCaptcha_Model_Config_Adapter {

    public function toOptionArray()
    {
        return array(
            array('value' => 'Zend_Http_Client_Adapter_Socket', 'label' => 'Socket (default)'),
            array('value' => 'Zend_Http_Client_Adapter_Curl', 'label' => 'curl'),
        );
    }

}
