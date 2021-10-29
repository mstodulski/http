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

use mstodulski\http\ParametersCollection;

interface ResponseInterface {

    const HTTP_STATUS_OK = 200;
    const HTTP_STATUS_CREATED = 201;
    const HTTP_STATUS_ACCEPTED = 202;
    const HTTP_STATUS_REDIRECT = 302;
    const HTTP_STATUS_SEE_OTHER = 303;
    const HTTP_STATUS_BAD_REQUEST = 400;
    const HTTP_STATUS_UNAUTHORIZED = 401;
    const HTTP_STATUS_NOT_FOUND = 404;
    const HTTP_STATUS_INTERNAL_SERVER_ERROR = 500;
    const HTTP_STATUS_NOT_IMPLEMENTED = 501;

    const HTTP_HEADER_DATE = 'Date';

    public function __construct($content = null, $httpStatus = ResponseInterface::HTTP_STATUS_OK, ParametersCollection $HTTPHeaderCollection = null);
    public function getContent() : ?string;
    public function getHttpHeaderCollection(): ParametersCollection;
    public function getHttpStatus() : int;
}
