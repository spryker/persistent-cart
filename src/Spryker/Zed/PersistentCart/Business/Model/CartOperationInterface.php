<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PersistentCart\Business\Model;

use Generated\Shared\Transfer\PersistentCartChangeQuantityTransfer;
use Generated\Shared\Transfer\PersistentCartChangeTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

interface CartOperationInterface
{
    public function add(PersistentCartChangeTransfer $persistentCartChangeTransfer): QuoteResponseTransfer;

    public function addValid(PersistentCartChangeTransfer $persistentCartChangeTransfer): QuoteResponseTransfer;

    public function remove(PersistentCartChangeTransfer $persistentCartChangeTransfer): QuoteResponseTransfer;

    public function reloadItems(QuoteTransfer $quoteTransfer): QuoteResponseTransfer;

    public function changeItemQuantity(PersistentCartChangeQuantityTransfer $persistentCartChangeQuantityTransfer): QuoteResponseTransfer;

    public function updateQuantity(PersistentCartChangeTransfer $persistentCartChangeTransfer): QuoteResponseTransfer;

    public function decreaseItemQuantity(PersistentCartChangeQuantityTransfer $persistentCartChangeQuantityTransfer): QuoteResponseTransfer;

    public function increaseItemQuantity(PersistentCartChangeQuantityTransfer $persistentCartChangeQuantityTransfer): QuoteResponseTransfer;

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function validate($quoteTransfer): QuoteResponseTransfer;
}
