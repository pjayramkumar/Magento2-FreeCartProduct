# Magento2-FreeCartProduct
Magento 2 Free Cart Product allow to add Free Product to cart along with Magento Visible Product

![Magento >= 2.0.0](https://img.shields.io/badge/magento-%3E=2.0.0-blue.svg)

Installation 
--------------

- ### php bin/magento module:enable Elightwalk_Freecartproduct
- ### php bin/magento setup:upgrade
- ### php bin/magento setup:di:compile

Module Features 
--------------

- ### Add Free Product to Cart along with Magento any Product type.
- ### Specify the Free Product Sku in System Configuration.
- ### Remove the Product to cart Free Product remove itself.


Troubleshoot 
--------------

- If you facing merory issue during the above commands follow to add params in the command -d memory_limit=-1.
for example php bin/magento -d memory_limit=-1 setup:upgrade


Help & Contact  
--------------

For extra help contact us on https://www.elightwalk.com/
