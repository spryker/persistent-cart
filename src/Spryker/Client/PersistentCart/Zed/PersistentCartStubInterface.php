<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PersistentCart\Zed;

use Generated\Shared\Transfer\PersistentCartChangeQuantityTransfer;
use Generated\Shared\Transfer\PersistentCartChangeTransfer;
use Generated\Shared\Transfer\PersistentItemReplaceTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteSyncRequestTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\QuoteUpdateRequestTransfer;

interface PersistentCartStubInterface
{
    public function addItem(PersistentCartChangeTransfer $persistentCartChangeTransfer): QuoteResponseTransfer;

    public function addValidItems(PersistentCartChangeTransfer $persistentCartChangeTransfer): QuoteResponseTransfer;

    public function removeItem(PersistentCartChangeTransfer $persistentCartChangeTransfer): QuoteResponseTransfer;

    public function reloadItems(QuoteTransfer $quoteTransfer): QuoteResponseTransfer;

    public function changeItemQuantity(PersistentCartChangeQuantityTransfer $persistentCartChangeQuantityTransfer): QuoteResponseTransfer;

    public function updateQuantity(PersistentCartChangeTransfer $persistentCartChangeTransfer): QuoteResponseTransfer;

    public function decreaseItemQuantity(PersistentCartChangeQuantityTransfer $persistentCartChangeQuantityTransfer): QuoteResponseTransfer;

    public function increaseItemQuantity(PersistentCartChangeQuantityTransfer $persistentCartChangeQuantityTransfer): QuoteResponseTransfer;

    public function syncStorageQuote(QuoteSyncRequestTransfer $quoteSyncRequestTransfer): QuoteResponseTransfer;

    public function validateQuote(QuoteTransfer $quoteTransfer): QuoteResponseTransfer;

    public function deleteQuote(QuoteTransfer $quoteTransfer): QuoteResponseTransfer;

    public function persistQuote(QuoteTransfer $quoteTransfer): QuoteResponseTransfer;

    public function createQuote(QuoteTransfer $quoteTransfer): QuoteResponseTransfer;

    public function createQuoteWithReloadedItems(QuoteTransfer $quoteTransfer): QuoteResponseTransfer;

    public function updateQuote(QuoteUpdateRequestTransfer $quoteUpdateRequestTransfer): QuoteResponseTransfer;

    public function updateAndReloadQuote(QuoteUpdateRequestTransfer $quoteUpdateRequestTransfer): QuoteResponseTransfer;

    public function replaceQuoteByCustomerAndStore(QuoteTransfer $quoteTransfer): QuoteResponseTransfer;

    public function resetQuoteLock(QuoteTransfer $quoteTransfer): QuoteResponseTransfer;

    public function replaceItem(PersistentItemReplaceTransfer $persistentItemReplaceTransfer): QuoteResponseTransfer;
}
