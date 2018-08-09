<?php

/**
 * Captcha image type models
 *
 * @category   ProxiBlue
 * @package    ProxiBlue_reCaptcha
 * @author     Lucas van Staden (sales@proxiblue.com.au)
 */
class ProxiBlue_ReCaptcha_Model_Config_Type {

    public function toOptionArray() {
        return array(
            array('value' => 'zend', 'label' => 'Core Captcha'),
            array('value' => 'recaptcha', 'label' => 'Google reCaptcha')
        );
    }

}