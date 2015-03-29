reCaptcha
=========

Drop-In Replacement of Magento's core Captcha system with Googles reCaptcha

http://www.proxiblue.com.au/blog/magento-recaptcha/

Now supports the new 'I am not a robot' reCaptcha api.

Making captcha work in magento 1.9 (RWD theme)
----------------------------------------------

Captcha was disabld in teh RWD theme that magento 1.9 uses.

This was done by simply placing an empty layout xml file into the theme.

To make captcha work in magento 1.9, you must delete that file, or place a copy of the base captcha.xml file into your own theme layout folder.

ref: http://magento.stackexchange.com/questions/40788/captcha-is-not-visible-at-frontend-login-register-form-for-rwd-theme
