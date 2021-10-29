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

use DateTime;
use DateTimeZone;
use Exception;
use JetBrains\PhpStorm\NoReturn;

class Response implements ResponseInterface {

    protected mixed $content;
    protected int $httpStatus;
    protected ParametersCollection $httpHeaderCollection;

    /** @throws Exception */
    public function __construct($content = null, $httpStatus = ResponseInterface::HTTP_STATUS_OK, ParametersCollection $httpHeaderCollection = null)
    {
        $this->content = $content;
        $this->httpStatus = $httpStatus;
        if (isset($httpHeaderCollection)) {
            $this->httpHeaderCollection = $httpHeaderCollection;
        } else {
            $this->httpHeaderCollection = new ParametersCollection();
        }

        $dateTime = new DateTime('now', new DateTimeZone('UTC'));
        $this->httpHeaderCollection->addParameter(ResponseInterface::HTTP_HEADER_DATE, $dateTime->format('D, d M Y H:i:s') . ' GMT');
    }

    public function getContent() : ?string
    {
        return $this->content;
    }

    public function getHttpHeaderCollection(): ParametersCollection
    {
        return $this->httpHeaderCollection;
    }

    public function getHttpStatus(): int
    {
        return $this->httpStatus;
    }

    #[NoReturn] public function returnResponse()
    {
        http_response_code($this->getHttpStatus());
        foreach ($this->getHttpHeaderCollection() as $name => $header) {
            header(
                $name . ': ' . $header,
                true,
                $this->getHttpStatus()
            );
        }

        echo $this->content;
        die;
    }
}
