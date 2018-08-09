<?php

/**
 * Captcha image type models
 *
 * @category   ProxiBlue
 * @package    ProxiBlue_reCaptcha
 * @author     Lucas van Staden (sales@proxiblue.com.au)
 */
class ProxiBlue_ReCaptcha_Model_Config_Theme {

    public function toOptionArray() {
        return array(
            array('value' => 'invisible', 'label' => 'Invisible'),
            array('value' => 'robot', 'label' => 'I am not a Robot')
        );
    }

}