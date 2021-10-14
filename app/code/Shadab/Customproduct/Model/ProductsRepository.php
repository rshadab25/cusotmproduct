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
        \Magento\Framework\App\ResourceConnection $_resource,
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
        $this->_resource = $_resource;
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
    public function  get($entityId, $storeId= null)
    {
        if($storeId>0){
            $products = $this->productsFactory->create()->getCollection();
            $newcollection = $products->getSelect()
                ->joinRight(
                    ['customproducts_store'=> 'customproducts_store'],
                    'customproducts_store.product_id = main_table.entity_id',
                    ['customproducts_store.*']
                )
                ->reset(\Zend_Db_Select::COLUMNS)
                ->columns('customproducts_store.*')
                ->where("customproducts_store.store_id=$storeId and product_id=$entityId");
                $connection = $this->_resource->getConnection();
                $queryResult = $connection->fetchRow($newcollection);
                if (isset($queryResult) && is_array($queryResult) && count($queryResult)>0) {
                    $arrayResult=[];
                    $arrayResult['entity_id']=$queryResult['product_id'];
                    $arrayResult['sku']=$queryResult['sku'];
                    $arrayResult['vendor_number']=$queryResult['vendor_number'];
                    $arrayResult['vendor_note']=$queryResult['vendor_note'];
                    $arrayResult['created_at']=$queryResult['created_at'];
                    $arrayResult['updated_at']=$queryResult['updated_at'];
                    $products = $this->productsFactory->create();
                    $products->setData($arrayResult);
                    return $products->getDataModel();
                } else {
                    $products = $this->productsFactory->create();
                    $this->resource->load($products, $entityId);
                    if (!$products->getId()) {
                        throw new NoSuchEntityException(__('product with id "%1" does not exist.', $entityId));
                    }
                    return $products->getDataModel();
                }  
        }else{
            $products = $this->productsFactory->create();
            $this->resource->load($products, $entityId);
            if (!$products->getId()) {
                throw new NoSuchEntityException(__('product with id "%1" does not exist.', $entityId));
            }
            return $products->getDataModel();
        }
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
