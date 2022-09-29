<?php

namespace Harriswebworks\AdobeSignCheckout\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class InstallSchema implements InstallSchemaInterface
{
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;

        $installer->startSetup();

        $table = $installer->getConnection()
            ->newTable($installer->getTable('hww_adobe_sign'))
            ->addColumn('entity_id', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, null, ['auto_increment' => true, 'identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true], 'Description')
            ->addColumn('user_id', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, null, [], 'Description')
            ->addColumn('agreement_id', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, 20, [], 'Description')
            ->addColumn('quote_id', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, 20, [], 'Description')
            ->addColumn('agreement_status', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, 20, [], 'Description');
        $installer->getConnection()->createTable($table);

        $installer->endSetup();
    }
}
