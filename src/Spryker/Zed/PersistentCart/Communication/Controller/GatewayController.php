<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PersistentCart\Communication\Controller;

use Generated\Shared\Transfer\PersistentCartChangeQuantityTransfer;
use Generated\Shared\Transfer\PersistentCartChangeTransfer;
use Generated\Shared\Transfer\PersistentItemReplaceTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteSyncRequestTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\QuoteUpdateRequestTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractGatewayController;

/**
 * @method \Spryker\Zed\PersistentCart\Business\PersistentCartFacadeInterface getFacade()
 * @method \Spryker\Zed\PersistentCart\Communication\PersistentCartCommunicationFactory getFactory()
 */
class GatewayController extends AbstractGatewayController
{
    public function addItemAction(PersistentCartChangeTransfer $persistentCartChangeTransfer): QuoteResponseTransfer
    {
        return $this->getFacade()->add($persistentCartChangeTransfer);
    }

    public function addValidItemsAction(PersistentCartChangeTransfer $persistentCartChangeTransfer): QuoteResponseTransfer
    {
        return $this->getFacade()->addValid($persistentCartChangeTransfer);
    }

    public function removeItemAction(PersistentCartChangeTransfer $persistentCartChangeTransfer): QuoteResponseTransfer
    {
        return $this->getFacade()->remove($persistentCartChangeTransfer);
    }

    public function reloadItemsAction(QuoteTransfer $quoteTransfer): QuoteResponseTransfer
    {
        return $this->getFacade()->reloadItems($quoteTransfer);
    }

    public function changeItemQuantityAction(PersistentCartChangeQuantityTransfer $persistentCartChangeQuantityTransfer): QuoteResponseTransfer
    {
        return $this->getFacade()->changeItemQuantity($persistentCartChangeQuantityTransfer);
    }

    public function updateQuantityAction(PersistentCartChangeTransfer $persistentCartChangeTransfer): QuoteResponseTransfer
    {
        return $this->getFacade()->updateQuantity($persistentCartChangeTransfer);
    }

    public function decreaseItemQuantityAction(PersistentCartChangeQuantityTransfer $persistentCartChangeQuantityTransfer): QuoteResponseTransfer
    {
        return $this->getFacade()->decreaseItemQuantity($persistentCartChangeQuantityTransfer);
    }

    public function increaseItemQuantityAction(PersistentCartChangeQuantityTransfer $persistentCartChangeQuantityTransfer): QuoteResponseTransfer
    {
        return $this->getFacade()->increaseItemQuantity($persistentCartChangeQuantityTransfer);
    }

    public function syncStorageQuoteAction(QuoteSyncRequestTransfer $quoteSyncRequestTransfer): QuoteResponseTransfer
    {
        return $this->getFacade()->syncStorageQuote($quoteSyncRequestTransfer);
    }

    public function validateQuoteAction(QuoteTransfer $quoteTransfer): QuoteResponseTransfer
    {
        return $this->getFacade()->validateQuote($quoteTransfer);
    }

    public function deleteQuoteAction(QuoteTransfer $quoteTransfer): QuoteResponseTransfer
    {
        return $this->getFacade()->deleteQuote($quoteTransfer);
    }

    public function createQuoteAction(QuoteTransfer $quoteTransfer): QuoteResponseTransfer
    {
        return $this->getFacade()->createQuote($quoteTransfer);
    }

    public function createQuoteWithReloadedItemsAction(QuoteTransfer $quoteTransfer): QuoteResponseTransfer
    {
        return $this->getFacade()->createQuoteWithReloadedItems($quoteTransfer);
    }

    public function updateQuoteAction(QuoteUpdateRequestTransfer $quoteUpdateRequestTransfer): QuoteResponseTransfer
    {
        return $this->getFacade()->updateQuote($quoteUpdateRequestTransfer);
    }

    public function updateAndReloadQuoteAction(QuoteUpdateRequestTransfer $quoteUpdateRequestTransfer): QuoteResponseTransfer
    {
        return $this->getFacade()->updateAndReloadQuote($quoteUpdateRequestTransfer);
    }

    public function replaceQuoteByCustomerAndStoreAction(QuoteTransfer $quoteTransfer): QuoteResponseTransfer
    {
        return $this->getFacade()->replaceQuoteByCustomerAndStore($quoteTransfer);
    }

    public function resetQuoteLockAction(QuoteTransfer $quoteTransfer): QuoteResponseTransfer
    {
        return $this->getFacade()->resetQuoteLock($quoteTransfer);
    }

    public function replaceItemAction(PersistentItemReplaceTransfer $persistentItemReplaceTransfer): QuoteResponseTransfer
    {
        return $this->getFacade()->replaceItem($persistentItemReplaceTransfer);
    }
}
