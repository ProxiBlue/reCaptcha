<?php

/**
 * Captcha Observer
 *
 * @category    Mage
 * @package     Mage_Captcha
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class ProxiBlue_ReCaptcha_Model_Observer
{
    /**
     * Check Captcha On Contact Us
     *
     * @param Varien_Event_Observer $observer
     *
     * @return Mage_Captcha_Model_Observer
     */
    public function checkContact($observer)
    {
        $formId = 'user_contact';
        $captchaModel = Mage::helper('captcha')->getCaptcha($formId);
        if ($captchaModel->isRequired()) {
            $controller = $observer->getControllerAction();
            if (!$captchaModel->isCorrect($this->_getCaptchaString($controller->getRequest(), $formId))) {
                Mage::getSingleton('customer/session')->addError(Mage::helper('captcha')->__('Incorrect CAPTCHA.'));
                // insert form data to session, allowing to re-populate the contact us form
                $data = $controller->getRequest()->getPost();
                Mage::getSingleton('customer/session')->setFormData($data);
                $controller->setFlag('', Mage_Core_Controller_Varien_Action::FLAG_NO_DISPATCH, true);
                $controller->getResponse()->setRedirect(Mage::getUrl('*/*/'));
            }
        }

        return $this;
    }

    /**
     * Check Captcha On Product Reviews Page
     *
     * @param Varien_Event_Observer $observer
     *
     * @return Mage_Captcha_Model_Observer
     */
    public function checkReview($observer)
    {
        $formId = 'user_review';
        $captchaModel = Mage::helper('captcha')->getCaptcha($formId);
        if ($captchaModel->isRequired()) {
            $controller = $observer->getControllerAction();
            if (!$captchaModel->isCorrect($this->_getCaptchaString($controller->getRequest(), $formId))) {
                Mage::getSingleton('core/session')->addError(Mage::helper('captcha')->__('Incorrect CAPTCHA.'));
                $data = $controller->getRequest()->getPost();
                Mage::getSingleton('review/session')->setFormData($data);
                if ($this->isOldMagento()) {
                    $controller->setFlag('', Mage_Core_Controller_Varien_Action::FLAG_NO_DISPATCH, true);
                    if ($redirectUrl = Mage::getSingleton('review/session')->getRedirectUrl(true)) {
                        $controller->getResponse()->setRedirect($redirectUrl);

                        return $this;
                    }
                    $controller->getResponse()->setRedirect($this->_getRefererUrl($controller));
                } else {
                    //invalidate the formkey, which will force the controller to redirect back to referer
                    $controller->getRequest()->setParam('form_key', 'Incorrect CAPTCHA.');
                }
            }
        }

        return $this;
    }

    /**
     * Test if this is an older magento
     *
     * @return boolean
     */
    public function isOldMagento()
    {
        $isEE = Mage::helper('core')->isModuleEnabled('Enterprise_Enterprise');
        $magentoVersion = Mage::getVersionInfo();
        if ($magentoVersion['minor'] < 9 || ($isEE && $magentoVersion['minor'] < 13)) {
            return true;
        }

        return false;
    }

    /**
     * Get Captcha String
     *
     * @param Varien_Object $request
     * @param string        $formId
     *
     * @return string
     */
    protected function _getCaptchaString($request, $formId)
    {
        $captchaParams = $request->getPost(Mage_Captcha_Helper_Data::INPUT_NAME_FIELD_VALUE);

        return $captchaParams[$formId];
    }

    /**
     * Identify referer url via all accepted methods (HTTP_REFERER, regular or base64-encoded request param)
     * Compatibility with magento < 1.9
     *
     * @return string
     */
    protected function _getRefererUrl($controller)
    {
        $refererUrl = $controller->getRequest()->getServer('HTTP_REFERER');
        if ($url = $controller->getRequest()->getParam(Mage_Core_Controller_Varien_Action::PARAM_NAME_REFERER_URL)) {
            $refererUrl = $url;
        }
        if ($url = $controller->getRequest()->getParam(Mage_Core_Controller_Varien_Action::PARAM_NAME_BASE64_URL)) {
            $refererUrl = Mage::helper('core')->urlDecodeAndEscape($url);
        }
        if ($url = $controller->getRequest()->getParam(Mage_Core_Controller_Varien_Action::PARAM_NAME_URL_ENCODED)) {
            $refererUrl = Mage::helper('core')->urlDecodeAndEscape($url);
        }

        if (!$this->_isUrlInternal($refererUrl)) {
            $refererUrl = Mage::app()->getStore()->getBaseUrl();
        }

        return $refererUrl;
    }

    /**
     * Check url to be used as internal
     * Compatibility with magento < 1.9
     *
     *
     * @param   string $url
     *
     * @return  bool
     */
    protected function _isUrlInternal($url)
    {
        if (strpos($url, 'http') !== false) {
            /**
             * Url must start from base secure or base unsecure url
             */
            if ((strpos($url, Mage::app()->getStore()->getBaseUrl()) === 0)
                || (strpos($url, Mage::app()->getStore()->getBaseUrl(Mage_Core_Model_Store::URL_TYPE_LINK, true)) === 0)
            ) {
                return true;
            }
        }

        return false;
    }


}
