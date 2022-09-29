<?php
namespace Harriswebworks\AdobeSignCheckout\Model\ResourceModel\AdobeSign;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = 'entity_id';
    protected $_eventPrefix = 'adobe_sign';
    protected $_eventObject = 'adobe_sign_collection';

    /**
     * Define the resource model & the model.
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Harriswebworks\AdobeSignCheckout\Model\AdobeSign', 'Harriswebworks\AdobeSignCheckout\Model\ResourceModel\AdobeSign');
    }
}
