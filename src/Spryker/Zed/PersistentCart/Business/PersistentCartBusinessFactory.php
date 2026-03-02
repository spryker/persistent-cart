<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PersistentCart\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\PersistentCart\Business\Locker\QuoteLocker;
use Spryker\Zed\PersistentCart\Business\Locker\QuoteLockerInterface;
use Spryker\Zed\PersistentCart\Business\Model\CartChangeRequestExpander;
use Spryker\Zed\PersistentCart\Business\Model\CartChangeRequestExpanderInterface;
use Spryker\Zed\PersistentCart\Business\Model\CartOperation;
use Spryker\Zed\PersistentCart\Business\Model\CartOperationInterface;
use Spryker\Zed\PersistentCart\Business\Model\QuoteDeleter;
use Spryker\Zed\PersistentCart\Business\Model\QuoteDeleterInterface;
use Spryker\Zed\PersistentCart\Business\Model\QuoteItemOperation;
use Spryker\Zed\PersistentCart\Business\Model\QuoteItemOperationInterface;
use Spryker\Zed\PersistentCart\Business\Model\QuoteMerger;
use Spryker\Zed\PersistentCart\Business\Model\QuoteMergerInterface;
use Spryker\Zed\PersistentCart\Business\Model\QuoteResolver;
use Spryker\Zed\PersistentCart\Business\Model\QuoteResolverInterface;
use Spryker\Zed\PersistentCart\Business\Model\QuoteResponseExpander;
use Spryker\Zed\PersistentCart\Business\Model\QuoteResponseExpanderInterface;
use Spryker\Zed\PersistentCart\Business\Model\QuoteStorageSynchronizer;
use Spryker\Zed\PersistentCart\Business\Model\QuoteStorageSynchronizerInterface;
use Spryker\Zed\PersistentCart\Business\Model\QuoteWriter;
use Spryker\Zed\PersistentCart\Business\Model\QuoteWriterInterface;
use Spryker\Zed\PersistentCart\Business\Provider\CartReorderProvider;
use Spryker\Zed\PersistentCart\Business\Provider\CartReorderProviderInterface;
use Spryker\Zed\PersistentCart\Business\Replacer\CartItemReplacer;
use Spryker\Zed\PersistentCart\Business\Replacer\CartItemReplacerInterface;
use Spryker\Zed\PersistentCart\Dependency\Facade\PersistentCartToStoreFacadeInterface;
use Spryker\Zed\PersistentCart\PersistentCartDependencyProvider;
use Spryker\Zed\PersistentCartExtension\Dependency\Plugin\QuoteItemFinderPluginInterface;

/**
 * @method \Spryker\Zed\PersistentCart\PersistentCartConfig getConfig()
 */
class PersistentCartBusinessFactory extends AbstractBusinessFactory
{
    public function createCartOperation(): CartOperationInterface
    {
        return new CartOperation(
            $this->getQuoteItemFinderPlugin(),
            $this->createQuoteResponseExpanderForForInsideCartOperations(),
            $this->createQuoteResolver(),
            $this->createQuoteItemOperation(),
            $this->getQuoteFacade(),
            $this->getQuotePostMergePlugins(),
        );
    }

    public function createCartOperationForValidation(): CartOperationInterface
    {
        return new CartOperation(
            $this->getQuoteItemFinderPlugin(),
            $this->createQuoteResponseExpander(),
            $this->createQuoteResolver(),
            $this->createQuoteItemOperationForValidation(),
            $this->getQuoteFacade(),
            $this->getQuotePostMergePlugins(),
        );
    }

    public function createCartItemOperation(): CartItemReplacerInterface
    {
        return new CartItemReplacer(
            $this->getQuoteItemFinderPlugin(),
            $this->createQuoteResolver(),
            $this->createQuoteItemOperation(),
            $this->getQuoteFacade(),
        );
    }

    public function createQuoteItemOperation(): QuoteItemOperationInterface
    {
        return new QuoteItemOperation(
            $this->getCartFacade(),
            $this->getQuoteFacade(),
            $this->createCartChangeRequestExpander(),
            $this->createQuoteResponseExpanderForForInsideCartOperations(),
            $this->getMessengerFacade(),
        );
    }

    public function createQuoteItemOperationForValidation(): QuoteItemOperationInterface
    {
        return new QuoteItemOperation(
            $this->getCartFacade(),
            $this->getQuoteFacade(),
            $this->createCartChangeRequestExpander(),
            $this->createQuoteResponseExpander(),
            $this->getMessengerFacade(),
        );
    }

    public function createQuoteResolver(): QuoteResolverInterface
    {
        return new QuoteResolver(
            $this->getQuoteFacade(),
            $this->createQuoteResponseExpanderForForInsideCartOperations(),
            $this->getMessengerFacade(),
            $this->getStoreFacade(),
            $this->getConfig(),
        );
    }

    public function createQuoteStorageSynchronizer(): QuoteStorageSynchronizerInterface
    {
        return new QuoteStorageSynchronizer(
            $this->getCartFacade(),
            $this->getQuoteFacade(),
            $this->createQuoteResponseExpander(),
            $this->createQuoteMerger(),
            $this->getStoreFacade(),
        );
    }

