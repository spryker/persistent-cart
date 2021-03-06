<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PersistentCart\Dependency\Client;

use Spryker\Shared\Kernel\Transfer\TransferInterface;

interface PersistentCartToZedRequestClientInterface
{
    /**
     * @param string $url
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $object
     * @param array|null $requestOptions
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function call($url, TransferInterface $object, $requestOptions = null);

    /**
     * @return void
     */
    public function addFlashMessagesFromLastZedRequest();

    /**
     * @return void
     */
    public function addResponseMessagesToMessenger(): void;
}
