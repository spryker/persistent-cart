<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PersistentCart\Business\Model;

use ArrayObject;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\PersistentCartChangeQuantityTransfer;
use Generated\Shared\Transfer\PersistentCartChangeTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\PersistentCart\Dependency\Facade\PersistentCartToCartFacadeInterface;
use Spryker\Zed\PersistentCart\Dependency\Facade\PersistentCartToQuoteFacadeInterface;

class CartOperation implements CartOperationInterface
{
    /**
     * @var \Spryker\Zed\PersistentCart\Dependency\Facade\PersistentCartToCartFacadeInterface
     */
    protected $cartFacade;

    /**
     * @var \Spryker\Zed\PersistentCart\Dependency\Facade\PersistentCartToQuoteFacadeInterface
     */
    protected $quoteFacade;

    /**
     * @param \Spryker\Zed\PersistentCart\Dependency\Facade\PersistentCartToCartFacadeInterface $cartFacade
     * @param \Spryker\Zed\PersistentCart\Dependency\Facade\PersistentCartToQuoteFacadeInterface $quoteFacade
     */
    public function __construct(PersistentCartToCartFacadeInterface $cartFacade, PersistentCartToQuoteFacadeInterface $quoteFacade)
    {
        $this->cartFacade = $cartFacade;
        $this->quoteFacade = $quoteFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\PersistentCartChangeTransfer $persistentCartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function add(PersistentCartChangeTransfer $persistentCartChangeTransfer)
    {
        $persistentCartChangeTransfer->requireCustomer();
        $quoteTransfer = $this->getCustomerQuote(
            $persistentCartChangeTransfer->getIdQuote(),
            $persistentCartChangeTransfer->getCustomer()
        );

        return $this->addItems((array)$persistentCartChangeTransfer->getItems(), $quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\PersistentCartChangeTransfer $persistentCartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function remove(PersistentCartChangeTransfer $persistentCartChangeTransfer)
    {
        $persistentCartChangeTransfer->requireCustomer();
        $quoteTransfer = $this->getCustomerQuote(
            $persistentCartChangeTransfer->getIdQuote(),
            $persistentCartChangeTransfer->getCustomer()
        );
        $itemTransferList = [];
        foreach ($persistentCartChangeTransfer->getItems() as $itemTransfer) {
            $itemTransferList[] = $this->findItemInQuote($itemTransfer, $quoteTransfer);
        }

        return $this->removeItems($itemTransferList, $quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function reloadItems(QuoteTransfer $quoteTransfer)
    {
        $quoteTransfer->requireCustomer();
        $quoteTransfer = $this->getCustomerQuote(
            $quoteTransfer->getIdQuote(),
            $quoteTransfer->getCustomer()
        );
        $quoteTransfer = $this->cartFacade->reloadItems($quoteTransfer);
        $this->quoteFacade->persistQuote($quoteTransfer);

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PersistentCartChangeQuantityTransfer $persistentCartChangeQuantityTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function changeItemQuantity(PersistentCartChangeQuantityTransfer $persistentCartChangeQuantityTransfer)
    {
        $persistentCartChangeQuantityTransfer->requireCustomer();
        $itemTransfer = $persistentCartChangeQuantityTransfer->getItem();
        $quoteTransfer = $this->getCustomerQuote(
            $persistentCartChangeQuantityTransfer->getIdQuote(),
            $persistentCartChangeQuantityTransfer->getCustomer()
        );
        $quoteItemTransfer = $this->findItemInQuote($itemTransfer, $quoteTransfer);
        if (!$quoteItemTransfer) {
            return $quoteTransfer;
        }
        if ($itemTransfer->getQuantity() === 0) {
            return $this->removeItems([$quoteItemTransfer], $quoteTransfer);
        }

        $delta = abs($quoteItemTransfer->getQuantity() - $itemTransfer->getQuantity());
        if ($delta === 0) {
            return $quoteTransfer;
        }

        $changeItemTransfer = clone $quoteItemTransfer;
        $changeItemTransfer->setQuantity($delta);
        if ($quoteItemTransfer->getQuantity() > $itemTransfer->getQuantity()) {
            return $this->removeItems([$changeItemTransfer], $quoteTransfer);
        }

        return $this->addItems([$changeItemTransfer], $quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\PersistentCartChangeQuantityTransfer $persistentCartChangeQuantityTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function decreaseItemQuantity(PersistentCartChangeQuantityTransfer $persistentCartChangeQuantityTransfer)
    {
        $persistentCartChangeQuantityTransfer->requireCustomer();
        $quoteTransfer = $this->getCustomerQuote(
            $persistentCartChangeQuantityTransfer->getIdQuote(),
            $persistentCartChangeQuantityTransfer->getCustomer()
        );
        $decreaseItemTransfer = $this->findItemInQuote($persistentCartChangeQuantityTransfer->getItem(), $quoteTransfer);
        if (!$decreaseItemTransfer || !$persistentCartChangeQuantityTransfer->getItem()->getQuantity()) {
            return $quoteTransfer;
        }

        $itemTransfer = clone $decreaseItemTransfer;
        $itemTransfer->setQuantity(
            $persistentCartChangeQuantityTransfer->getItem()->getQuantity()
        );

        return $this->removeItems([$itemTransfer], $quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\PersistentCartChangeQuantityTransfer $persistentCartChangeQuantityTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function increaseItemQuantity(PersistentCartChangeQuantityTransfer $persistentCartChangeQuantityTransfer)
    {
        $persistentCartChangeQuantityTransfer->requireCustomer();
        $quoteTransfer = $this->getCustomerQuote(
            $persistentCartChangeQuantityTransfer->getIdQuote(),
            $persistentCartChangeQuantityTransfer->getCustomer()
        );
        $decreaseItemTransfer = $this->findItemInQuote($persistentCartChangeQuantityTransfer->getItem(), $quoteTransfer);
        if (!$decreaseItemTransfer || !$persistentCartChangeQuantityTransfer->getItem()->getQuantity()) {
            return $quoteTransfer;
        }

        $itemTransfer = clone $decreaseItemTransfer;
        $itemTransfer->setQuantity(
            $persistentCartChangeQuantityTransfer->getItem()->getQuantity()
        );

        return $this->addItems([$itemTransfer], $quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemTransferList
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function addItems(array $itemTransferList, QuoteTransfer $quoteTransfer)
    {
        $cartChangeTransfer = $this->createCartChangeTransfer($quoteTransfer);
        foreach ($itemTransferList as $itemTransfer) {
            $cartChangeTransfer->addItem($itemTransfer);
        }

        $quoteTransfer = $this->cartFacade->add($cartChangeTransfer);
        $this->quoteFacade->persistQuote($quoteTransfer);

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemTransferList
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function removeItems(array $itemTransferList, QuoteTransfer $quoteTransfer)
    {
        $cartChangeTransfer = $this->createCartChangeTransfer($quoteTransfer);
        foreach ($itemTransferList as $itemTransfer) {
            $cartChangeTransfer->addItem($itemTransfer);
        }
        $quoteTransfer = $this->cartFacade->remove($cartChangeTransfer);
        $this->quoteFacade->persistQuote($quoteTransfer);

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return null|\Generated\Shared\Transfer\ItemTransfer
     */
    protected function findItemInQuote(ItemTransfer $itemTransfer, QuoteTransfer $quoteTransfer): ?ItemTransfer
    {
        foreach ($quoteTransfer->getItems() as $quoteItemTransfer) {
            if ($quoteItemTransfer->getSku() === $itemTransfer->getSku()
                && $quoteItemTransfer->getGroupKey() === $itemTransfer->getGroupKey()
            ) {
                return $quoteItemTransfer;
            }
        }

        return null;
    }

    /**
     * @param int $idQuote
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function getCustomerQuote($idQuote, CustomerTransfer $customerTransfer)
    {
        $quoteTransfer = new QuoteTransfer();
        $quoteResponseTransfer = $this->quoteFacade->findQuoteById($idQuote);
        if ($quoteResponseTransfer->getIsSuccessful()
            && strcmp(
                $customerTransfer->getCustomerReference(),
                $quoteResponseTransfer->getQuoteTransfer()->getCustomerReference()
            ) === 0
        ) {
            $quoteTransfer = $quoteResponseTransfer->getQuoteTransfer();
        }
        $quoteTransfer->setCustomer($customerTransfer);

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    protected function createCartChangeTransfer(QuoteTransfer $quoteTransfer)
    {
        $items = $quoteTransfer->getItems();

        if (count($items) === 0) {
            $quoteTransfer->setItems(new ArrayObject());
        }

        $cartChangeTransfer = new CartChangeTransfer();
        $cartChangeTransfer->setQuote($quoteTransfer);

        return $cartChangeTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteValidationResponseTransfer
     */
    public function validate($quoteTransfer)
    {
        $quoteTransfer->requireCustomer();
        $customerQuoteTransfer = $this->getCustomerQuote($quoteTransfer->getIdQuote(), $quoteTransfer->getCustomer());
        if ($customerQuoteTransfer) {
            $quoteTransfer->fromArray($customerQuoteTransfer->modifiedToArray(), true);
        }
        $quoteValidationResponseTransfer = $this->cartFacade->validateQuote($quoteTransfer);
        $this->quoteFacade->persistQuote($quoteValidationResponseTransfer->getQuoteTransfer());

        return $quoteValidationResponseTransfer;
    }
}
