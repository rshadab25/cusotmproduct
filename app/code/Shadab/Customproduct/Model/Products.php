<?php
/**
 * Copyright © Shadab All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Shadab\Customproduct\Model;

use Magento\Framework\Api\DataObjectHelper;
use Shadab\Customproduct\Api\Data\ProductsInterface;
use Shadab\Customproduct\Api\Data\ProductsInterfaceFactory;

class Products extends \Magento\Framework\Model\AbstractModel
{

    protected $dataObjectHelper;

    protected $_eventPrefix = 'customproducts';
    protected $productsDataFactory;


    /**
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param ProductsInterfaceFactory $productsDataFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param \Shadab\Customproduct\Model\ResourceModel\Products $resource
     * @param \Shadab\Customproduct\Model\ResourceModel\Products\Collection $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        ProductsInterfaceFactory $productsDataFactory,
        DataObjectHelper $dataObjectHelper,
        \Shadab\Customproduct\Model\ResourceModel\Products $resource,
        \Shadab\Customproduct\Model\ResourceModel\Products\Collection $resourceCollection,
        array $data = []
    ) {
        $this->productsDataFactory = $productsDataFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * Retrieve products model with products data
     * @return ProductsInterface
     */
    public function getDataModel()
    {
        $productsData = $this->getData();
        
        $productsDataObject = $this->productsDataFactory->create();
        $this->dataObjectHelper->populateWithArray(
            $productsDataObject,
            $productsData,
            ProductsInterface::class
        );
        
        return $productsDataObject;
    }
}

