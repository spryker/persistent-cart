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
use Spryker\Client\PersistentCart\Dependency\Client\PersistentCartToZedRequestClientInterface;

class PersistentCartStub implements PersistentCartStubInterface
{
    /**
     * @var \Spryker\Client\PersistentCart\Dependency\Client\PersistentCartToZedRequestClientInterface
     */
    protected $zedRequestClient;

    public function __construct(PersistentCartToZedRequestClientInterface $zedRequestClient)
    {
        $this->zedRequestClient = $zedRequestClient;
    }

    public function addItem(PersistentCartChangeTransfer $persistentCartChangeTransfer): QuoteResponseTransfer
    {
        /** @var \Generated\Shared\Transfer\QuoteResponseTransfer $quoteResponseTransfer */
        $quoteResponseTransfer = $this->zedRequestClient->call('/persistent-cart/gateway/add-item', $persistentCartChangeTransfer);

        return $quoteResponseTransfer;
    }

    public function addValidItems(PersistentCartChangeTransfer $persistentCartChangeTransfer): QuoteResponseTransfer
    {
        /** @var \Generated\Shared\Transfer\QuoteResponseTransfer $quoteResponseTransfer */
        $quoteResponseTransfer = $this->zedRequestClient->call('/persistent-cart/gateway/add-valid-items', $persistentCartChangeTransfer);

        return $quoteResponseTransfer;
    }

    public function removeItem(PersistentCartChangeTransfer $persistentCartChangeTransfer): QuoteResponseTransfer
    {
        /** @var \Generated\Shared\Transfer\QuoteResponseTransfer $quoteResponseTransfer */
        $quoteResponseTransfer = $this->zedRequestClient->call('/persistent-cart/gateway/remove-item', $persistentCartChangeTransfer);

        return $quoteResponseTransfer;
    }

    public function reloadItems(QuoteTransfer $quoteTransfer): QuoteResponseTransfer
    {
        /** @var \Generated\Shared\Transfer\QuoteResponseTransfer $quoteResponseTransfer */
        $quoteResponseTransfer = $this->zedRequestClient->call('/persistent-cart/gateway/reload-items', $quoteTransfer);

        return $quoteResponseTransfer;
    }

    public function changeItemQuantity(PersistentCartChangeQuantityTransfer $persistentCartChangeQuantityTransfer): QuoteResponseTransfer
    {
        /** @var \Generated\Shared\Transfer\QuoteResponseTransfer $quoteResponseTransfer */
        $quoteResponseTransfer = $this->zedRequestClient->call('/persistent-cart/gateway/change-item-quantity', $persistentCartChangeQuantityTransfer);

        return $quoteResponseTransfer;
    }

    /**
     * @uses \Spryker\Zed\PersistentCart\Communication\Controller\GatewayController::updateQuantityAction()
     *
     * @param \Generated\Shared\Transfer\PersistentCartChangeTransfer $persistentCartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function updateQuantity(PersistentCartChangeTransfer $persistentCartChangeTransfer): QuoteResponseTransfer
    {
        /** @var \Generated\Shared\Transfer\QuoteResponseTransfer $quoteResponseTransfer */
        $quoteResponseTransfer = $this->zedRequestClient->call(
            '/persistent-cart/gateway/update-quantity',
            $persistentCartChangeTransfer,
        );

