<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\PersistentCart;

use Codeception\Actor;
use Codeception\Stub;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\PersistentCart\Dependency\Client\PersistentCartToCustomerClientInterface;
use Spryker\Client\PersistentCart\Dependency\Client\PersistentCartToQuoteClientInterface;
use Spryker\Client\PersistentCart\Dependency\Client\PersistentCartToZedRequestClientInterface;
use Spryker\Client\PersistentCart\QuoteUpdatePluginExecutor\QuoteUpdatePluginExecutorInterface;
use Spryker\Client\PersistentCart\Zed\PersistentCartStubInterface;

/**
 * Inherited Methods
 *
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 *
 * @SuppressWarnings(\SprykerTest\Client\PersistentCart\PHPMD)
 */
class PersistentCartClientTester extends Actor
{
    use _generated\PersistentCartClientTesterActions;

    public function createQuoteClientMock(QuoteTransfer $quoteTransfer, string $storageStrategy): PersistentCartToQuoteClientInterface
    {
        return Stub::makeEmpty(
            PersistentCartToQuoteClientInterface::class,
            [
                'getQuote' => $quoteTransfer,
                'getStorageStrategy' => $storageStrategy,
            ],
        );
    }

    public function createPersistentCartStubMock(?QuoteResponseTransfer $quoteResponseTransfer = null): PersistentCartStubInterface
    {
        return Stub::makeEmpty(
            PersistentCartStubInterface::class,
            [
                'syncStorageQuote' => $quoteResponseTransfer ?? (new QuoteResponseTransfer())->setIsSuccessful(true)->setQuoteTransfer(new QuoteTransfer()),
            ],
        );
    }

    public function createQuoteUpdatePluginExecutorMock(): QuoteUpdatePluginExecutorInterface
    {
        return Stub::makeEmpty(
            QuoteUpdatePluginExecutorInterface::class,
            [
                'executePlugins' => static function (QuoteResponseTransfer $quoteResponseTransfer) {
                    return $quoteResponseTransfer;
                },
            ],
        );
    }

    public function createZedRequestClientMock(): PersistentCartToZedRequestClientInterface
    {
        return Stub::makeEmpty(PersistentCartToZedRequestClientInterface::class);
    }

    public function createCustomerClientMock(): PersistentCartToCustomerClientInterface
    {
        return Stub::makeEmpty(PersistentCartToCustomerClientInterface::class);
    }
}
