reCaptcha
=========

Drop-In Replacement of Magento's core Captcha system with Googles reCaptcha

http://www.proxiblue.com.au/blog/magento-recaptcha/

* Supports all native magento captcha areas
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

* Core magento onepage checkout for reCaptcha in Checkout.
* There is no plans to extend this extension to use 3rd party checkouts, however PRs are welcomed
* Feel free to extend if you know how. PRs welcomed.
* You require a bit of knowledge on how to extend theme templates. This reCaptcha does not rewrite or extend core 
functionality, so it does not replace theme files. Clear instructions are given. It works 'out-the-box' in all areas 
where core magento captcha works. It is more 'developer' centric, when you don't want a reCaptcha module to make major 
changes to core functionality, and allows your developer to integrate into your themes. Clear instructions are given.

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


Disabling:
==========

* Disable compilation
* Edit the file <magento root>/app/etc/modules/ProxiBlue_ReCaptcha.xml, and set the active to false.
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

* Enable in admin under Customer Configuration by selecting 'Wishlist Sharing' in available forms list
* Unfortunately the core wishlist sharing form does not have an after form elements block, so you will need to adjust your form to display the captcha.

 Edit the wishlist sharing form located here: 
 
    app/design/frontend/[rwd|base|your package]/[default|your theme]/template/wishlist/sharing.phtml
 
 place the following line into the form, anywhere within the ```<ul class="form-list">``` element.
 a good place will be just before the closing ```</ul>``` tag 

    <ul>
        <?php echo $this->getChildHtml('recaptcha'); ?>
    </ul>    

 Entered values will be retained upon incorrect captcha entry  
 
Product Email a Friend Captcha
------------------------------

The core functionality can easily be used to produce spam. 
The process is that an account is created, then a product is Emailed to a Friend, then spam is generated via the send functionality, with spam messages in the message field.
Adding reCaptcha allows you to block this.

* Enable in admin under Customer Configuration by selecting 'Email a Friend' in available forms list

Unfortunately the core Send a Friend form does not have an after form elements block, so you will need to adjust your form to display the captcha.
Additionally, the form allows you to add/remove recipients by adding/removing more recipient rows.
Some changes need to be made to the base form (copy to your own theme folder) to accommodate this functionality, else the recipients will be lost upon incorrect captcha.

The changes are done to retain the core add/remove functionality of the form. 
Since these changes are fairly big, I have provided a diff which you can apply, and for reference there is also a gist with the complete form ready to use (if you have no custom changes in your theme for the ```sendfiend/send.phtml``` template, you can simply just place the provided gist file to your theme as the template ```sendfiend\send.phtml```)

[Full file](https://gist.github.com/ProxiBlue/bff88b184c9c0e44997ff6ae05468b01)
[diff](https://gist.github.com/ProxiBlue/fdf28f9bf8b678e9aa30c475d681f974) 

####Explaining the changes: 

[recipients counter](https://gist.github.com/ProxiBlue/bff88b184c9c0e44997ff6ae05468b01#file-gistfile1-txt-L35)
This change pre-sets the counter used to add new recipients. By default this is 0, since no recipients are initially present

[pre propulate set receipients](https://gist.github.com/ProxiBlue/bff88b184c9c0e44997ff6ae05468b01#file-gistfile1-txt-L108) Injects the already defined recipients back into the form, after any failed captcha

[inject captcha form](https://gist.github.com/ProxiBlue/bff88b184c9c0e44997ff6ae05468b01#file-gistfile1-txt-L102) ads the reCaptcha form to the display
 
 
 
Entered values will be retained upon incorrect captcha entry    


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
Thsi will also wipe your api keys, so you will need to re-setup admin.



Our Premium extensions:
----------------------
[Magento Dynamic Category Products](http://www.proxiblue.com.au/magento-dynamic-category-products.html "Magento Dynamic Category Products")
Automate Category Product associations - assign any product to a category, using various rules.
