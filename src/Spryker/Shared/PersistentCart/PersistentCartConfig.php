<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\PersistentCart;

use Spryker\Shared\Kernel\AbstractBundleConfig;

class PersistentCartConfig extends AbstractBundleConfig
{
    /**
     * Persistent cart anonymous prefix.
     *
     * @var string
     */
    protected const PERSISTENT_CART_ANONYMOUS_PREFIX = 'anonymous:';

    protected const bool IS_QUOTE_UPDATE_PLUGINS_INSIDE_CART_ENABLED = false;

    /**
     * @api
     *
     * @return string
     */
    public function getPersistentCartAnonymousPrefix(): string
    {
        return static::PERSISTENT_CART_ANONYMOUS_PREFIX;
    }

    /**
     * Specification:
     * - Determines whether separate quote update plugins should be used for cart operations.
     * - When enabled, uses dedicated plugins for inside-cart operations instead of default quote update plugins.
     * - Affects both client and Zed layer quote processing within cart context.
     *
     * @api
     *
     * @return bool
     */
    public function isQuoteUpdatePluginsInsideCartEnabled(): bool
    {
        return static::IS_QUOTE_UPDATE_PLUGINS_INSIDE_CART_ENABLED;
    }
}
