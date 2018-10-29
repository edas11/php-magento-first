<?php
declare(strict_types = 1);

namespace Edvardas\MyFirstExtension\Setup;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Cms\Model\PageFactory;
use Magento\Framework\App\State;

class InstallData implements InstallDataInterface
{
    private $eavSetupFactory;
    private $pageFactory;
    private $productRepository;
    private $searchCriteriaBuilder;

    public function __construct(
        EavSetupFactory $eavSetupFactory,
        PageFactory $pageFactory,
        ProductRepositoryInterface $productRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        State $appState
    ) {
        $this->eavSetupFactory = $eavSetupFactory;
        $this->pageFactory = $pageFactory;
        $this->productRepository = $productRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $appState->setAreaCode('frontend');
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
        $this->searchCriteriaBuilder->addFilter('featured_product', 1, 'eq');
        $this->searchCriteriaBuilder->setPageSize(5);
        $searchCriteria = $this->searchCriteriaBuilder->create();
        $products = $this->productRepository
            ->getList($searchCriteria)
            ->getItems();

        $html = '<div>';
        foreach ($products as $singleProduct) {
            $html = $html . $singleProduct->getName();
        }
        $html = $html . '</div>';

        $cmsPageData = [
            'title' => 'Featured products', // cms page title
            'page_layout' => '1column', // cms page layout
            'meta_keywords' => 'Featured products', // cms page meta keywords
            'meta_description' => 'Featured products page', // cms page description
            'identifier' => 'featured-products-page', // cms page url identifier
            'content_heading' => 'Featured products page', // Page heading
            'content' => "<h1>Featured products</h1>" . $html, // page content
            'is_active' => 1, // define active status
            'stores' => [0], // assign to stores
            'sort_order' => 0 // page sort order
        ];

        // create page
        $this->pageFactory->create()->setData($cmsPageData)->save();
    }
}