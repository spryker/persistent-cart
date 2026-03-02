<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PersistentCart\QuoteWriter;

use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\PersistentCart\Dependency\Client\PersistentCartToCustomerClientInterface;
use Spryker\Client\PersistentCart\Dependency\Client\PersistentCartToQuoteClientInterface;
use Spryker\Client\PersistentCart\Dependency\Client\PersistentCartToZedRequestClientInterface;
use Spryker\Client\PersistentCart\QuoteUpdatePluginExecutor\QuoteUpdatePluginExecutorInterface;
use Spryker\Client\PersistentCart\Zed\PersistentCartStubInterface;

class QuoteDeleter implements QuoteDeleterInterface
{
    /**
     * @var \Spryker\Client\PersistentCart\Dependency\Client\PersistentCartToQuoteClientInterface
     */
    protected $quoteClient;

    /**
     * @var \Spryker\Client\PersistentCart\Zed\PersistentCartStubInterface
     */
    protected $persistentCartStub;

    /**
     * @var \Spryker\Client\PersistentCart\QuoteUpdatePluginExecutor\QuoteUpdatePluginExecutorInterface
     */
    protected $quoteUpdatePluginExecutor;

    /**
     * @var \Spryker\Client\PersistentCart\Dependency\Client\PersistentCartToZedRequestClientInterface
     */
    protected $zedRequestClient;

    /**
     * @var \Spryker\Client\PersistentCart\Dependency\Client\PersistentCartToCustomerClientInterface
     */
    protected $customerClient;

    public function __construct(
        PersistentCartToQuoteClientInterface $quoteClient,
        PersistentCartToZedRequestClientInterface $zedRequestClient,
        PersistentCartToCustomerClientInterface $customerClient,
        PersistentCartStubInterface $persistentCartStub,
        QuoteUpdatePluginExecutorInterface $quoteUpdatePluginExecutor
    ) {
        $this->quoteClient = $quoteClient;
        $this->persistentCartStub = $persistentCartStub;
        $this->quoteUpdatePluginExecutor = $quoteUpdatePluginExecutor;
        $this->zedRequestClient = $zedRequestClient;
        $this->customerClient = $customerClient;
    }

    public function deleteQuote(QuoteTransfer $quoteTransfer): QuoteResponseTransfer
    {
        $quoteTransfer->setCustomer($this->customerClient->getCustomer());
        $quoteResponseTransfer = $this->persistentCartStub->deleteQuote($quoteTransfer);

        $quoteResponseTransfer = $this->executeUpdateQuotePlugins($quoteResponseTransfer);
        if ($quoteResponseTransfer->getIsSuccessful() && $this->quoteClient->getQuote()->getIdQuote() === $quoteTransfer->getIdQuote()) {
            $this->quoteClient->setQuote(new QuoteTransfer());
        }
        $this->zedRequestClient->addResponseMessagesToMessenger();

        return $quoteResponseTransfer;
    }

    protected function executeUpdateQuotePlugins(QuoteResponseTransfer $quoteResponseTransfer): QuoteResponseTransfer
    {
        return $this->quoteUpdatePluginExecutor->executePlugins($quoteResponseTransfer);
    }
}
