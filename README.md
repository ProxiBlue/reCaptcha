reCaptcha
=========

Drop-In Replacement of Magento's core Captcha system with Googles reCaptcha

http://www.proxiblue.com.au/blog/magento-recaptcha/

* Supports all native magento captcha areas
* Supports placing captcha into contact us form
* Supports the 'I am not a robot' reCaptcha api (now the default)
* Support Product Review Captcha

Requirements
============

Core magento onepage checkout for reCaptcha in Checkout.
There is no plans to extend this extension to ise 3rd party checkouts.
Feel free to extend if you know how. PR welconed.

Installing
==========

All:
----

* if captcha is enabled, disable it. including for admin

By GIT:
-------

* clone this repo
* disable compilation if you use that.
* copy the files from the repo into the base folder of your magento install
* clear your cache
* re-enable compilation

By Composer:
------------

* disable compilation

* Update the following to sections in your composer file:

```
   "require": {
           "proxiblue/recaptcha":"*"
      },
   "repositories": [
      {
            "type": "vcs",
                "url": "https://github.com/ProxiBlue/reCaptcha.git"
        }
    ],
```
* Update composer: ```composer.phar update```
* Clear cache
* re-enable compilation

By Magento Connect 1:
---------------------

Check version number available via connect against what is in GIT. It is most likely that the connect package is dated, and it is prefered to install via composer or direct from git.

Disabling:
==========

* Disable compilation
* Edit the file <magento root>/app/etc/modules/ProxiBlue_ReCaptcha.xml, and set the active to false.
* Clear Cache
* Enable compilation

Setup:
======

* Obtain your site public key (Site Key) and private key (Secret Key) for your domain and recaptcha usage
* Enter these in the admin settings for captcha.

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

Contact Us Captcha
------------------

* Enable in admin under Customer Configuration by selecting COntacts in available forms list
* Unfortunately the core contact us form does not have before or after form elements block, so you will need to adjust your contact us form to display the capctha.

 Edit the contact form located here: 
 
    app/design/frontend/[rwd|base|your package]/[default|your theme]/template/contacts/form.phtml
 
 place the following line into the form, anywhere between the form elements. 

    <?php echo $this->getChildHtml('recaptcha'); ?>

Unfortunately magento core templates do not accomodate reloading the posted form data.
This means that if the captcha was incorrect, the user will be given a new blank form.
Obviously not ideal.

The captcha extension places the form data into the customer session, aptly named 'formData', using the following lines of code

    $data = $controller->getRequest()->getPost();
    Mage::getSingleton('customer/session')->setFormData($data);

You can re-populate the form data using the information stored in the session. 
This will require you to make some changes to the form.phtml file. 
It is really up to you how you will retrieve and use the session data.
As an example, you can do this at the top of the template form.phtml:

    $formData = new Varien_Object();
    $formData->setData(Mage::getSingleton('customer/session')->getFormData());
   
The posted data is now held in the Varien Object called $formData
You can pre-populate the data as such:

    $_firstname = ($formData->getFirstname())?$formData->getFirstname():$this->helper('contacts')->getFirstName();
    $_lastname = ($formData->getLastname())?$formData->getLastname():$this->helper('contacts')->getLastName();
    $_email = ($formData->getEmail())?$formData->getEmail():$this->helper('contacts')->getEmail();
    $_telephone = ($formData->getTelephone())?$formData->getTelephone():'';
    $_suburb = ($formData->getSuburb())?$formData->getSuburb():'';  
    $_postcode = ($formData->getPostcode())?$formData->getPostcode():'';
    $_comment = ($formData->getComment())?$formData->getComment():'';

and in the template, simply echo out the values held in the definded variables:
An example is as such:

    <input name="firstname" id="firstname" title="<?php echo Mage::helper('contacts')->__('First Name') ?>" value="<?php echo $this->htmlEscape($_firstname) ?>" class="input-text required-entry" type="text" />


Product Review Captcha
-------------------------

* Enable in admin under Customer Configuration by selecting 'reviews' in available forms list
* Unfortunately the core product review form does not have an after form elements block, so you will need to adjust your reviews form to display the captcha.

 Edit the reviews form located here: 
 
    app/design/frontend/[rwd|base|your package]/[default|your theme]/template/review/form.phtml
 
 place the following line into the form, anywhere between the form elements. 

    <?php echo $this->getChildHtml('recaptcha'); ?>

Captcha is still not appearing, even after I did the steps above!
-----------------------------------------------------------------

Some possibilities:

* You are using a custom theme package, and the reCaptcha layout directive file is not loaded. 
* You are using a custom theme and the fallback to the base theme is not picking up the layout file. 

To fix this, simply copy the file `app/design/frontend/base/default/layout/proxiblue_recaptcha.xml` to your package or theme folder, which will be located something like such: `app/design/frontend/<PACKAGE_NAME>/<THEME NAME>/layout/proxiblue_recaptcha.xml`

* You have directives in your custom theme that changes how the review and customer screen layouts are built.

There can be quite a few ways that your custom theme/package changed this. The most common would be in your local.xml file, located at `app/design/frontend/<PACKAGE_NAME>/<THEME NAME>/layout/local.xml`
In that file, locate the relevant sections as noted in the reCaptcha layout file (https://github.com/ProxiBlue/reCaptcha/blob/master/app/design/frontend/base/default/layout/proxiblue_recaptcha.xml)
Insert into the layout sections the relevant reCaptcha parts.

For example, if you have this section in your lcoal.xml file

```
<review_product_list>
</review_product_list>
```

copy the entire section from the reCaptcha layout over into that section.

```
<reference name="product.review.form">
            <block type="captcha/captcha" name="recaptcha">
                <action method="setFormId">
                    <formId>user_review</formId>
                </action>
                <action method="setImgWidth">
                    <width>230</width>
                </action>
                <action method="setImgHeight">
                    <width>50</width>
                </action>
            </block>
        </reference>
</review_product_list>
```

If, you also have the following:  ```<reference name="product.review.form">``` then only copy the BLOCK definition part into that reference.





Our Premium extensions:
----------------------
[Magento Free Gift Promotions](http://www.proxiblue.com.au/magento-gift-promotions.html "Magento Free Gift Promotions")
The ultimate magento gift promotions module - clean code, and it just works!

[Magento Dynamic Category Products](http://www.proxiblue.com.au/magento-dynamic-category-products.html "Magento Dynamic Category Products")
Automate Category Product associations - assign any product to a category, using various rules.
