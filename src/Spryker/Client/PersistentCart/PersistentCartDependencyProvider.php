<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PersistentCart;

use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;
use Spryker\Client\PersistentCart\Dependency\Client\PersistentCartToCustomerClientBridge;
use Spryker\Client\PersistentCart\Dependency\Client\PersistentCartToQuoteClientBridge;
use Spryker\Client\PersistentCart\Dependency\Client\PersistentCartToZedRequestClientBridge;
use Spryker\Client\PersistentCart\Plugin\PersistentCartQuotePersistPlugin;
use Spryker\Client\PersistentCartExtension\Dependency\Plugin\QuotePersistPluginInterface;

/**
 * @method \Spryker\Client\PersistentCart\PersistentCartConfig getConfig()
 */
class PersistentCartDependencyProvider extends AbstractDependencyProvider
{
    /**
     * @var string
     */
    public const CLIENT_CUSTOMER = 'CLIENT_CUSTOMER';

    /**
     * @var string
     */
    public const CLIENT_QUOTE = 'CLIENT_QUOTE';

    /**
     * @var string
     */
    public const CLIENT_ZED_REQUEST = 'CLIENT_ZED_REQUEST';

    /**
     * @var string
     */
    public const PLUGIN_QUOTE_PERSIST = 'PLUGIN_QUOTE_PERSIST';

    /**
     * @var string
     */
    public const PLUGINS_QUOTE_UPDATE = 'PLUGINS_QUOTE_UPDATE';

    /**
     * @var string
     */
    public const PLUGINS_CHANGE_REQUEST_EXTEND = 'PLUGINS_CHANGE_REQUEST_EXTEND';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container): Container
    {
        $container = $this->addCustomerClient($container);
        $container = $this->addQuoteClient($container);
        $container = $this->addQuoteUpdatePlugins($container);
        $container = $this->addQuotePersistPlugin($container);
        $container = $this->addChangeRequestExtendPlugins($container);
        $container = $this->addZedRequestClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addQuoteClient(Container $container): Container
    {
        $container->set(static::CLIENT_QUOTE, function (Container $container) {
            return new PersistentCartToQuoteClientBridge($container->getLocator()->quote()->client());
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addCustomerClient(Container $container): Container
    {
        $container->set(static::CLIENT_CUSTOMER, function (Container $container) {
            return new PersistentCartToCustomerClientBridge($container->getLocator()->customer()->client());
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addZedRequestClient(Container $container): Container
    {
        $container->set(static::CLIENT_ZED_REQUEST, function (Container $container) {
            return new PersistentCartToZedRequestClientBridge($container->getLocator()->zedRequest()->client());
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addQuoteUpdatePlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_QUOTE_UPDATE, function (Container $container) {
            return $this->getQuoteUpdatePlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addChangeRequestExtendPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_CHANGE_REQUEST_EXTEND, function (Container $container) {
            return $this->getChangeRequestExtendPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addQuotePersistPlugin(Container $container): Container
    {
        $container->set(static::PLUGIN_QUOTE_PERSIST, function (Container $container) {
            return $this->getQuotePersistPlugin();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Client\PersistentCartExtension\Dependency\Plugin\QuoteUpdatePluginInterface>
     */
    protected function getQuoteUpdatePlugins(): array
    {
        return [];
    }

    /**
     * @return array<\Spryker\Client\PersistentCartExtension\Dependency\Plugin\PersistentCartChangeExpanderPluginInterface>
     */
    protected function getChangeRequestExtendPlugins(): array
    {
        return [];
    }

    /**
     * @return \Spryker\Client\PersistentCartExtension\Dependency\Plugin\QuotePersistPluginInterface
     */
    protected function getQuotePersistPlugin(): QuotePersistPluginInterface
    {
        return new PersistentCartQuotePersistPlugin();
    }
}
