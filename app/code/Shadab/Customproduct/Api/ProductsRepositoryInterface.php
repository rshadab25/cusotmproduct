<?php
/**
 * Copyright © Shadab All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Shadab\Customproduct\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface ProductsRepositoryInterface
{

    /**
     * Save products
     * @param \Shadab\Customproduct\Api\Data\ProductsInterface $products
     * @return \Shadab\Customproduct\Api\Data\ProductsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \Shadab\Customproduct\Api\Data\ProductsInterface $products
    );

    /**
     * Retrieve products
     * @param string $entityId
     * @return \Shadab\Customproduct\Api\Data\ProductsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function get($entityId);

    /**
     * Retrieve products matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Shadab\Customproduct\Api\Data\ProductsSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete products
     * @param \Shadab\Customproduct\Api\Data\ProductsInterface $products
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \Shadab\Customproduct\Api\Data\ProductsInterface $products
    );

    /**
     * Delete products by ID
     * @param string $entityId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($entityId);
}

