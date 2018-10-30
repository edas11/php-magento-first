<?php
declare(strict_types = 1);

namespace Edvardas\MyFirstExtension\Block;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\View\Element\Template;

class FeaturedProductsBlock extends Template
{
    private $productRepository;
    private $searchCriteriaBuilder;

    public function __construct(
        Template\Context $context,
        ProductRepositoryInterface $productRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        array $data = array()
    ) {
        parent::__construct($context, $data);
        $this->productRepository = $productRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    public function getFeaturedProducts()
    {
        $this->searchCriteriaBuilder->addFilter('featured_product', 1, 'eq');
        $this->searchCriteriaBuilder->setPageSize(5);
        $searchCriteria = $this->searchCriteriaBuilder->create();
        $products = $this->productRepository
            ->getList($searchCriteria)
            ->getItems();
        return $products;
        //$products[0]->getName();
    }
}