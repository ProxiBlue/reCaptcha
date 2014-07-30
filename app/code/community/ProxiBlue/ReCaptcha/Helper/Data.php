<?php

/**
 * Captcha image type models
 *
 * @category   ProxiBlue
 * @package    ProxiBlue_reCaptcha
 * @author     Lucas van Staden (sales@proxiblue.com.au)
 */
class ProxiBlue_ReCaptcha_Helper_Data extends Mage_Captcha_Helper_Data {

	/**
	 * The API server address
	 */
	const RECAPTCHA_API_SERVER = "//www.google.com";
	const RECAPTCHA_API_PATH = "/recaptcha/api";
	const RECAPTCHA_VERIFY_SERVER = "www.google.com";
	const RECAPTCHA_VERIFY_PATH = "/recaptcha/api/verify";

}
