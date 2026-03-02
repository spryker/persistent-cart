<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PersistentCart\Business\Locker;

use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\PersistentCart\Business\Model\QuoteResolverInterface;
use Spryker\Zed\PersistentCart\Business\Model\QuoteResponseExpanderInterface;
use Spryker\Zed\PersistentCart\Dependency\Facade\PersistentCartToCartFacadeInterface;
use Spryker\Zed\PersistentCart\Dependency\Facade\PersistentCartToQuoteFacadeInterface;

class QuoteLocker implements QuoteLockerInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\PersistentCart\Dependency\Facade\PersistentCartToCartFacadeInterface
     */
    protected $cartFacade;

    /**
     * @var \Spryker\Zed\PersistentCart\Business\Model\QuoteResolverInterface
     */
    protected $quoteResolver;

    /**
     * @var \Spryker\Zed\PersistentCart\Dependency\Facade\PersistentCartToQuoteFacadeInterface
     */
    protected $quoteFacade;

    /**
     * @var \Spryker\Zed\PersistentCart\Business\Model\QuoteResponseExpanderInterface
     */
    protected $quoteResponseExpander;

    public function __construct(
        PersistentCartToCartFacadeInterface $cartFacade,
        QuoteResolverInterface $quoteResolver,
        PersistentCartToQuoteFacadeInterface $quoteFacade,
        QuoteResponseExpanderInterface $quoteResponseExpander
    ) {
        $this->cartFacade = $cartFacade;
        $this->quoteResolver = $quoteResolver;
        $this->quoteFacade = $quoteFacade;
        $this->quoteResponseExpander = $quoteResponseExpander;
    }

    public function resetQuoteLock(QuoteTransfer $quoteTransfer): QuoteResponseTransfer
    {
        return $this->getTransactionHandler()->handleTransaction(function () use ($quoteTransfer) {
            return $this->executeResetQuoteLockTransaction($quoteTransfer);
        });
    }

    public function executeResetQuoteLockTransaction(QuoteTransfer $quoteTransfer): QuoteResponseTransfer
    {
        $quoteTransfer->requireCustomer();

        $quoteResponseTransfer = $this->quoteResolver->resolveCustomerQuote(
            $quoteTransfer->getIdQuote(),
            $quoteTransfer->getCustomer(),
        );

        if (!$quoteResponseTransfer->getIsSuccessful()) {
            return $quoteResponseTransfer;
        }

        $quoteResponseTransfer = $this->cartFacade->resetQuoteLock($quoteResponseTransfer->getQuoteTransfer());
        $quoteResponseTransfer->setCustomer($quoteTransfer->getCustomer());
        $this->quoteFacade->updateQuote($quoteResponseTransfer->getQuoteTransfer());

        return $this->quoteResponseExpander->expand($quoteResponseTransfer);
    }
}
