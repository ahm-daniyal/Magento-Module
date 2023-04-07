<?php declare(strict_types=1);

namespace DeveloperHub\OrderNote\Setup\Patch\Schema;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\SchemaPatchInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Quote\Setup\QuoteSetup;
use Magento\Sales\Setup\SalesSetup;
use Magento\Sales\Setup\SalesSetupFactory;
use Magento\Quote\Setup\QuoteSetupFactory;

class AddOrderNoteAttribute implements SchemaPatchInterface
{
    const ATTRIBUTE_CODE = 'developerhub_order_note';

    /** @var SchemaSetupInterface */
    private $setup;

    /** @var ModuleDataSetupInterface */
    private $moduleDataSetup;

    /** @var SalesSetupFactory */
    private $salesSetupFactory;

    /** @var QuoteSetupFactory */
    private $quoteSetupFactory;

    /**
     * @param SchemaSetupInterface $setup
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param SalesSetupFactory $salesSetupFactory
     * @param QuoteSetupFactory $quoteSetupFactory
     */
    public function __construct(
        SchemaSetupInterface $setup,
        ModuleDataSetupInterface $moduleDataSetup,
        SalesSetupFactory $salesSetupFactory,
        QuoteSetupFactory $quoteSetupFactory
    ) {
        $this->setup = $setup;
        $this->moduleDataSetup = $moduleDataSetup;
        $this->salesSetupFactory = $salesSetupFactory;
        $this->quoteSetupFactory = $quoteSetupFactory;
    }

    public function apply()
    {
        $this->setup->startSetup();
        $this->createOrderNoteAttribute();
        $this->setup->endSetup();
        return $this;
    }

    /** @return void */
    private function createOrderNoteAttribute()
    {
        /** @var QuoteSetup $quoteInstaller */
        $quoteInstaller = $this->quoteSetupFactory->create(
            ['resourceName' => 'quote_setup', 'setup' => $this->moduleDataSetup]
        );

        /** @var SalesSetup $salesInstaller */
        $salesInstaller = $this->salesSetupFactory->create(
            ['resourceName' => 'sales_setup', 'setup' => $this->moduleDataSetup]
        );

        $quoteInstaller->addAttribute(
            'quote',
            self::ATTRIBUTE_CODE,
            [
                'type' => Table::TYPE_TEXT,
                'length' => '64k', 'nullable' => true
            ]
        );

        $salesInstaller->addAttribute(
            'order',
            self::ATTRIBUTE_CODE,
            [
                'type' => Table::TYPE_TEXT,
                'length' => '64k', 'nullable' => true,
                'grid' => true
            ]
        );
    }

    /** @return array */
    public static function getDependencies(): array
    {
        return [];
    }

    /** @return array */
    public function getAliases(): array
    {
        return [];
    }
}
