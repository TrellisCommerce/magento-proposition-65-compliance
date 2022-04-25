Brought to you and maintained by [Trellis Commerce](https://trellis.co/) - A full-service eCommerce agency based in Boston, MA.

# Trellis Proposition 65 Compliance

:warning: This extension is NOT guaranteed to cover all aspects of Proposition 65 requirements. Please
do thorough assessment of your site's needs with respect to Proposition 65. This extension is meant as a tool to help
meet some Proposition 65 requirements.

This extension adds two CMS blocks that can be used throughout the site to show Proposition 65 warnings to the user. 
This extension also adds a product attribute called "Proposition 65". This product attribute allows you to select 
one of the newly created CMS blocks and display the warning message in several places:

* Product detail page
* Cart items
* Checkout item summary
* Sales order emails item rendering

## Installation
Follow the instructions below to install this extension using Composer.

```
composer config repositories.trellis/module-proposition-65-compliance git git@github.com:TrellisCommerce/magento-proposition-65-compliance
composer require trellis/module-proposition-65-compliance
bin/magento module:enable --clear-static-content Trellis_Compliance
bin/magento setup:upgrade
bin/magento cache:flush
```

## Configuration
This module creates two CMS blocks available for usage throughout the site.

* Warning #1 - generic warning message about Proposition 65.
* Warning #2 - more detailed warning message about Proposition 65.

The product attribute can be set when editing a product in admin, under the main attribute section. [Screenshot](https://share.getcloudapp.com/04uEnDvb)