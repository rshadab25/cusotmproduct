<?php
/**
 * Copyright © Shadab All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Shadab\Customproduct\Model\Products;

use Magento\Framework\App\Request\DataPersistorInterface;
use Shadab\Customproduct\Model\ResourceModel\Products\CollectionFactory;

class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{

    protected $loadedData;
    protected $dataPersistor;

    protected $collection;

    /**
     * Constructor
     *
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $collectionFactory
     * @param DataPersistorInterface $dataPersistor
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        DataPersistorInterface $dataPersistor,
        \Magento\Framework\App\Request\Http $request,
        \Magento\Framework\App\ResourceConnection $resource,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $collectionFactory->create();
        $this->request = $request;
        $this->_resource = $resource;
        $this->dataPersistor = $dataPersistor;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    /**
     * Get data
     *
     * @return array
     */
    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }
        $storeId = $this->request->getParam('store');
        $items = $this->collection->getItems();
        foreach ($items as $model) {
            if ($storeId > 0) {
                
                $prodcutId = $model->getId();
                $connection = $this->_resource->getConnection();
                $newcollection = $this->collection->getSelect()
                ->joinRight(
                    ['customproducts_store'=> 'customproducts_store'],
                    'customproducts_store.product_id = main_table.entity_id',
                    ['customproducts_store.*']
                )
                ->reset(\Zend_Db_Select::COLUMNS)
                ->columns('customproducts_store.*')
                ->where("customproducts_store.store_id=$storeId and product_id=$prodcutId");
                $queryResult = $connection->fetchRow($newcollection);
                if (isset($queryResult) && is_array($queryResult) && count($queryResult)>0) {
                    $this->loadedData[$model->getId()] = $queryResult;
                } else {
                    $this->loadedData[$model->getId()] = $model->getData();
                }
            } else {
                $this->loadedData[$model->getId()] = $model->getData();
            }
        }
        $data = $this->dataPersistor->get('customproducts');
        
        if (!empty($data)) {
            $model = $this->collection->getNewEmptyItem();
            $model->setData($data);
            $this->loadedData[$model->getId()] = $model->getData();
            $this->dataPersistor->clear('customproducts');
        }
        
        return $this->loadedData;
    }
}
