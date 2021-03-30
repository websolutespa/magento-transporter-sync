<?php
/*
 * Copyright © Websolute spa. All rights reserved.
 * See LICENSE and/or COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Websolute\TransporterSync\Block\Adminhtml\Product\Edit\Button;

use Magento\Backend\Block\Widget\Context;
use Magento\Catalog\Model\Session;
use Magento\Customer\Block\Adminhtml\Edit\GenericButton;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;
use Magento\Ui\Component\Control\Container;

class Sync extends GenericButton implements ButtonProviderInterface
{
    /**
     * @var Session
     */
    private $catalogSession;

    /**
     * @var string
     */
    private $identifier;

    /**
     * @var string
     */
    private $targetName;

    /**
     * @param Context $context
     * @param Registry $registry
     * @param Session $catalogSession
     * @param string $targetName
     * @param string $identifier
     */
    public function __construct(
        Context $context,
        Registry $registry,
        Session $catalogSession,
        string $targetName,
        string $identifier = 'sku'
    ) {
        $this->catalogSession = $catalogSession;
        $this->identifier = $identifier;
        $this->targetName = $targetName;
        parent::__construct($context, $registry);
    }

    /**
     * @inheritDoc
     */
    public function getButtonData()
    {
        return [
            'id' => 'sync_product',
            'label' => __('Sync'),
            'class' => 'primary',
            'class_name' => Container::SPLIT_BUTTON,
            'options' => $this->getOptions(),
        ];
    }

    /**
     * @return array
     */
    protected function getOptions(): array
    {
        return [
            'action_1' => [
                'label' => __('Stock'),
                'onclick' => 'setLocation(\'' . $this->getStockSyncUrl() . '\')'
            ],
            'action_2' => [
                'label' => __('Price'),
                'onclick' => 'setLocation(\'' . $this->getPriceSyncUrl() . '\')'
            ],
            'action_3' => [
                'label' => __('Special Price'),
                'onclick' => 'setLocation(\'' . $this->getPriceSyncUrl() . '\')'
            ]
        ];
    }

    /**
     * @return string
     */
    private function getStockSyncUrl(): string
    {
        $currentProduct = $this->getCurrentProduct();
        return $this->getUrl(
            'transporter_sync/sync/stock',
            [
                'product_sku' => $currentProduct->getData($this->identifier)
            ]
        );
    }

    /**
     * @return mixed
     */
    private function getCurrentProduct()
    {
        return $this->registry->registry('current_product');
    }

    /**
     * @return string
     */
    private function getPriceSyncUrl(): string
    {
        $currentProduct = $this->getCurrentProduct();
        return $this->getUrl(
            'transporter_sync/sync/price',
            [
                'product_sku' => $currentProduct->getData($this->identifier)
            ]
        );
    }
}
