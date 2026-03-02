<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PersistentCart\QuoteUpdatePluginExecutor;

use Generated\Shared\Transfer\QuoteResponseTransfer;

interface QuoteUpdatePluginExecutorInterface
{
    public function executePlugins(QuoteResponseTransfer $quoteResponseTransfer): QuoteResponseTransfer;
}
