<?php
/**
 * Copyright © Shadab All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Shadab\Customproduct\Model;

use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Api\ExtensibleDataObjectConverter;
use Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\Store\Model\StoreManagerInterface;
use Shadab\Customproduct\Api\Data\ProductsInterfaceFactory;
use Shadab\Customproduct\Api\Data\ProductsSearchResultsInterfaceFactory;
use Shadab\Customproduct\Api\ProductsRepositoryInterface;
use Shadab\Customproduct\Model\ResourceModel\Products as ResourceProducts;
use Shadab\Customproduct\Model\ResourceModel\Products\CollectionFactory as ProductsCollectionFactory;

class ProductsRepository implements ProductsRepositoryInterface
{

    protected $productsFactory;

    protected $resource;

    protected $extensibleDataObjectConverter;
    protected $searchResultsFactory;

    protected $productsCollectionFactory;

    protected $dataProductsFactory;

    private $storeManager;

    protected $dataObjectHelper;

    protected $dataObjectProcessor;

    protected $extensionAttributesJoinProcessor;

    private $collectionProcessor;


    /**
     * @param ResourceProducts $resource
     * @param ProductsFactory $productsFactory
     * @param ProductsInterfaceFactory $dataProductsFactory
     * @param ProductsCollectionFactory $productsCollectionFactory
     * @param ProductsSearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param StoreManagerInterface $storeManager
     * @param CollectionProcessorInterface $collectionProcessor
     * @param JoinProcessorInterface $extensionAttributesJoinProcessor
     * @param ExtensibleDataObjectConverter $extensibleDataObjectConverter
     */
    public function __construct(
        ResourceProducts $resource,
        ProductsFactory $productsFactory,
        ProductsInterfaceFactory $dataProductsFactory,
        ProductsCollectionFactory $productsCollectionFactory,
        ProductsSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager,
        CollectionProcessorInterface $collectionProcessor,
        JoinProcessorInterface $extensionAttributesJoinProcessor,
        ExtensibleDataObjectConverter $extensibleDataObjectConverter
    ) {
        $this->resource = $resource;
        $this->productsFactory = $productsFactory;
        $this->productsCollectionFactory = $productsCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataProductsFactory = $dataProductsFactory;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->storeManager = $storeManager;
        $this->collectionProcessor = $collectionProcessor;
        $this->extensionAttributesJoinProcessor = $extensionAttributesJoinProcessor;
        $this->extensibleDataObjectConverter = $extensibleDataObjectConverter;
    }

    /**
     * {@inheritdoc}
     */
    public function save(
        \Shadab\Customproduct\Api\Data\ProductsInterface $products
    ) {
        /* if (empty($products->getStoreId())) {
            $storeId = $this->storeManager->getStore()->getId();
            $products->setStoreId($storeId);
        } */
        
        $productsData = $this->extensibleDataObjectConverter->toNestedArray(
            $products,
            [],
            \Shadab\Customproduct\Api\Data\ProductsInterface::class
        );
        
        $productsModel = $this->productsFactory->create()->setData($productsData);
        
        try {
            $this->resource->save($productsModel);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the products: %1',
                $exception->getMessage()
            ));
        }
        return $productsModel->getDataModel();
    }

    /**
     * {@inheritdoc}
     */
    public function get($entityId)
    {
        $products = $this->productsFactory->create();
        $this->resource->load($products, $entityId);
        if (!$products->getId()) {
            throw new NoSuchEntityException(__('product with id "%1" does not exist.', $entityId));
        }
        return $products->getDataModel();
    }

    /**
     * {@inheritdoc}
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $collection = $this->productsCollectionFactory->create();
        
        $this->extensionAttributesJoinProcessor->process(
            $collection,
            \Shadab\Customproduct\Api\Data\ProductsInterface::class
        );
        
        $this->collectionProcessor->process($criteria, $collection);
        
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);
        
        $items = [];
        foreach ($collection as $model) {
            $items[] = $model->getDataModel();
        }
        
        $searchResults->setItems($items);
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(
        \Shadab\Customproduct\Api\Data\ProductsInterface $products
    ) {
        try {
            $productsModel = $this->productsFactory->create();
            $this->resource->load($productsModel, $products->getEntityId());
            $this->resource->delete($productsModel);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the products: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById($entityId)
    {
        return $this->delete($this->get($entityId));
    }
}

