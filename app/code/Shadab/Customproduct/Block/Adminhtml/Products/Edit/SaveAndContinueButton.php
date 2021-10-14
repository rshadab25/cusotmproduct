<?php
/**
 * Copyright © Shadab All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Shadab\Customproduct\Block\Adminhtml\Products\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class SaveAndContinueButton extends GenericButton implements ButtonProviderInterface
{

    /**
     * @return array
     */
    public function getButtonData()
    {
        if (!$this->isAllowed()) {
            return [];
        }
        if (!$this->context->getRequest()->getParam('store')) {
            $store_id = 0;
        } else {
            $store_id = $this->context->getRequest()->getParam('store');
        }
        return [
            'label' => __('Save and Continue Edit'),
            'class' => 'save',
            'data_attribute' => [
                'mage-init' => [
                    'buttonAdapter' => [
                        'actions' => [
                            [
                                'targetName' => 'shadab_customproduct_products_form.shadab_customproduct_products_form',
                                'actionName' => 'save',
                                'params' => [
                                    true,
                                    ['store'=>$store_id]
                                ]
                            ]
                        ]
                    ]
                ]
            ],
            'sort_order' => 80,
        ];
    }
    public function isAllowed()
    {
        return $this->authorization->isAllowed('Shadab_Customproduct::delete');
    }
}
