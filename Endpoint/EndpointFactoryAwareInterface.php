<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\ApiBundle\Endpoint;

use Enhavo\Component\Type\FactoryInterface;

interface EndpointFactoryAwareInterface
{
    public function setEndpointFactory(FactoryInterface $factory): void;
}
