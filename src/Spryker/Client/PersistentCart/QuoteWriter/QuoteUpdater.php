<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PersistentCart\QuoteWriter;

use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteUpdateRequestTransfer;
use Spryker\Client\PersistentCart\Dependency\Client\PersistentCartToQuoteClientInterface;
use Spryker\Client\PersistentCart\QuoteUpdatePluginExecutor\QuoteUpdatePluginExecutorInterface;
use Spryker\Client\PersistentCart\Zed\PersistentCartStubInterface;

class QuoteUpdater implements QuoteUpdaterInterface
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

    public function __construct(
        PersistentCartToQuoteClientInterface $quoteClient,
        PersistentCartStubInterface $persistentCartStub,
        QuoteUpdatePluginExecutorInterface $quoteUpdatePluginExecutor
    ) {
        $this->quoteClient = $quoteClient;
        $this->persistentCartStub = $persistentCartStub;
        $this->quoteUpdatePluginExecutor = $quoteUpdatePluginExecutor;
    }

    public function updateQuote(QuoteUpdateRequestTransfer $quoteUpdateRequestTransfer): QuoteResponseTransfer
    {
        $quoteResponseTransfer = $this->persistentCartStub->updateQuote($quoteUpdateRequestTransfer);
        if ($quoteResponseTransfer->getIsSuccessful()) {
            $this->quoteClient->setQuote($quoteResponseTransfer->getQuoteTransfer());
        }
        $quoteResponseTransfer = $this->executeUpdateQuotePlugins($quoteResponseTransfer);

        return $quoteResponseTransfer;
    }

    protected function executeUpdateQuotePlugins(QuoteResponseTransfer $quoteResponseTransfer): QuoteResponseTransfer
    {
        return $this->quoteUpdatePluginExecutor->executePlugins($quoteResponseTransfer);
    }
}
