<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PersistentCart;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\PersistentCart\Dependency\Client\PersistentCartToCustomerClientInterface;
use Spryker\Client\PersistentCart\Dependency\Client\PersistentCartToQuoteClientInterface;
use Spryker\Client\PersistentCart\Dependency\Client\PersistentCartToZedRequestClientInterface;
use Spryker\Client\PersistentCart\GuestCartCustomerReferenceGenerator\GuestCartCustomerReferenceGenerator;
use Spryker\Client\PersistentCart\GuestCartCustomerReferenceGenerator\GuestCartCustomerReferenceGeneratorInterface;
use Spryker\Client\PersistentCart\QuoteStorageSynchronizer\CustomerLoginQuoteSync;
use Spryker\Client\PersistentCart\QuoteStorageSynchronizer\CustomerLoginQuoteSyncInterface;
use Spryker\Client\PersistentCart\QuoteStorageSynchronizer\CustomerQuoteCleaner;
use Spryker\Client\PersistentCart\QuoteStorageSynchronizer\CustomerQuoteCleanerInterface;
use Spryker\Client\PersistentCart\QuoteUpdatePluginExecutor\ChangeRequestExtendPluginExecutor;
use Spryker\Client\PersistentCart\QuoteUpdatePluginExecutor\ChangeRequestExtendPluginExecutorInterface;
use Spryker\Client\PersistentCart\QuoteUpdatePluginExecutor\QuoteUpdatePluginExecutor;
use Spryker\Client\PersistentCart\QuoteUpdatePluginExecutor\QuoteUpdatePluginExecutorInterface;
use Spryker\Client\PersistentCart\QuoteWriter\QuoteCreator;
use Spryker\Client\PersistentCart\QuoteWriter\QuoteCreatorInterface;
use Spryker\Client\PersistentCart\QuoteWriter\QuoteDeleter;
use Spryker\Client\PersistentCart\QuoteWriter\QuoteDeleterInterface;
use Spryker\Client\PersistentCart\QuoteWriter\QuoteUpdater;
use Spryker\Client\PersistentCart\QuoteWriter\QuoteUpdaterInterface;
use Spryker\Client\PersistentCart\QuoteWriter\QuoteWriter;
use Spryker\Client\PersistentCart\QuoteWriter\QuoteWriterInterface;
use Spryker\Client\PersistentCart\Zed\PersistentCartStub;
use Spryker\Client\PersistentCart\Zed\PersistentCartStubInterface;
use Spryker\Client\PersistentCartExtension\Dependency\Plugin\QuotePersistPluginInterface;

/**
 * @method \Spryker\Client\PersistentCart\PersistentCartConfig getConfig()
 */
class PersistentCartFactory extends AbstractFactory
{
    public function createQuoteCreator(): QuoteCreatorInterface
    {
        return new QuoteCreator(
            $this->getQuoteClient(),
            $this->getZedRequestClient(),
            $this->createZedPersistentCartStub(),
            $this->createQuoteUpdatePluginExecutor(),
        );
    }

    public function createQuoteUpdater(): QuoteUpdaterInterface
    {
        return new QuoteUpdater(
            $this->getQuoteClient(),
            $this->createZedPersistentCartStub(),
            $this->createQuoteUpdatePluginExecutorForInsideCartOperations(),
        );
    }

    public function createQuoteWriter(): QuoteWriterInterface
    {
        return new QuoteWriter(
            $this->getQuotePersistPlugin(),
            $this->getQuoteClient(),
        );
    }

    public function createQuoteDeleter(): QuoteDeleterInterface
    {
        return new QuoteDeleter(
            $this->getQuoteClient(),
            $this->getZedRequestClient(),
            $this->getCustomerClient(),
            $this->createZedPersistentCartStub(),
            $this->createQuoteUpdatePluginExecutor(),
        );
    }

    public function getQuoteClient(): PersistentCartToQuoteClientInterface
    {
        return $this->getProvidedDependency(PersistentCartDependencyProvider::CLIENT_QUOTE);
    }

    public function getZedRequestClient(): PersistentCartToZedRequestClientInterface
    {
        return $this->getProvidedDependency(PersistentCartDependencyProvider::CLIENT_ZED_REQUEST);
    }

    public function createZedPersistentCartStub(): PersistentCartStubInterface
    {
        return new PersistentCartStub($this->getZedRequestClient());
    }

    public function getCustomerClient(): PersistentCartToCustomerClientInterface
    {
        return $this->getProvidedDependency(PersistentCartDependencyProvider::CLIENT_CUSTOMER);
    }

    public function createQuoteUpdatePluginExecutor(): QuoteUpdatePluginExecutorInterface
    {
        return new QuoteUpdatePluginExecutor($this->getQuoteUpdatePlugins());
    }

    public function createQuoteUpdatePluginExecutorForInsideCartOperations(): QuoteUpdatePluginExecutorInterface
    {
        return new QuoteUpdatePluginExecutor($this->getQuoteUpdatePluginsForInsideCartOperations());
    }

    public function createChangeRequestExtendPluginExecutor(): ChangeRequestExtendPluginExecutorInterface
    {
        return new ChangeRequestExtendPluginExecutor($this->getChangeRequestExtendPlugins());
    }

    public function createCustomerLoginQuoteSync(): CustomerLoginQuoteSyncInterface
    {
        return new CustomerLoginQuoteSync(
            $this->createZedPersistentCartStub(),
            $this->getQuoteClient(),
            $this->createQuoteUpdatePluginExecutor(),
            $this->getZedRequestClient(),
            $this->getCustomerClient(),
        );
    }

    public function createCustomerQuoteCleaner(): CustomerQuoteCleanerInterface
    {
        return new CustomerQuoteCleaner(
            $this->createZedPersistentCartStub(),
            $this->getQuoteClient(),
            $this->createQuoteUpdatePluginExecutorForInsideCartOperations(),
            $this->getZedRequestClient(),
        );
    }

    /**
     * @return array<\Spryker\Client\PersistentCartExtension\Dependency\Plugin\QuoteUpdatePluginInterface>
     */
    protected function getQuoteUpdatePlugins(): array
    {
        return $this->getProvidedDependency(PersistentCartDependencyProvider::PLUGINS_QUOTE_UPDATE);
    }

    /**
     * @return array<\Spryker\Client\PersistentCartExtension\Dependency\Plugin\QuoteUpdatePluginInterface>
     */
    protected function getQuoteUpdatePluginsForInsideCartOperations(): array
    {
        return $this->getProvidedDependency(PersistentCartDependencyProvider::PLUGINS_QUOTE_UPDATE_FOR_INSIDE_CART_OPERATIONS);
    }

    /**
     * @return array<\Spryker\Client\PersistentCartExtension\Dependency\Plugin\PersistentCartChangeExpanderPluginInterface>
     */
    protected function getChangeRequestExtendPlugins(): array
    {
        return $this->getProvidedDependency(PersistentCartDependencyProvider::PLUGINS_CHANGE_REQUEST_EXTEND);
    }

    public function getQuotePersistPlugin(): QuotePersistPluginInterface
    {
        return $this->getProvidedDependency(PersistentCartDependencyProvider::PLUGIN_QUOTE_PERSIST);
    }

    public function createGuestCartCustomerReferenceGenerator(): GuestCartCustomerReferenceGeneratorInterface
    {
        return new GuestCartCustomerReferenceGenerator($this->getConfig());
    }
}
