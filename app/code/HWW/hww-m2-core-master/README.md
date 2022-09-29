# Harriswebworks_Core

This module is used as a wrapper for all Harriswebworks Magento 2 extensions.

## Installation


 - Add the composer repository to the configuration by running `composer config repositories.hww-module-core vcs git@github.com:harriswebworks/hww-m2-core.git`
 - Install the module composer by running `composer require harriswebworks/module-core`
 - enable the module by running `php bin/magento module:enable Harriswebworks_Core`
 - apply database updates by running `php bin/magento setup:upgrade`
 - Flush the cache by running `php bin/magento cache:flush`

