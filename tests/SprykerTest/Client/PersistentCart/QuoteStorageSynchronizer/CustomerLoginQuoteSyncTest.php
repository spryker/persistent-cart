<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\PersistentCart\QuoteStorageSynchronizer;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\PersistentCart\QuoteStorageSynchronizer\CustomerLoginQuoteSync;
use Spryker\Client\PersistentCart\Zed\PersistentCartStubInterface;
use SprykerTest\Client\PersistentCart\PersistentCartClientTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group PersistentCart
 * @group QuoteStorageSynchronizer
 * @group CustomerLoginQuoteSyncTest
 * Add your own group annotations below this line
 */
class CustomerLoginQuoteSyncTest extends Unit
{
    protected const STORAGE_STRATEGY_DATABASE = 'database';

    protected const STORAGE_STRATEGY_SESSION = 'session';

    protected PersistentCartClientTester $tester;

    public function testSyncQuoteForCustomerShouldSkipZedCallWhenQuoteHasNoItems(): void
    {
        // Arrange
        $persistentCartStubMock = $this->tester->createPersistentCartStubMock();
        $customerLoginQuoteSync = $this->createCustomerLoginQuoteSync(
            new QuoteTransfer(),
            static::STORAGE_STRATEGY_DATABASE,
            $persistentCartStubMock,
        );

        // Assert
        $persistentCartStubMock->expects($this->never())->method('syncStorageQuote');

        // Act
        $customerLoginQuoteSync->syncQuoteForCustomer(new CustomerTransfer());
    }

    public function testSyncQuoteForCustomerShouldSyncQuoteWhenQuoteHasItemsAndNoCustomerYet(): void
    {
        // Arrange
        $persistentCartStubMock = $this->tester->createPersistentCartStubMock();
        $quoteTransfer = (new QuoteTransfer())->addItem(new ItemTransfer());
        $customerLoginQuoteSync = $this->createCustomerLoginQuoteSync(
            $quoteTransfer,
            static::STORAGE_STRATEGY_DATABASE,
            $persistentCartStubMock,
        );

        // Assert
        $persistentCartStubMock->expects($this->once())->method('syncStorageQuote');

        // Act
        $customerLoginQuoteSync->syncQuoteForCustomer(new CustomerTransfer());
    }

    public function testSyncQuoteForCustomerShouldSkipZedCallWhenStorageStrategyIsNotDatabase(): void
    {
        // Arrange
        $persistentCartStubMock = $this->tester->createPersistentCartStubMock();
        $quoteTransfer = (new QuoteTransfer())->addItem(new ItemTransfer());
        $customerLoginQuoteSync = $this->createCustomerLoginQuoteSync(
            $quoteTransfer,
            static::STORAGE_STRATEGY_SESSION,
            $persistentCartStubMock,
        );

        // Assert
        $persistentCartStubMock->expects($this->never())->method('syncStorageQuote');

        // Act
        $customerLoginQuoteSync->syncQuoteForCustomer(new CustomerTransfer());
    }

    public function testSyncQuoteForCustomerShouldSkipZedCallWhenQuoteAlreadyHasCustomer(): void
    {
        // Arrange
        $persistentCartStubMock = $this->tester->createPersistentCartStubMock();
        $quoteTransfer = (new QuoteTransfer())
            ->addItem(new ItemTransfer())
            ->setCustomer(new CustomerTransfer());
        $customerLoginQuoteSync = $this->createCustomerLoginQuoteSync(
            $quoteTransfer,
            static::STORAGE_STRATEGY_DATABASE,
            $persistentCartStubMock,
        );

        // Assert
        $persistentCartStubMock->expects($this->never())->method('syncStorageQuote');

        // Act
        $customerLoginQuoteSync->syncQuoteForCustomer(new CustomerTransfer());
    }

    protected function createCustomerLoginQuoteSync(
        QuoteTransfer $quoteTransfer,
        string $storageStrategy,
        PersistentCartStubInterface $persistentCartStubMock
    ): CustomerLoginQuoteSync {
        return new CustomerLoginQuoteSync(
            $persistentCartStubMock,
            $this->tester->createQuoteClientMock($quoteTransfer, $storageStrategy),
            $this->tester->createQuoteUpdatePluginExecutorMock(),
            $this->tester->createZedRequestClientMock(),
            $this->tester->createCustomerClientMock(),
        );
    }
}
