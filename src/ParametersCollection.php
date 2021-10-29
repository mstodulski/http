<?php
/**
 * This file is part of the EasyCore package.
 *
 * (c) Marcin Stodulski <marcin.stodulski@devsprint.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace mstodulski\http;

class ParametersCollection {

    private array $parameters;

    public function getParameters(): array
    {
        return $this->parameters;
    }

    public function setParameters(array $parameters): void
    {
        $this->parameters = $parameters;
    }

    public function addParameter(string $name, mixed $value)
    {
        $this->parameters[$name] = $value;
    }

    public function get(string $name)
    {
        return $this->parameters[$name] ?? null;
    }

    public function clear()
    {
        $this->parameters = [];
    }
}
