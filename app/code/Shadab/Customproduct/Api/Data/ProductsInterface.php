<?php
/**
 * Copyright © Shadab All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Shadab\Customproduct\Api\Data;

interface ProductsInterface extends \Magento\Framework\Api\ExtensibleDataInterface
{

    const UPDATED_AT = 'updated_at';
    const PRODUCTS_ID = 'entity_id';
    const VENDOR_NOTE = 'vendor_note';
    const CREATED_AT = 'created_at';
    const SKU = 'sku';
    const VENDOR_NUMBER = 'vendor_number';

    /**
     * Get entity_id
     * @return string|null
     */
    public function getEntityId();

    /**
     * Set entity_id
     * @param string $entityId
     * @return \Shadab\Customproduct\Api\Data\ProductsInterface
     */
    public function setEntityId($entityId);

    /**
     * Get sku
     * @return string|null
     */
    public function getSku();

    /**
     * Set sku
     * @param string $sku
     * @return \Shadab\Customproduct\Api\Data\ProductsInterface
     */
    public function setSku($sku);

    /**
     * Retrieve existing extension attributes object or create a new one.
     * @return \Shadab\Customproduct\Api\Data\ProductsExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * Set an extension attributes object.
     * @param \Shadab\Customproduct\Api\Data\ProductsExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \Shadab\Customproduct\Api\Data\ProductsExtensionInterface $extensionAttributes
    );

    /**
     * Get vendor_number
     * @return string|null
     */
    public function getVendorNumber();

    /**
     * Set vendor_number
     * @param string $vendorNumber
     * @return \Shadab\Customproduct\Api\Data\ProductsInterface
     */
    public function setVendorNumber($vendorNumber);

    /**
     * Get vendor_note
     * @return string|null
     */
    public function getVendorNote();

    /**
     * Set vendor_note
     * @param string $vendorNote
     * @return \Shadab\Customproduct\Api\Data\ProductsInterface
     */
    public function setVendorNote($vendorNote);

    /**
     * Get created_at
     * @return string|null
     */
    public function getCreatedAt();

    /**
     * Set created_at
     * @param string $createdAt
     * @return \Shadab\Customproduct\Api\Data\ProductsInterface
     */
    public function setCreatedAt($createdAt);

    /**
     * Get updated_at
     * @return string|null
     */
    public function getUpdatedAt();

    /**
     * Set updated_at
     * @param string $updatedAt
     * @return \Shadab\Customproduct\Api\Data\ProductsInterface
     */
    public function setUpdatedAt($updatedAt);
}

