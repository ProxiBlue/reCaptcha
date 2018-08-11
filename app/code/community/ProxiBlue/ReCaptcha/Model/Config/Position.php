<?php

/**
 * Captcha image type models
 *
 * @category   ProxiBlue
 * @package    ProxiBlue_reCaptcha
 * @author     Lucas van Staden (sales@proxiblue.com.au)
 */
class ProxiBlue_ReCaptcha_Model_Config_Position {

    public function toOptionArray() {
        return array(
            array('value' => 'bottomright', 'label' => 'Bottom Right'),
            array('value' => 'bottomleft', 'label' => 'Bottom Left'),
            array('value' => 'inline', 'label' => 'Inline - Using own styles')
        );
    }

}