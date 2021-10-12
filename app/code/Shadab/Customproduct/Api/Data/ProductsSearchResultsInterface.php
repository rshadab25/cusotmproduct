<?php
/**
 * Copyright © Shadab All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Shadab\Customproduct\Api\Data;

interface ProductsSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{

    /**
     * Get products list.
     * @return \Shadab\Customproduct\Api\Data\ProductsInterface[]
     */
    public function getItems();

    /**
     * Set sku list.
     * @param \Shadab\Customproduct\Api\Data\ProductsInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}

