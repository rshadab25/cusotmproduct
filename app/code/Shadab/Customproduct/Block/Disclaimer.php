<?php
/**
 * Copyright © Shadab All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Shadab\Customproduct\Block;

class Disclaimer extends \Magento\Framework\View\Element\Template
{

    /**
     * Constructor
     *
     * @param \Magento\Framework\View\Element\Template\Context  $context
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param array $data
     */

    /**
     * Disclaimer config path
     */
    const XML_PATH_DISCLAIMER_CONFIG = 'settings/general/disclaimer';

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        array $data = []
    ) {
        $this->scopeConfig = $scopeConfig;
        parent::__construct($context, $data);
    }
    /**
     * @param string $value
     * @return string
     */
    public function getConfigValue($value = '')
    {
        return $this->scopeConfig->getValue($value, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }
    /**
     * @return string
     */
    public function getDisclaimer()
    {
        $configValue = $this->getConfigValue(self::XML_PATH_DISCLAIMER_CONFIG);
        return __($configValue);
    }
}
