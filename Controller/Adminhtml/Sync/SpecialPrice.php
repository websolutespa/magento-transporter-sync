<?php
/*
 * Copyright © Websolute spa. All rights reserved.
 * See LICENSE and/or COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Websolute\TransporterSync\Controller\Adminhtml\Sync;

use Exception;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\Action\HttpGetActionInterface as HttpGetActionInterface;
use Magento\Framework\View\Result\PageFactory;
use Websolute\TransporterCommand\Model\ProcessAll;

class SpecialPrice extends Action implements HttpGetActionInterface
{
    /**
     * @var string
     */
    protected const TYPE = 'silicon_base_price_specific';

    /**
     * @var bool|PageFactory
     */
    protected $resultPageFactory = false;

    /**
     * @var ProcessAll
     */
    private $processAll;

    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param ProcessAll $processAll
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        ProcessAll $processAll
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->processAll = $processAll;
    }

    /**
     * @inheritDoc
     */
    public function execute(): Redirect
    {
        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();

        try {
            $sku = $this->getRequest()->getParam('product_sku');
            $this->processAll->execute(self::TYPE, $sku);
            $this->messageManager->addSuccessMessage(__('Special Price Sync Complete.'));
        } catch (Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        }

        $resultRedirect->setRefererOrBaseUrl();
        return $resultRedirect;
    }
}
