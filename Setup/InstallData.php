<?php
declare(strict_types = 1);

namespace Edvardas\MyFirstExtension\Setup;

use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Cms\Model\PageFactory;

class InstallData implements InstallDataInterface
{
    private $eavSetupFactory;
    private $pageFactory;

    public function __construct(
        EavSetupFactory $eavSetupFactory,
        PageFactory $pageFactory
    ) {
        $this->eavSetupFactory = $eavSetupFactory;
        $this->pageFactory = $pageFactory;
    }

    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $this->prepareEav();

        $this->prepareCms();
    }

    public function prepareEav(): void
    {
        $eavSetup = $this->eavSetupFactory->create();
        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'featured_product',
            [
                'group' => 'General',
                'type' => 'int',
                'label' => 'Featured product',
                'input' => 'boolean',
                'source' => 'Edvardas\MyFirstExtension\Model\Attribute\Source\Featured',
                'frontend' => 'Edvardas\MyFirstExtension\Model\Attribute\Frontend\Featured',
                'backend' => 'Edvardas\MyFirstExtension\Model\Attribute\Backend\Featured',
                'required' => false,
                'sort_order' => 50,
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                'is_used_in_grid' => false,
                'is_visible_in_grid' => false,
                'is_filterable_in_grid' => false
            ]
        );
    }

    public function prepareCms(): void
    {
        $content = '{{block class="Edvardas\MyFirstExtension\Block\FeaturedProductsBlock" template="Edvardas_MyFirstExtension::featured-product-list.phtml"}}';
        $cmsPageData = [
            'title' => 'Featured products', // cms page title
            'page_layout' => '1column', // cms page layout
            'meta_keywords' => 'Featured products', // cms page meta keywords
            'meta_description' => 'Featured products page', // cms page description
            'identifier' => 'featured-products-page', // cms page url identifier
            'content_heading' => 'Featured products page', // Page heading
            'content' => $content, // page content
            'is_active' => 1, // define active status
            'stores' => [0], // assign to stores
            'sort_order' => 0 // page sort order
        ];

        // create page
        $this->pageFactory->create()->setData($cmsPageData)->save();
    }
}