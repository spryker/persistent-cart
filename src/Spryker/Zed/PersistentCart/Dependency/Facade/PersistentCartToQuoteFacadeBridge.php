<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PersistentCart\Dependency\Facade;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteCollectionTransfer;
use Generated\Shared\Transfer\QuoteCriteriaFilterTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\StoreTransfer;

class PersistentCartToQuoteFacadeBridge implements PersistentCartToQuoteFacadeInterface
{
    /**
     * @var \Spryker\Zed\Quote\Business\QuoteFacadeInterface
     */
    protected $quoteFacade;

    /**
     * @param \Spryker\Zed\Quote\Business\QuoteFacadeInterface $quoteFacade
     */
    public function __construct($quoteFacade)
    {
        $this->quoteFacade = $quoteFacade;
    }

    public function isQuoteLocked(QuoteTransfer $quoteTransfer): bool
    {
        return $this->quoteFacade->isQuoteLocked($quoteTransfer);
    }

    public function createQuote(QuoteTransfer $quoteTransfer): QuoteResponseTransfer
    {
        return $this->quoteFacade->createQuote($quoteTransfer);
    }

    public function updateQuote(QuoteTransfer $quoteTransfer): QuoteResponseTransfer
    {
        return $this->quoteFacade->updateQuote($quoteTransfer);
    }

    /**
     * @deprecated Use {@link findQuoteByCustomerAndStore()} instead.
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function findQuoteByCustomer(CustomerTransfer $customerTransfer): QuoteResponseTransfer
    {
        return $this->quoteFacade->findQuoteByCustomer($customerTransfer);
    }

    public function findQuoteByCustomerAndStore(CustomerTransfer $customerTransfer, StoreTransfer $storeTransfer): QuoteResponseTransfer
    {
        return $this->quoteFacade->findQuoteByCustomerAndStore($customerTransfer, $storeTransfer);
    }

    /**
     * @param int $idQuote
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function findQuoteById($idQuote): QuoteResponseTransfer
    {
        return $this->quoteFacade->findQuoteById($idQuote);
    }

    public function getStorageStrategy(): string
    {
        return $this->quoteFacade->getStorageStrategy();
    }

    public function deleteQuote(QuoteTransfer $quoteTransfer): QuoteResponseTransfer
    {
        return $this->quoteFacade->deleteQuote($quoteTransfer);
    }

    public function getQuoteCollection(QuoteCriteriaFilterTransfer $quoteCriteriaFilterTransfer): QuoteCollectionTransfer
    {
        return $this->quoteFacade->getQuoteCollection($quoteCriteriaFilterTransfer);
    }
}
