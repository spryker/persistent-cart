<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PersistentCart\Dependency\Plugin;

use Generated\Shared\Transfer\QuoteResponseTransfer;

interface QuoteUpdatePluginInterface
{
    /**
     * Specification:
     * - .
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteResponseTransfer $quoteResponseTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function processResponse(QuoteResponseTransfer $quoteResponseTransfer): QuoteResponseTransfer;
}