<?php

/**
 * Captcha image type models
 *
 * @category   ProxiBlue
 * @package    ProxiBlue_reCaptcha
 * @author     Lucas van Staden (sales@proxiblue.com.au)
 */
class ProxiBlue_ReCaptcha_Model_Config_Lang {

	private $lang = array(
                array('value' => 'en', 'label' => 'English (US)'),
                array('value' => 'en-GB', 'label' => 'English (US)'),
		array('value' => 'fr', 'label' => 'French'),
                array('value' => 'fr-CA', 'label' => 'French (Canadian)'),	
		array('value' => 'de', 'label' => 'German'),
                array('value' => 'de-AT', 'label' => 'German (Austria)'),
                array('value' => 'de-CH', 'label' => 'German (Switzerland)'),
		array('value' => 'nl', 'label' => 'Dutch'),
		array('value' => 'pt', 'label' => 'Portuguese'),
                array('value' => 'pt-BR', 'label' => 'Portuguese (Brazil)'),
                array('value' => 'pt-PT', 'label' => 'Portuguese (Portugal)'),
		array('value' => 'ru', 'label' => 'Russian'),
		array('value' => 'es', 'label' => 'Spanish'),
                array('value' => 'es-419', 'label' => 'Spanish (Latin America)'),
		array('value' => 'tr', 'label' => 'Turkish'),
		array('value' => 'ar', 'label' => 'Arabic'),
		array('value' => 'af', 'label' => 'Afrikaans'),
                array('value' => 'am', 'label' => 'Amharic'),
                array('value' => 'hy', 'label' => 'Armenian'),
                array('value' => 'az', 'label' => 'Azerbaijani'),
                array('value' => 'eu', 'label' => 'Basque'),
                array('value' => 'bn', 'label' => 'Bengali'),
                array('value' => 'bg', 'label' => 'Bulgarian'),
                array('value' => 'ca', 'label' => 'Catalan'),
                array('value' => 'zh-HK', 'label' => 'Chinese (Hong Kong)'),
                array('value' => 'zh-CN', 'label' => 'Chinese (Simplified)'),
                array('value' => 'zh-TW', 'label' => 'Chinese (Traditional)'),
                array('value' => 'hr', 'label' => 'Croatian'),
                array('value' => 'cs', 'label' => 'Czech'),
                array('value' => 'da', 'label' => 'Danish'),
                array('value' => 'et', 'label' => 'Estonian'),
                array('value' => 'fil', 'label' => 'Filipino'),
                array('value' => 'fi', 'label' => 'Finnish'),
                array('value' => 'cs', 'label' => 'Czech'),
                array('value' => 'da', 'label' => 'Danish'),
                array('value' => 'et', 'label' => 'Estonian'),
                array('value' => 'fil', 'label' => 'Filipino'),
                array('value' => 'fi', 'label' => 'Finnish'),
                array('value' => 'it', 'label' => 'Italian'),
                array('value' => 'gl', 'label' => 'Galician'),
                array('value' => 'ka', 'label' => 'Georgian'),	
                array('value' => 'el', 'label' => 'Greek'),
                array('value' => 'gu', 'label' => 'Gujarati'),
                array('value' => 'iw', 'label' => 'Hebrew'),	
                array('value' => 'hi', 'label' => 'Hindi'),	
                array('value' => 'hu', 'label' => 'Hungarain'),	
                array('value' => 'is', 'label' => 'Icelandic'),	
                array('value' => 'id', 'label' => 'Indonesian'),	
                array('value' => 'ja', 'label' => 'Japanese'),	
                array('value' => 'kn', 'label' => 'Kannada'),	
                array('value' => 'ko', 'label' => 'Korean'),	
                array('value' => 'lo', 'label' => 'Laothian'),	
                array('value' => 'lv', 'label' => 'Latvian'),	
                array('value' => 'lt', 'label' => 'Lithuanian'),	
                array('value' => 'ms', 'label' => 'Malay'),	
                array('value' => 'ml', 'label' => 'Malayalam'),	
                array('value' => 'mr', 'label' => 'Marathi'),	
                array('value' => 'mn', 'label' => 'Mongolian'),	
                array('value' => 'no', 'label' => 'Norwegian'),	
                array('value' => 'fa', 'label' => 'Persian'),	
                array('value' => 'pl', 'label' => 'Polish'),	
                array('value' => 'ro', 'label' => 'Romanian'),	
                array('value' => 'sr', 'label' => 'Serbian'),	
                array('value' => 'si', 'label' => 'Sinhalese'),	
                array('value' => 'sk', 'label' => 'Slovak'),	
                array('value' => 'sl', 'label' => 'Slovenian'),	
                array('value' => 'sw', 'label' => 'Swahili'),	
                array('value' => 'sv', 'label' => 'Swedish'),	
                array('value' => 'ta', 'label' => 'Tamil'),	
                array('value' => 'te', 'label' => 'Telugu'),	
                array('value' => 'th', 'label' => 'Thai'),	
                array('value' => 'uk', 'label' => 'Ukrainian'),	
                array('value' => 'ur', 'label' => 'Urdu'),	
                array('value' => 'vi', 'label' => 'Vietnamese'),	
                array('value' => 'zu', 'label' => 'Zulu'),
	);        


	public function toOptionArray() {
		return $this->lang;
	}

}
