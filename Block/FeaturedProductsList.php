<?php
declare(strict_types = 1);

namespace Edvardas\MyFirstExtension\Block;

use Magento\Catalog\Block\Product\ListProduct;
use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Catalog\Model\Layer\Resolver;
use Magento\Framework\Data\Helper\PostHelper;
use Magento\Framework\Url\Helper\Data;

class FeaturedProductsList extends ListProduct
{
    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        \Magento\Catalog\Model\ResourceModel\Product\Collection $collection,
        PostHelper $postDataHelper,
        Resolver $layerResolver,
        CategoryRepositoryInterface $categoryRepository,
        Data $urlHelper,
        array $data = array()
    ) {
        parent::__construct($context, $postDataHelper, $layerResolver, $categoryRepository, $urlHelper, $data);
        $this->setCollection($this->getFeaturedProducts($collection));
    }

    public function getFeaturedProducts(\Magento\Catalog\Model\ResourceModel\Product\Collection $productCollection)
    {
        $limitProducts = $this->_scopeConfig->getValue('firstExtensionConfig/general/limit');
        $productCollection->addAttributeToSelect('*');
        $productCollection->addAttributeToFilter('featured_product', ['eq' => '1']);
        $productCollection->setPageSize($limitProducts);
        $productCollection->setCurPage(1);
        return $productCollection->load();
    }
}