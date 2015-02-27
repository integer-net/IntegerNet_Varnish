IntegerNet_Varnish
==================

Magento External Full Page Cache with Varnish

Infos
-----
- Module Name: IntegerNet_Varnish
- Version: 1.0.0

Description
-----------


Requirements
------------
- PHP >= 5.2.0
- PHP <= 5.6.9


Compatibility
-------------
- Magento >= 1.5

Installation Instructions
-------------------------
1. Install the extension by copying all the files in `src` into your Magent document root.
2. Clear the cache, logout from the admin panel and then login again.
3. Configure the extension under System ***System >> Configuration >> Advanced/System >> External Full Page Cache Settings***

Uninstallation Instructions
--------------
1. Set Magento "Enable External Cache" option under ***System >> Configuration >> Advanced/System >> External Full Page Cache Settings*** to ***No***
2. Disable IntegerNet_Varnish module by deleting the `app/etc/modules/IntegerNet_Varnish.xml` File
3. Clear the cache, logout from the admin panel and then login again.
4. delete folowing IntegerNet_Varnish module files and directories
- app/etc/modules/IntegerNet_Varnish.xml
- app/code/community/IntegerNet/Varnish
- app/design/adminhtml/default/default/layout/integernet_varnish.xml
- app/design/adminhtml/default/default/template/integernet_varnish
- app/design/frontend/base/default/layout/integernet_varnish.xml
- app/design/frontend/base/default/template/integernet_varnish
- app/locale/de_DE/IntegerNet_Varnish.csv
- app/locale/en_US/IntegerNet_Varnish.csv
- js/integernet_varnish
- shell/integernet_varnish.php
- var/integernet_varnish
5. Remover entitys from database table `<table-prefix>core_config_data` path stars with `system/external_page_cache/integernet_varnish_`
6. Remote table `<table-prefix>integernet_varnish_index`

Support
-------
info@integer-net.de

Developer
---------
Viktor Franz
[http://www.integer-net.de/](http://www.integer-net.de)
Twitter [@integer_net](https://twitter.com/integer_net)

Licence
-------
Proprietary

Copyright
---------
(c) 2015 integer_net GmbH