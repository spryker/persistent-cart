<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PersistentCart\Business\Model;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteCriteriaFilterTransfer;
use Generated\Shared\Transfer\QuoteMergeRequestTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteSyncRequestTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\PersistentCart\Business\Exception\QuoteSynchronizationNotAvailable;
use Spryker\Zed\PersistentCart\Dependency\Facade\PersistentCartToCartFacadeInterface;
use Spryker\Zed\PersistentCart\Dependency\Facade\PersistentCartToQuoteFacadeInterface;
use Spryker\Zed\PersistentCart\Dependency\Facade\PersistentCartToStoreFacadeInterface;

class QuoteStorageSynchronizer implements QuoteStorageSynchronizerInterface
{
    /**
     * @uses \Spryker\Shared\Quote\QuoteConfig::STORAGE_STRATEGY_DATABASE
     *
     * @var string
     */
    protected const STORAGE_STRATEGY_DATABASE = 'database';

    /**
     * @var \Spryker\Zed\PersistentCart\Dependency\Facade\PersistentCartToCartFacadeInterface
     */
    protected $cartFacade;

    /**
     * @var \Spryker\Zed\PersistentCart\Dependency\Facade\PersistentCartToQuoteFacadeInterface
     */
    protected $quoteFacade;

    /**
     * @var \Spryker\Zed\PersistentCart\Business\Model\QuoteResponseExpanderInterface
     */
    protected $quoteResponseExpander;

    /**
     * @var \Spryker\Zed\PersistentCart\Business\Model\QuoteMergerInterface
     */
    protected $quoteMerger;

    /**
     * @var \Spryker\Zed\PersistentCart\Dependency\Facade\PersistentCartToStoreFacadeInterface
     */
    protected $storeFacade;

    public function __construct(
        PersistentCartToCartFacadeInterface $cartFacade,
        PersistentCartToQuoteFacadeInterface $quoteFacade,
        QuoteResponseExpanderInterface $quoteResponseExpander,
        QuoteMergerInterface $quoteMerger,
        PersistentCartToStoreFacadeInterface $storeFacade
    ) {
        $this->cartFacade = $cartFacade;
        $this->quoteFacade = $quoteFacade;
        $this->quoteResponseExpander = $quoteResponseExpander;
        $this->quoteMerger = $quoteMerger;
        $this->storeFacade = $storeFacade;
    }

    public function syncStorageQuote(QuoteSyncRequestTransfer $quoteSyncRequestTransfer): QuoteResponseTransfer
    {
        $this->assertDatabaseStorageStrategy();
        $this->validateRequest($quoteSyncRequestTransfer);

        $customerTransfer = $quoteSyncRequestTransfer->getCustomerTransfer();

        $quoteTransfer = $quoteSyncRequestTransfer->getQuoteTransfer();
        $storeTransfer = $this->storeFacade->getCurrentStore();
        $quoteResponseTransfer = $this->getDefaultCustomerQuote($customerTransfer, $storeTransfer);

        if ($customerTransfer->getIdCustomer() === null && $quoteResponseTransfer->getQuoteTransfer() === null) {
            return new QuoteResponseTransfer();
        }

        if ($quoteResponseTransfer->getIsSuccessful()) {
            $quoteTransfer = $this->mergeQuotes($quoteTransfer, $quoteResponseTransfer->getQuoteTransfer());
        }

        $quoteTransfer->setCustomer($customerTransfer);
        if (count($quoteTransfer->getItems())) {
            $quoteTransfer = $this->cartFacade->reloadItems($quoteTransfer);
        }

        return $this->quoteResponseExpander->expand($this->saveQuote($quoteTransfer));
    }

    protected function validateRequest(QuoteSyncRequestTransfer $quoteSyncRequestTransfer): void
    {
        $quoteSyncRequestTransfer
            ->requireCustomerTransfer()
            ->requireQuoteTransfer();

        $quoteSyncRequestTransfer->getCustomerTransfer()->requireCustomerReference();
    }

    protected function mergeQuotes(QuoteTransfer $targetQuoteTransfer, QuoteTransfer $sourceQuoteTransfer): QuoteTransfer
    {
        if (!count($targetQuoteTransfer->getItems())) {
            $quoteTransfer = (new QuoteTransfer())
                ->fromArray($targetQuoteTransfer->modifiedToArray(), true)
                ->fromArray($sourceQuoteTransfer->modifiedToArray(), true);

            return $quoteTransfer;
        }
        $quoteMergeRequestTransfer = new QuoteMergeRequestTransfer();
        $quoteMergeRequestTransfer
            ->setTargetQuote($targetQuoteTransfer)
            ->setSourceQuote($sourceQuoteTransfer);

        $targetQuoteTransfer = $this->quoteMerger->merge($quoteMergeRequestTransfer);
        $targetQuoteTransfer->setIdQuote($sourceQuoteTransfer->getIdQuote());

        return $targetQuoteTransfer;
    }

    protected function saveQuote(QuoteTransfer $quoteTransfer): QuoteResponseTransfer
    {
        if ($quoteTransfer->getIdQuote()) {
            return $this->quoteFacade->updateQuote($quoteTransfer);
        }

        return $this->quoteFacade->createQuote($quoteTransfer);
    }

    /**
     * @throws \Spryker\Zed\PersistentCart\Business\Exception\QuoteSynchronizationNotAvailable
     *
     * @return void
     */
    protected function assertDatabaseStorageStrategy(): void
    {
        if ($this->quoteFacade->getStorageStrategy() !== static::STORAGE_STRATEGY_DATABASE) {
            throw new QuoteSynchronizationNotAvailable(
                sprintf(
                    'Synchronization available for "%s" storage strategy only',
                    static::STORAGE_STRATEGY_DATABASE,
                ),
            );
        }
    }

    protected function getDefaultCustomerQuote(
        CustomerTransfer $customerTransfer,
        StoreTransfer $storeTransfer
    ): QuoteResponseTransfer {
        $quoteCriteriaFilterTransfer = (new QuoteCriteriaFilterTransfer())
            ->setCustomerReference($customerTransfer->getCustomerReferenceOrFail())
            ->setIdStore($storeTransfer->getIdStoreOrFail())
            ->setIsDefault(true);

        $defaultQuoteTransfer = $this->quoteFacade->getQuoteCollection($quoteCriteriaFilterTransfer)
            ->getQuotes()
            ->getIterator()
            ->current();

        return (new QuoteResponseTransfer())
            ->setIsSuccessful($defaultQuoteTransfer !== null)
            ->setQuoteTransfer($defaultQuoteTransfer);
    }
}
