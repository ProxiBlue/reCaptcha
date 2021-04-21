<?php

/**
 * The reCaptcha Block Used to embed into CMS pages
 *
 * @category   ProxiBlue
 * @package    ProxiBlue_reCaptcha
 * @author     Lucas van Staden (sales@proxiblue.com.au)
 */
class ProxiBlue_ReCaptcha_Block_Subscribe extends Mage_Newsletter_Block_Subscribe
{
    protected function _prepareLayout()
    {
        $captchaBlock = $this->getLayout()->createBlock('captcha/captcha', 'recaptcha')
            ->setFormId('newsletter_subscribe');
        $this->setChild('recaptcha', $captchaBlock);
        return $this;
    }
}
