<?php
/**
 * Copyright © Shadab All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Shadab\Customproduct\Model\Data;

use Shadab\Customproduct\Api\Data\ProductsInterface;

class Products extends \Magento\Framework\Api\AbstractExtensibleObject implements ProductsInterface
{

    /**
     * Get entity_id
     * @return string|null
     */
    public function getEntityId()
    {
        return $this->_get(self::PRODUCTS_ID);
    }

    /**
     * Set entity_id
     * @param string $entityId
     * @return \Shadab\Customproduct\Api\Data\ProductsInterface
     */
    public function setEntityId($entityId)
    {
        return $this->setData(self::PRODUCTS_ID, $entityId);
    }

    /**
     * Get sku
     * @return string|null
     */
    public function getSku()
    {
        return $this->_get(self::SKU);
    }

    /**
     * Set sku
     * @param string $sku
     * @return \Shadab\Customproduct\Api\Data\ProductsInterface
     */
    public function setSku($sku)
    {
        return $this->setData(self::SKU, $sku);
    }

    /**
     * Retrieve existing extension attributes object or create a new one.
     * @return \Shadab\Customproduct\Api\Data\ProductsExtensionInterface|null
     */
    public function getExtensionAttributes()
    {
        return $this->_getExtensionAttributes();
    }

    /**
     * Set an extension attributes object.
     * @param \Shadab\Customproduct\Api\Data\ProductsExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \Shadab\Customproduct\Api\Data\ProductsExtensionInterface $extensionAttributes
    ) {
        return $this->_setExtensionAttributes($extensionAttributes);
    }

    /**
     * Get vendor_number
     * @return string|null
     */
    public function getVendorNumber()
    {
        return $this->_get(self::VENDOR_NUMBER);
    }

    /**
     * Set vendor_number
     * @param string $vendorNumber
     * @return \Shadab\Customproduct\Api\Data\ProductsInterface
     */
    public function setVendorNumber($vendorNumber)
    {
        return $this->setData(self::VENDOR_NUMBER, $vendorNumber);
    }

    /**
     * Get vendor_note
     * @return string|null
     */
    public function getVendorNote()
    {
        return $this->_get(self::VENDOR_NOTE);
    }

    /**
     * Set vendor_note
     * @param string $vendorNote
     * @return \Shadab\Customproduct\Api\Data\ProductsInterface
     */
    public function setVendorNote($vendorNote)
    {
        return $this->setData(self::VENDOR_NOTE, $vendorNote);
    }

    /**
     * Get created_at
     * @return string|null
     */
    public function getCreatedAt()
    {
        return $this->_get(self::CREATED_AT);
    }

    /**
     * Set created_at
     * @param string $createdAt
     * @return \Shadab\Customproduct\Api\Data\ProductsInterface
     */
    public function setCreatedAt($createdAt)
    {
        return $this->setData(self::CREATED_AT, $createdAt);
    }

    /**
     * Get updated_at
     * @return string|null
     */
    public function getUpdatedAt()
    {
        return $this->_get(self::UPDATED_AT);
    }

    /**
     * Set updated_at
     * @param string $updatedAt
     * @return \Shadab\Customproduct\Api\Data\ProductsInterface
     */
    public function setUpdatedAt($updatedAt)
    {
        return $this->setData(self::UPDATED_AT, $updatedAt);
    }
}
