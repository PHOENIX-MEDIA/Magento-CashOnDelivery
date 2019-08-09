CashOnDelivery
==============

*Attention:* From version 0.4 only Magento >= 1.4.0 is supported. If you use Magento < 1.4 go to Magento Connect, switch to the "Releases" tab and choose the extension key of the last 0.3.x version which supports previous Magento releases.
From version 1.0.7 only Magento >= 1.5.0 is supported.

This extension is maintained by [PHOENIX MEDIA](http://www.phoenix-media.eu/), Magento Gold Partner in Stuttgart and Vienna.
 

Changelog
---------

1.0.15
- Quote data to order data using sales_convert_quote fieldset (Thanks to [postadelmaga](https://github.com/PHOENIX-MEDIA/Magento-CashOnDelivery/commit/85a7fc31ec3de24e89ee68a1e62f409238ec7418) (#50))
- Add "cod_fee", "base_cod_fee" attributes to webservices (Thanks to [tawfekov](https://github.com/tawfekov/Magento-CashOnDelivery/commit/db91efdf110a1b9114e9d9e232925f20d382ae32) (#33))

1.0.14
- Removed not used observer method (Thanks to [sreichel](https://github.com/sreichel/Magento-CashOnDelivery/commit/89cbd3c2d464e900c1c1ac3e00cbc8f8a09cade7))
- Fixed "Add Create Invoice Option - Add ability to configure that a cash on delivery order automatically create an invoice." (Thanks to [Flipmediaco](https://github.com/Flipmediaco/Magento-CashOnDelivery/commit/8ae36cffd3bbab8e61852810c21a3c3a558378b4) (#19))

1.0.13
- "SR-714 allow capture offline" (Thanks to [Pascal Querner](https://github.com/MSCG/Magento-CashOnDelivery/commit/75475a11d647f8f14c9a4de0654b068f6a9d079a))

1.0.12
- Add locale "en_GB", "fr_FR" and "pt_PT"  (Thanks to [MaWoScha](https://github.com/MaWoScha))
- Improve locale "it_IT" (Thanks to [emiliolodigiani](https://github.com/emiliolodigiani/Magento-CashOnDelivery/commit/0fe069c8af3645db16c12825c1d06642c580b312))
- Improve or complete other localizations

1.0.11
- Implement issue #43: Change 'foreigncosts' to 'foreigncountrycosts'

1.0.10
- Implement issue #16: CoD refund now changeable

1.0.9
- Fix issue #23: Missing CoD tax in invoice/creditmemo tax subtotal

1.0.8
- Refactored the totals handling and added CoD totals to creditmemos and guest orders
- Added composer and readme files

1.0.7
- Fixed typo and visible HTML Tag in the invoice PDF
- Added option for an percentage fee
- Refactored the module to an own namespace to prevent problems with Magento native cash on delivery payment method
- Added Modman module description file

1.0.6
- Some cosmetic improvements

1.0.5
- added min/max order amount config option
- fixed some translations oddities

1.0.4
- made COD fee available in "Checkout Totals Sort Order" tab

1.0.3
- added missing totals in invoice

1.0.2:
- Fixed missing COD Tax Fee for invoices containing configurable products

1.0.1:
- Fixed wrong COD fee displaying on order info block when "Display Cod Fee" options set to "Including Tax"

1.0.0:
- Version 0.4.8 is considered as stable. 

0.4.8:
- Fixed module setup for Magento >1.4.1  compliance 

0.4.7:
- Added missing COD fee in several documents 

0.4.6:
- Allowed store scope module configuring
- Fixed isAvailable() method: added general settings & store specific configuration handing
- Added Greek translation
- Fixed backend order/create: payment methods block is updated on address change

0.4.5:
- Added missing templates 

0.4.4:
- Added missing translations 

0.4.3:
- Added "Display COD fee incl/excl tax" option handling on checkout, order, invoice (+pdf) pages  

0.4.2:
- Small fix for backend orders 

0.4.1:
- Added COD fee in order and invoice emails (thanks to Kristof Ringleff)

0.4.0:
- Added support of the Magento 1.4
- Added COD fee to the customer->order/invoice-view/print/email pages
- Implemented "Display Zero COD fee" option
- Added COD fee to the PDFs

0.3.5:
- Fixed another tax issue with Market Ready Germany module
- Fixed translation

0.3.4:
- Fixed tax issue with Market Ready Germany module

0.3.3:
- Supports control of COD method availability for specific shipment methods
- Fixes configuration issue while upgrading from earlier versions

0.3.2:
- Removed support for MultiShipping checkout

0.3-0.3.1:
- Uses shipping country instead of billing country in checkout
- Added COD fee to order total summary
- Now supports separate tax configuration
- Fixed several tax issues
- Fixed some backend issues when creating orders

0.2.1:
- added Polish locale

0.2.0:
- finally the shipping costs and taxes are calculated (really) properly

0.1.1:
- quick update to correctly calculate shipping taxes

0.1.0
- initial public release