    public function createQuoteDeleter(): QuoteDeleterInterface
    {
        return new QuoteDeleter(
            $this->getQuoteFacade(),
            $this->createQuoteResponseExpander(),
            $this->getMessengerFacade(),
        );
    }

    public function createQuoteWriter(): QuoteWriterInterface
    {
        return new QuoteWriter(
            $this->getQuoteFacade(),
            $this->createQuoteResponseExpander(),
            $this->createQuoteResolver(),
            $this->createQuoteItemOperation(),
        );
    }

    public function createQuoteResponseExpander(): QuoteResponseExpanderInterface
    {
        return new QuoteResponseExpander(
            $this->getQuoteResponseExpanderPlugins(),
        );
    }

    public function createQuoteResponseExpanderForForInsideCartOperations(): QuoteResponseExpanderInterface
    {
        return new QuoteResponseExpander(
            $this->getQuoteResponseExpanderPluginsForInsideCartOperations(),
        );
    }

    public function createCartChangeRequestExpander(): CartChangeRequestExpanderInterface
    {
        return new CartChangeRequestExpander(
            $this->getRemoveItemsRequestExpanderPlugins(),
        );
    }

    public function createQuoteMerger(): QuoteMergerInterface
    {
        return new QuoteMerger(
            $this->getCartAddItemStrategyPlugins(),
        );
    }

    public function createQuoteLocker(): QuoteLockerInterface
    {
        return new QuoteLocker(
            $this->getCartFacade(),
            $this->createQuoteResolver(),
            $this->getQuoteFacade(),
            $this->createQuoteResponseExpanderForForInsideCartOperations(),
        );
    }

    public function createCartReorderProvider(): CartReorderProviderInterface
    {
        return new CartReorderProvider(
            $this->getQuoteFacade(),
            $this->createQuoteWriter(),
        );
    }

    /**
     * @return \Spryker\Zed\PersistentCart\Dependency\Facade\PersistentCartToCartFacadeInterface
     */
    public function getCartFacade()
    {
        return $this->getProvidedDependency(PersistentCartDependencyProvider::FACADE_CART);
    }

    /**
     * @return \Spryker\Zed\PersistentCart\Dependency\Facade\PersistentCartToMessengerFacadeInterface
     */
    public function getMessengerFacade()
    {
        return $this->getProvidedDependency(PersistentCartDependencyProvider::FACADE_MESSENGER);
    }

    /**
     * @return \Spryker\Zed\PersistentCart\Dependency\Facade\PersistentCartToQuoteFacadeInterface
     */
    public function getQuoteFacade()
    {
        return $this->getProvidedDependency(PersistentCartDependencyProvider::FACADE_QUOTE);
    }

    public function getStoreFacade(): PersistentCartToStoreFacadeInterface
    {
        return $this->getProvidedDependency(PersistentCartDependencyProvider::FACADE_STORE);
    }

    protected function getQuoteItemFinderPlugin(): QuoteItemFinderPluginInterface
    {
        return $this->getProvidedDependency(PersistentCartDependencyProvider::PLUGIN_QUOTE_ITEM_FINDER);
    }

    /**
     * @return array<\Spryker\Zed\PersistentCartExtension\Dependency\Plugin\CartChangeRequestExpandPluginInterface>
     */
    protected function getRemoveItemsRequestExpanderPlugins(): array
    {
        return $this->getProvidedDependency(PersistentCartDependencyProvider::PLUGINS_REMOVE_ITEMS_REQUEST_EXPANDER);
    }

    /**
     * @return array<\Spryker\Zed\PersistentCartExtension\Dependency\Plugin\QuoteResponseExpanderPluginInterface>
     */
    protected function getQuoteResponseExpanderPlugins(): array
    {
        return $this->getProvidedDependency(PersistentCartDependencyProvider::PLUGINS_QUOTE_RESPONSE_EXPANDER);
    }

    /**
     * @return array<\Spryker\Zed\PersistentCartExtension\Dependency\Plugin\QuoteResponseExpanderPluginInterface>
     */
    protected function getQuoteResponseExpanderPluginsForInsideCartOperations(): array
    {
        return $this->getProvidedDependency(PersistentCartDependencyProvider::PLUGINS_QUOTE_RESPONSE_EXPANDER_FOR_INSIDE_CART_OPERATIONS);
    }

    /**
     * @return array<\Spryker\Zed\CartExtension\Dependency\Plugin\CartOperationStrategyPluginInterface>
     */
    public function getCartAddItemStrategyPlugins(): array
    {
        return $this->getProvidedDependency(PersistentCartDependencyProvider::PLUGINS_CART_ADD_ITEM_STRATEGY);
    }

    /**
     * @return list<\Spryker\Zed\PersistentCartExtension\Dependency\Plugin\QuotePostMergePluginInterface>
     */
    public function getQuotePostMergePlugins(): array
    {
        return $this->getProvidedDependency(PersistentCartDependencyProvider::PLUGINS_QUOTE_POST_MERGE);
    }
}
