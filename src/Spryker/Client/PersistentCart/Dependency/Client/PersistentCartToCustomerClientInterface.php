<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PersistentCart\Dependency\Client;

use Generated\Shared\Transfer\CustomerTransfer;

interface PersistentCartToCustomerClientInterface
{
    public function getCustomer(): ?CustomerTransfer;
}
