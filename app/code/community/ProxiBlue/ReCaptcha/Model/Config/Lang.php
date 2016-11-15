<?php

/**
 * Captcha image type models
 *
 * @category   ProxiBlue
 * @package    ProxiBlue_reCaptcha
 * @author     Lucas van Staden (sales@proxiblue.com.au)
 */
class ProxiBlue_ReCaptcha_Model_Config_Lang {

	private $lang = array(array('value' => 'en', 'label' => 'English'),
		array('value' => 'fr', 'label' => 'French'),
		array('value' => 'de', 'label' => 'German'),
		array('value' => 'nl', 'label' => 'Dutch'),
		array('value' => 'pt', 'label' => 'Portuguese'),
		array('value' => 'ru', 'label' => 'Russian'),
		array('value' => 'es', 'label' => 'Spanish'),
		array('value' => 'tr', 'label' => 'Turkish'),
		array('value' => 'ar', 'label' => 'Arabic'),
		array('value' => 'af', 'label' => 'Afrikaans'),
		      array('value' => 'am', 'label' => 'Amharic'),
		      array('value' => 'hy', 'label' => 'Armenian'),
		      array('value' => 'it', 'label' => 'Italian')
	);

	public function toOptionArray() {
		return $this->lang;
	}

}
