<?php

/**
 * Captcha image type models
 *
 * @category   ProxiBlue
 * @package    ProxiBlue_reCaptcha
 * @author     Lucas van Staden (sales@proxiblue.com.au)
 */
class ProxiBlue_ReCaptcha_Model_Config_Themes {

	public function toOptionArray() {
		return array(array('value' => 'clean', 'label' => 'Clean'),
			array('value' => 'white', 'label' => 'White'),
			array('value' => 'red', 'label' => 'Red'),
			array('value' => 'blackglass', 'label' => 'Blackglass'),
			array('value' => 'local', 'label' => 'Local'),
            array('value' => 'New', 'label' => 'I am not a Robot')
		);
	}

}
