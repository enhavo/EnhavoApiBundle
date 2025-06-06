<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\ApiBundle\Profiler;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ApiBundle\Endpoint\Context;
use Enhavo\Bundle\ApiBundle\Endpoint\EndpointTypeExtensionInterface;
use Enhavo\Bundle\ApiBundle\Endpoint\EndpointTypeInterface;
use Symfony\Bundle\FrameworkBundle\DataCollector\AbstractDataCollector;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class EndpointDataCollector extends AbstractDataCollector
{
    public function collect(Request $request, Response $response, ?\Throwable $exception = null): void
    {
    }

    private function initNodes(): void
    {
        if (!isset($this->data['nodes'])) {
            $this->data['nodes'] = [];
        }
    }

    public function addParent(EndpointTypeInterface $parent): void
    {
        $this->initNodes();
        $this->data['nodes'][] = new EndpointNode(get_class($parent));
    }

    public function addType(EndpointTypeInterface $parent): void
    {
        $this->initNodes();
        $this->data['nodes'][] = new EndpointNode(get_class($parent));
    }

    public function addExtension(EndpointTypeExtensionInterface $extension): void
    {
        $this->initNodes();
        $this->getLastNode()->addExtension(get_class($extension));
    }

    public function getLastNode(): ?EndpointNode
    {
        $this->initNodes();

        if (count($this->data['nodes'])) {
            return $this->data['nodes'][count($this->data['nodes']) - 1];
        }

        return null;
    }

    public function getType(): ?string
    {
        if (null === $this->getLastNode()) {
            return null;
        }

        $parts = explode('\\', $this->getLastNode()->getType());

        return array_pop($parts);
    }

    public function setData(Data $data): void
    {
        $this->data['data'] = $this->cloneVar($data->normalize());
    }

    public function getData()
    {
        return $this->data['data'];
    }

    public function setContext(Context $data): void
    {
        $this->data['context'] = $this->cloneVar($data->getData());
    }

    public function getContext()
    {
        return $this->data['context'];
    }

    public function setOptions($options): void
    {
        $this->data['options'] = $this->cloneVar($options);
        $this->data['options_original'] = $options;
    }

    public function getOptions()
    {
        return $this->data['options'];
    }

    public function getOriginalOptions()
    {
        return $this->data['options_original'];
    }

    /** @return EndpointNode[] */
    public function getNodes()
    {
        $this->initNodes();

        return $this->data['nodes'];
    }

    public function getName(): string
    {
        return 'endpoint';
    }

    public static function getTemplate(): ?string
    {
        return '@EnhavoApi/profiler/endpoint.html.twig';
    }
}
