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

interface PersistentCartToQuoteFacadeInterface
{
    public function isQuoteLocked(QuoteTransfer $quoteTransfer): bool;

    public function createQuote(QuoteTransfer $quoteTransfer): QuoteResponseTransfer;

    public function updateQuote(QuoteTransfer $quoteTransfer): QuoteResponseTransfer;

    /**
     * @deprecated Use {@link findQuoteByCustomerAndStore()} instead.
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function findQuoteByCustomer(CustomerTransfer $customerTransfer): QuoteResponseTransfer;

    public function findQuoteByCustomerAndStore(CustomerTransfer $customerTransfer, StoreTransfer $storeTransfer): QuoteResponseTransfer;

    /**
     * @param int $idQuote
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function findQuoteById($idQuote): QuoteResponseTransfer;

    public function getStorageStrategy(): string;

    public function deleteQuote(QuoteTransfer $quoteTransfer): QuoteResponseTransfer;

    public function getQuoteCollection(QuoteCriteriaFilterTransfer $quoteCriteriaFilterTransfer): QuoteCollectionTransfer;
}
