<?php
namespace Harriswebworks\AdobeSignCheckout\Logger;

class Handler extends \Magento\Framework\Logger\Handler\Base
{
    protected $loggerType = Logger::INFO;

    protected $fileName = '/var/log/hww_adobe_sign.log';
}
