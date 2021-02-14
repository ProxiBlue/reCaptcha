reCaptcha
=========

Drop-In Replacement of OpenMage core Captcha system with Googles reCaptcha

http://www.proxiblue.com.au/blog/magento-recaptcha/

* Supports all native OpenMage captcha areas
* Supports placing captcha into 
   
  * Contact Us form, 
  * CMS pages (contact us in cms page), 
  * Products Reviews 
  * Customer Wishlist Sharing
  * Product 'Email a friend'
  * Newsletter Subscribe
 
* Supports Invisible reCaptcha (now the default) with option to set badge position
* Supports the 'I am not a robot' reCaptcha (just in case you don't want invisible)

Requirements
============

* Core onepage checkout for reCaptcha in Checkout.
* There is no plans to extend this extension to use 3rd party checkouts, however PRs are welcomed 
** Works with FireCheckout - https://github.com/ProxiBlue/reCaptcha/pull/40 
* Feel free to extend if you know how. PRs welcomed.
* You require a bit of knowledge on how to extend theme templates. This reCaptcha does not rewrite or extend core 
functionality, so it does not replace theme files. Clear instructions are given. It works 'out-the-box' in all areas 
where core OpenMage captcha works. It is more 'developer' centric, when you don't want a reCaptcha module to make major 
changes to core functionality, and allows your developer to integrate into your themes. Clear instructions are given.

Installing
==========

VERSIONS:
=========

If you have magento 1.9.4, *OR* you have Magento < 1.9.4 + SUPEE 10975 patch installed, you must use release 2.1.x or greater.
If you have Magento < 1.9.4 and not pacthed with SUPEE 10975, then you must use version 2.0.1 (the most up-to-date version prior to 1.9.4 and SUPEE 10975 patch)
If you have OpenMage use release 2.1.x or greater.

Your *should* patch to SUPEE 10975 else your store is a security risk!


All:
----

* if captcha is enabled, disable it. including for admin

By GIT:
-------

* clone this repo
* disable compilation if you use that.
* copy the files from the repo into the base folder of your magento / OpenMage install
* clear your cache
* re-enable compilation

By Composer:
------------

Direct from GitHub repo:
------------------------

* disable compilation (mageno 1 only, OpenMage have removed the compilation feature)

* Update the following to sections in your composer file:

```
   "require": {
       "proxiblue/recaptcha": "*"
   },
   "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/ProxiBlue/reCaptcha.git"
        }
    ],
```
* Update composer: ```composer update```
* Clear cache
* re-enable compilation (mageno 1 only, OpenMage have removed the compilation feature)

Using composer package:
-----------------------

In the root of your OpenMage install, run the following commands:

```
composer config --global --auth http-basic.github.repo.repman.io token ec42f7dd9269f0d9355f94279d221d3f07c4f8c70c9344a1ef27ddbc0a07a8d6
```

Add these lines to your composer.json file, or add a new repository URL if you already have one or more:

```
{
    "repositories": [
        {"type": "composer", "url": "https://github.repo.repman.io"}
    ]
}
```

Disabling:
==========

* Disable compilation
* Edit the file <site root>/app/etc/modules/ProxiBlue_ReCaptcha.xml, and set the active to false.
* Remove any theme captcha insertion code as described below in all sections
* Clear Cache
* Enable compilation

This *should* not happen, but if you uninstall the module, you *could* run into an error that the recaptcha block is not available

```exception 'Mage_Core_Exception' with message 'Invalid block type: ProxiBlue_ReCaptcha_Block_Captcha_Recaptcha'```

You need to remove two entries from the core table ```core_config_data``` (one for admin, one for frontend)

* access your database via your prefered MySQL admin tool (for example PHPMyAdmin)
* run: ```DELETE FROM `core_config_data` WHERE `path` = 'customer/capctha/type';
* run: ```DELETE FROM `core_config_data` WHERE `path` = 'admin/capctha/type';
* clear your cache by deleting the ```var/cache``` folder

if you use n98-magerun:

* n98-magerun config:delete customer/capctha/type
* n98-magerun config:delete admin/capctha/type
* n98-magerun cache:clean


Setup:
======

* Obtain your site public key (Site Key) and private key (Secret Key) for your domain and recaptcha usage
* Note that Invisible requires new keys, and existing keys for 'I am not a robot' will NOT work!
* Enter these in the admin settings for captcha.

You can get testing/developer keys here: https://developers.google.com/recaptcha/docs/faq

V1 Captcha support dropped
----------------------------------------------

Since 1.4.0 all v1 captcha (pre I am not a Robot) has been removed.


Disable / Enable form submit buttons
------------------------------------

From 1.4.0 you can add the class 'enable-captcha-clicked' to any element and add the 'disabled' property to that element.
After clicked, the element will be enabled.

example:

```html
<button type="submit" class="btn btn-primary enable-captcha-clicked" disabled="disabled">Submit</button>
```

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
 
 place the following line into the form, anywhere between ```<ul class="form-list">``` and closing ```<ul>``` elements in the form

    <?php echo $this->getChildHtml('recaptcha'); ?>

Unfortunately magento core templates do not accommodate reloading the posted form data.
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

Submitting Contact Us via AJAX
------------------------------

From version 1.3.0, you can pass two additional params via an AJAX submitted form.
The response form the module will then be a JSON string denoting if the captcha failed.

Example AJAX call to submit a contact us form:

     $j.ajax({
         url: $j('#contactForm').attr('action'),
         type: 'POST',
         data: {
             help: $j("#help").val(),
             firstname: $j("#firstname").val(),
             lastname: $j("#lastname").val(),
             email: $j("#email-address").val(),
             telephone: $j("#telephone").val(),
             suburb: $j("#suburb").val(),
             postcode: $j("#postcode").val(),
             comment: $j("#comment").val(),
             about: $j("#about").val(),
             consultant: $j("#consultant").val(),
             json: 1,
             gcr: $j("#g-recaptcha-response").val()
         },
         success: function (result, xhr) {
             try {
                 var result = jQuery.parseJSON(result);
             } catch (err) {
                 // fail silently as result was not JSON, so could be success
             }
             if(typeof result =='object') {
                 if (result.error) {
                     alert(result.error);
                 }
             } else {
                 // assume a success as not capctha error
                 // deal with any other form errors here.
             }
         },
         error: function (xhr, err) {
             alert(err);
         }
     });
     event.preventDefault();
     return false;

Note the inclusion of two extra variables in the POST:

     json: 1,
     gcr: $j("#g-recaptcha-response").val()

Use in CMS Page
----------------

You can Place the Contact Us form within a CMS page using the following Block notation:

     <ul>
     {{block type="proxiblue_recaptcha/contact" name="contactForm" form_action="/contacts/index/post" template="contacts/form.phtml"}}
     </ul>

Remember to add the custom block to your allowed blocks in System->Permissions->Blocks. Use ```proxiblue_recaptcha/contact```

Product Review Captcha
-------------------------

* Enable in admin under Customer Configuration by selecting 'reviews' in available forms list
* Unfortunately the core product review form does not have an after form elements block, so you will need to adjust your reviews form to display the captcha.

 Edit the reviews form located here: 
 
    app/design/frontend/[rwd|base|your package]/[default|your theme]/template/review/form.phtml
 
 place the following line into the form, anywhere between the form elements. 

    <ul>
        <?php echo $this->getChildHtml('recaptcha'); ?>
    </ul>
    
Customer Wishlist Sharing Captcha
-------------------------

The core functionality can easily be used to produce spam. 
The process is that an account is created, then a product is added, then spam is generated via the share functionality, with spam messages in the message field.
Adding reCaptcha allows you to block this.


** Magento introduced wishlist capctha from 1.9.4, or with SUPEE 10975 **

The custom wishlist recaptcha code of this module was removed, favouring the core functionality.
If you require wishlist recapctha in pre 1.9.4 without SUPEE-10975, install version 2.0.1 

Product Email a Friend Captcha
------------------------------

** Magento introduced capctha from 1.9.4, or with SUPEE 10975 **

The custom recaptcha code of this module was removed, favouring the core functionality.
If you require recapctha in pre 1.9.4 without SUPEE-10975, install version 2.0.1 

Newsletter Subscribe Captcha
----------------------------

Most sites have newsletter subscribe option, on every page. This is a big source for spam.
With invisible recaptcha option you can limit this now, without adding extra effort for user to subscribe.

To make reCaptcha appear on subscriber form/page, you need to edit this template:

    app/design/frontend/[rwd|base|your package]/[default|your theme]/template/newsletter/subscribe.phtml 

Place the following code between the ```<form>``` and closing ```</form>``` elements:

````
<ul>
   <?php echo $this->getChildHtml('recaptcha'); ?>
</ul>

````

Ensure options are set in admin to allow recaptcha for newsletter, and using Invisible reCaptcha is recommeded


Captcha is still not appearing, even after I did the steps above!
-----------------------------------------------------------------

Some possibilities:

* You are using a custom theme package, and the reCaptcha layout directive file is not loaded. 
* You are using a custom theme and the fallback to the base theme is not picking up the layout file. 

To fix this, simply copy the file 

    app/design/frontend/base/default/layout/proxiblue_recaptcha.xml

to your package or theme folder, which will be located something like such: 

    app/design/frontend/<PACKAGE_NAME>/<THEME NAME>/layout/proxiblue_recaptcha.xml

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

* It may be you have old captcha settings stuck in db, and they need to be cleared out.

Run this SQL against your db: ```DELETE FROM core_config_data where path like '%captcha%'```
This will also wipe your api keys, so you will need to re-setup admin.

Admin Forgot password is not working
====================================

This is NOT caused by the recapctha module/code, and is a core bug

ref: https://magento.stackexchange.com/questions/125453/admin-forgot-password-does-not-work-with-x-content-type-options-nosniff-header

There are multiple options for 'Wishlist and Product sharing' optiosn in admin form
===================================================================================

This was caused by upgrade past version 2.0.1

You need to clear out the old admin config, and resetup the required forms:

```
delete from core_config_data where path = 'customer/captcha/forms';
delete from core_config_data where path = 'admin/captcha/forms';
```


Our Premium extensions:
----------------------
[Magento Dynamic Category Products](http://www.proxiblue.com.au/magento-dynamic-category-products.html "Magento Dynamic Category Products")
Automate Category Product associations - assign any product to a category, using various rules.