        return $quoteResponseTransfer;
    }

    public function decreaseItemQuantity(PersistentCartChangeQuantityTransfer $persistentCartChangeQuantityTransfer): QuoteResponseTransfer
    {
        /** @var \Generated\Shared\Transfer\QuoteResponseTransfer $quoteResponseTransfer */
        $quoteResponseTransfer = $this->zedRequestClient->call('/persistent-cart/gateway/decrease-item-quantity', $persistentCartChangeQuantityTransfer);

        return $quoteResponseTransfer;
    }

    public function increaseItemQuantity(PersistentCartChangeQuantityTransfer $persistentCartChangeQuantityTransfer): QuoteResponseTransfer
    {
        /** @var \Generated\Shared\Transfer\QuoteResponseTransfer $quoteResponseTransfer */
        $quoteResponseTransfer = $this->zedRequestClient->call('/persistent-cart/gateway/increase-item-quantity', $persistentCartChangeQuantityTransfer);

        return $quoteResponseTransfer;
    }

    public function syncStorageQuote(QuoteSyncRequestTransfer $quoteSyncRequestTransfer): QuoteResponseTransfer
    {
        /** @var \Generated\Shared\Transfer\QuoteResponseTransfer $quoteResponseTransfer */
        $quoteResponseTransfer = $this->zedRequestClient->call('/persistent-cart/gateway/sync-storage-quote', $quoteSyncRequestTransfer);

        return $quoteResponseTransfer;
    }

    public function validateQuote(QuoteTransfer $quoteTransfer): QuoteResponseTransfer
    {
        /** @var \Generated\Shared\Transfer\QuoteResponseTransfer $quoteResponseTransfer */
        $quoteResponseTransfer = $this->zedRequestClient->call('/persistent-cart/gateway/validate-quote', $quoteTransfer);

        return $quoteResponseTransfer;
    }

    public function deleteQuote(QuoteTransfer $quoteTransfer): QuoteResponseTransfer
    {
        /** @var \Generated\Shared\Transfer\QuoteResponseTransfer $quoteResponseTransfer */
        $quoteResponseTransfer = $this->zedRequestClient->call('/persistent-cart/gateway/delete-quote', $quoteTransfer);

        return $quoteResponseTransfer;
    }

    public function persistQuote(QuoteTransfer $quoteTransfer): QuoteResponseTransfer
    {
        /** @var \Generated\Shared\Transfer\QuoteResponseTransfer $quoteResponseTransfer */
        $quoteResponseTransfer = $this->zedRequestClient->call('/persistent-cart/gateway/persist-quote', $quoteTransfer);

        return $quoteResponseTransfer;
    }

    public function createQuote(QuoteTransfer $quoteTransfer): QuoteResponseTransfer
    {
        /** @var \Generated\Shared\Transfer\QuoteResponseTransfer $quoteResponseTransfer */
        $quoteResponseTransfer = $this->zedRequestClient->call('/persistent-cart/gateway/create-quote', $quoteTransfer);

        return $quoteResponseTransfer;
    }

    public function createQuoteWithReloadedItems(QuoteTransfer $quoteTransfer): QuoteResponseTransfer
    {
        /** @var \Generated\Shared\Transfer\QuoteResponseTransfer $quoteResponseTransfer */
        $quoteResponseTransfer = $this->zedRequestClient->call('/persistent-cart/gateway/create-quote-with-reloaded-items', $quoteTransfer);

        return $quoteResponseTransfer;
    }

    public function updateQuote(QuoteUpdateRequestTransfer $quoteUpdateRequestTransfer): QuoteResponseTransfer
    {
        /** @var \Generated\Shared\Transfer\QuoteResponseTransfer $quoteResponseTransfer */
        $quoteResponseTransfer = $this->zedRequestClient->call('/persistent-cart/gateway/update-quote', $quoteUpdateRequestTransfer);

        return $quoteResponseTransfer;
    }

    public function replaceQuote(QuoteTransfer $quoteTransfer): QuoteResponseTransfer
    {
        /** @var \Generated\Shared\Transfer\QuoteResponseTransfer $quoteResponseTransfer */
        $quoteResponseTransfer = $this->zedRequestClient->call('/persistent-cart/gateway/replace-quote', $quoteTransfer);

        return $quoteResponseTransfer;
    }

    public function updateAndReloadQuote(QuoteUpdateRequestTransfer $quoteUpdateRequestTransfer): QuoteResponseTransfer
    {
        /** @var \Generated\Shared\Transfer\QuoteResponseTransfer $quoteResponseTransfer */
        $quoteResponseTransfer = $this->zedRequestClient->call('/persistent-cart/gateway/update-and-reload-quote', $quoteUpdateRequestTransfer);

        return $quoteResponseTransfer;
    }

    public function replaceQuoteByCustomerAndStore(QuoteTransfer $quoteTransfer): QuoteResponseTransfer
    {
        /** @var \Generated\Shared\Transfer\QuoteResponseTransfer $quoteResponseTransfer */
        $quoteResponseTransfer = $this->zedRequestClient->call('/persistent-cart/gateway/replace-quote-by-customer-and-store', $quoteTransfer);

        return $quoteResponseTransfer;
    }

    public function resetQuoteLock(QuoteTransfer $quoteTransfer): QuoteResponseTransfer
    {
        /** @var \Generated\Shared\Transfer\QuoteResponseTransfer $quoteResponseTransfer */
        $quoteResponseTransfer = $this->zedRequestClient->call('/persistent-cart/gateway/reset-quote-lock', $quoteTransfer);

        return $quoteResponseTransfer;
    }

    /**
     * @uses \Spryker\Zed\PersistentCart\Communication\Controller\GatewayController::replaceItemAction()
     *
     * @param \Generated\Shared\Transfer\PersistentItemReplaceTransfer $persistentItemReplaceTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function replaceItem(PersistentItemReplaceTransfer $persistentItemReplaceTransfer): QuoteResponseTransfer
    {
        /** @var \Generated\Shared\Transfer\QuoteResponseTransfer $quoteResponseTransfer */
        $quoteResponseTransfer = $this->zedRequestClient->call('/persistent-cart/gateway/replace-item', $persistentItemReplaceTransfer);

        return $quoteResponseTransfer;
    }
}
