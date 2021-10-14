<?php
/**
 * Copyright © Shadab All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Shadab\Customproduct\Controller\Adminhtml\Products;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\Action\HttpPostActionInterface;

class Save extends \Magento\Backend\App\Action implements HttpGetActionInterface, HttpPostActionInterface
{

    const ADMIN_RESOURCE = 'Shadab_Customproduct::save';
    protected $dataPersistor;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor,
        \Magento\Framework\App\ResourceConnection $resource,
        \Shadab\Customproduct\Model\ProductsFactory $prodFactory
    ) {
        $this->dataPersistor = $dataPersistor;
        $this->_resource = $resource;
        $this->prodFactory = $prodFactory;
        parent::__construct($context);
    }

    /**
     * Save action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $storeId = $this->getRequest()->getParam('store', 0);
        $data = $this->getRequest()->getPostValue();
        if ($data) {
            $id = $this->getRequest()->getParam('entity_id');
            if (isset($id) && $id<=0) {
                $id = $this->getRequest()->getParam('product_id');
            } elseif (!isset($id)) {
                $id = $this->getRequest()->getParam('product_id');
            }
        
            $model = $this->prodFactory->create()->load($id);
            if (!$model->getId() && $id) {
                $this->messageManager->addErrorMessage(__('This Product no longer exists.'));
                return $resultRedirect->setPath('*/*/');
            } else {
                unset($data['store_id']);
                $model->setData($data);
                try {
                    if ($storeId>0 && $id > 0) {
                        $connection = $this->_resource->getConnection();
                        $tableName =  $this->_resource->getTableName('customproducts_store');
                        $select = "select * from $tableName where product_id=$id and store_id=$storeId";
                        $queryResult = $connection->fetchRow($select);
                        if (isset($queryResult) && is_array($queryResult) && count($queryResult)>0) {
                            $sku=$data['sku'];
                            $vendor_number=$data['vendor_number'];
                            $vendor_note=$data['vendor_note'];
                            $updateQuery="UPDATE `customproducts_store` SET `sku` = '$sku', 
                                `vendor_number` = '$vendor_number', 
                                `vendor_note` = '$vendor_note' 
                                WHERE `customproducts_store`.`product_id` = $id 
                                AND `customproducts_store`.`store_id` = $storeId";
                            $connection->query($updateQuery);
                        } else {
                            $insertData=[
                            'sku'=>$data['sku'],
                            'vendor_number'=>$data['vendor_number'],
                            'vendor_note'=>$data['vendor_note'],
                            'store_id'=>$storeId,
                            'product_id'=>$id
                            ];
                            $connection->insert($tableName, $insertData);
                        }
                    } else {
                        $model->save();
                        $id =$model->getId();
                    }
                
                    $this->messageManager->addSuccessMessage(__('You saved the Products.'));
                    $this->dataPersistor->clear('customproducts');
        
                    if ($this->getRequest()->getParam('back')) {
                        return $resultRedirect->setPath('*/*/edit', ['entity_id' => $id,'store'=>$storeId]);
                    }
                    return $resultRedirect->setPath('*/*/');
                } catch (LocalizedException $e) {
                    $this->messageManager->addErrorMessage($e->getMessage());
                } catch (\Exception $e) {
                    $errMsg = "Something went wrong while saving the Products.";
                    $this->messageManager->addExceptionMessage($e, __($errMsg));
                }
            }
        
            $this->dataPersistor->set('customproducts', $data);
            return $resultRedirect->setPath(
                '*/*/edit',
                ['entity_id' => $this->getRequest()->getParam('entity_id'),
                    'store' => $this->getRequest()->getParam('store')]
            );
        }
        return $resultRedirect->setPath('*/*/');
    }
}
