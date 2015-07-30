reCaptcha
=========

Drop-In Replacement of Magento's core Captcha system with Googles reCaptcha

http://www.proxiblue.com.au/blog/magento-recaptcha/

* Supports placing captcha into contact us form
* Supports the 'I am not a robot' reCaptcha api (now the default)
* Support Product Review Captcha

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


Product Review Us Captcha
-------------------------

* Enable in admin under Customer Configuration by selecting 'reviews' in available forms list
* Unfortunately the core product review form does not have an after form elements block, so you will need to adjust your reviews form to display the capctha.

 Edit the reviews form located here: 
 
    app/design/frontend/[rwd|base|your package]/[default|your theme]/template/review/form.phtml
 
 place the following line into the form, anywhere between the form elements. 

    <?php echo $this->getChildHtml('recaptcha'); ?>


Our Premium extensions:
----------------------
[Magento Free Gift Promotions](http://www.proxiblue.com.au/magento-gift-promotions.html "Magento Free Gift Promotions")
The ultimate magento gift promotions module - clean code, and it just works!

[Magento Dynamic Category Products](http://www.proxiblue.com.au/magento-dynamic-category-products.html "Magento Dynamic Category Products")
Automate Category Product associations - assign any product to a category, using various rules.
