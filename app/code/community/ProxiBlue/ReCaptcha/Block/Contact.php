<?php

/**
 * The reCaptcha Block Used to embed into CMS pages
 *
 * @category   ProxiBlue
 * @package    ProxiBlue_reCaptcha
 * @author     Lucas van Staden (sales@proxiblue.com.au)
 */
class ProxiBlue_ReCaptcha_Block_Contact extends Mage_Core_Block_Template {

	protected $_template = 'contacts/form.phtml';

    protected function _prepareLayout()
    {
        $captchaBlock = $this->getLayout()
            ->createBlock('captcha/captcha','recaptcha')->setFormId('user_contact');
        $this->setChild('recaptcha',$captchaBlock);
        return $this;
    }
}
