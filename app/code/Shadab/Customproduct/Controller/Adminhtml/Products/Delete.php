<?php
/**
 * Copyright © Shadab All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Shadab\Customproduct\Controller\Adminhtml\Products;

use Magento\Framework\App\Action\HttpGetActionInterface;

class Delete extends \Shadab\Customproduct\Controller\Adminhtml\Products implements HttpGetActionInterface
{

    const ADMIN_RESOURCE = 'Shadab_Customproduct::delete';
    /**
     * Constructor
     *
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Shadab\Customproduct\Model\ProductsFactory $prodFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Shadab\Customproduct\Model\ProductsFactory $prodFactory,
        \Magento\Framework\App\ResourceConnection $resource,
        \Magento\Framework\Registry $coreRegistry
    ) {
        $this->prodFactory = $prodFactory;
        $this->_resource = $resource;
        parent::__construct($context, $coreRegistry);
    }

    /**
     * Delete action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        // check if we know what should be deleted
        $id = $this->getRequest()->getParam('entity_id');
        if ($id) {
            try {
                // init model and delete
                $model = $this->prodFactory->create();
                $model->load($id);
                $model->delete();
                $connection = $this->_resource->getConnection();
                $tableName =  $this->_resource->getTableName('customproducts_store');
                $whereConditions = [
                    $connection->quoteInto('product_id = ?', $id),
                ];
                $connection->delete($tableName, $whereConditions);
                // display success message
                $this->messageManager->addSuccessMessage(__('You deleted the Products.'));
                // go to grid
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                // display error message
                $this->messageManager->addErrorMessage($e->getMessage());
                // go back to edit form
                return $resultRedirect->setPath('*/*/edit', ['entity_id' => $id]);
            }
        }
        // display error message
        $this->messageManager->addErrorMessage(__('We can\'t find a Products to delete.'));
        // go to grid
        return $resultRedirect->setPath('*/*/');
    }
}
