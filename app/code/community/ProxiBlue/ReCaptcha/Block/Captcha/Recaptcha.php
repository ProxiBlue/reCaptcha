<?php

/**
 * The reCaptcha Block
 *
 * @category   ProxiBlue
 * @package    ProxiBlue_reCaptcha
 * @author     Lucas van Staden (sales@proxiblue.com.au)
 */
class ProxiBlue_ReCaptcha_Block_Captcha_Recaptcha extends Mage_Captcha_Block_Captcha_Zend {

	protected $_template = 'captcha/recaptcha.phtml';

    public function canRenderJs()
    {
        if (!Mage::registry('recaptcha_rendered')) {
            Mage::register('recaptcha_rendered', true, true);
            return true;
        }
        return false;
    }

}
