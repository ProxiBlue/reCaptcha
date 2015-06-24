reCaptcha
=========

Drop-In Replacement of Magento's core Captcha system with Googles reCaptcha

http://www.proxiblue.com.au/blog/magento-recaptcha/

Now supports the new 'I am not a robot' reCaptcha api.

Making captcha work in magento 1.9 (RWD theme)
----------------------------------------------

Captcha was disabled in the RWD theme that magento 1.9 uses.

This was done by simply placing an empty layout xml file into the theme.

To make captcha work in magento 1.9: 

* Place a copy of the base captcha.xml file into your own theme layout folder.

   cp app/design/frontend/base/default/layout/captcha.xml app/design/frontend/YOUR_PACKAGE/THEME/layout/captcha.xml

* or, delete that file from the rwd theme to fallback to base, 

    rm app/design/frontend/rwd/default/layout/captcha.xml

ref: http://magento.stackexchange.com/questions/40788/captcha-is-not-visible-at-frontend-login-register-form-for-rwd-theme


Our Premium extensions:
----------------------
[Magento Free Gift Promotions](http://www.proxiblue.com.au/magento-gift-promotions.html "Magento Free Gift Promotions")
The ultimate magento gift promotions module - clean code, and it just works!

[Magento Dynamic Category Products](http://www.proxiblue.com.au/magento-dynamic-category-products.html "Magento Dynamic Category Products")
Automate Category Product associations - assign any product to a category, using various rules.
