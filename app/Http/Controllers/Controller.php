<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Validator;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    //#################################### Declare Properties ########################################################//

    /**
     * This wil hold the http status code
     * @var int
     */
    private $httpStatusCode = 200;

    /**
     * This will hold custom status code
     * @var null
     */
    private $customStatusCode = null;

    /**
     * This will contains http headers information
     * @var array
     */
    private $httpHeader = [];

    /**
     * This will contains error code
     * @var int
     */
    private $errorCode = null;

    /**
     * This is the name of main key which will hold the resources
     */
    const RESOURCE = 'payload';

    /**
     * This will hold the exception if the debug mode is set to true
     * @var null
     */
    private $exception = null;
    /**
     * This will hold error message
     * @var null
     */
    private $errorMessage = null;

    /**
     * This is the name of the custom data type, default will be data; e.g: posts/items
     * @var string
     */
    private $resourceType = 'results';

    /**
     * This will responsible for the created resource id name;
     * e.g: if we create a book response will return book_id, default name is resource_id
     * @var string
     */
    private $resourceIdName = 'resource_id';

    /**
     * Total count of the resource
     * @var int
     */
    private $resourceCount = 0;

    /**
     * This will hold the created resource id
     * @var null
     */
    private $resourceId = null;

    /**
     * This will hold the pagination data or paginate object
     * @var array
     */
    private $paginatedData = [];

    /**
     * This will hold if the json response will be JSON_NUMERIC_CHECK is enabled
     * @var bool
     */
    private $jsonNumericCheck = true;

    //#################################### Getters and setters #######################################################//


    /**
     * This method will return the http status code
     * @return int
     */
    public function getStatusCode()
    {
        return $this->httpStatusCode;
    }

    /**
     * This method will allow to set the valid HTTP status code
     * Note: If the HTTP status code is invalid, it will throw an exception
     * @param int $httpStatusCode
     * @return $this
     * @throws \Exception
     */
    public function setStatusCode($httpStatusCode)
    {
        if (!array_key_exists($httpStatusCode, $this->codes())) {
            throw new \Exception('You must provide valid HTTP status code!');
        } else {
            $this->httpStatusCode = $httpStatusCode;
        }
        return $this;
    }

    /**
     * This method will return the custom status code
     * @return null
     */
    public function getCustomStatusCode()
    {
        return $this->customStatusCode;
    }

    /**
     * This method will allow to set the custom code to the response
     * Note: default is null
     * @param null $customStatusCode
     * @return $this
     */
    public function setCustomStatusCode($customStatusCode)
    {
        $this->customStatusCode = $customStatusCode;
        return $this;
    }

    /**
     * This method will return the HTTP headers
     * @return array
     */
    public function getHeader()
    {
        return $this->httpHeader;
    }

    /**
     * This method will allow to set HTTP headers to response
     * @param array $httpHeaders
     * @return $this
     */
    public function setHeader(array $httpHeaders)
    {
        $this->httpHeader = $httpHeaders;
        return $this;
    }

    /**
     * This method  will return the error code
     * @return int
     */
    public function getErrorCode()
    {
        return $this->errorCode;
    }

    /**
     * This method will allow to set the error code to the response
     * @param int $errorCode
     * @return $this
     */
    public function setErrorCode($errorCode)
    {
        $this->errorCode = $errorCode;
        return $this;
    }

    /**
     * This will simply return the error message
     * @return null
     */
    public function getErrorMessage()
    {
        return $this->errorMessage;
    }

    /**
     * This method will allow to set custom message for error / validation error
     * @param null $errorMessage
     * @return $this
     */
    public function setErrorMessage($errorMessage)
    {
        $this->errorMessage = $errorMessage;
        return $this;
    }

    /**
     * This method will return the exception
     * @return null
     */
    public function getException()
    {
        return $this->exception;
    }

    /**
     * This method will allow to set the exception message
     * Note: This message will be available in response when APP_DEBUG is true
     * @param null $exception
     * @return $this
     */
    public function setException($exception)
    {
        $this->exception = $exception;
        return $this;
    }

    /**
     * This method will return the resource type name
     * @return string
     */
    public function getResourceType()
    {
        return $this->resourceType;
    }

    /**
     * This method will allow to set resource type name; default is "result"
     * @param string $resourceType
     * @return $this
     */
    public function setResourceType($resourceType)
    {
        $this->resourceType = $resourceType;
        return $this;
    }

    /**
     * This method will return the total resource count
     * @return int
     */
    public function getResourceCount()
    {
        return $this->resourceCount;
    }

    /**
     * This method will allow to set the total resource count
     * @param int $resourceCount
     * @return $this
     */
    public function setResourceCount($resourceCount)
    {
        if (is_numeric($resourceCount)) {
            $this->resourceCount = (int)$resourceCount;
        }
        return $this;
    }

    /**
     * This method will return the resourceIdName
     * @return string
     */
    public function getResourceIdName()
    {
        return $this->resourceIdName;
    }

    /**
     * @param string $resourceIdName
     * @return $this
     */
    public function setResourceIdName($resourceIdName)
    {
        $this->resourceIdName = $resourceIdName;
        return $this;
    }

    /**
     * This will return the created resource id
     * @return null
     */
    public function getResourceId()
    {
        return $this->resourceId;
    }

    /**
     * This will set the created resource id
     * @param null $resourceId
     * @return $this
     */
    public function setResourceId($resourceId)
    {
        $this->resourceId = (int)$resourceId;
        return $this;
    }

    /**
     * This method will return pagination data
     * @return array
     */
    public function getPaginatedData()
    {
        return $this->paginatedData;
    }

    /**
     * This will be responsible for setting paginate object to the paginated collection
     * @param array $paginatedData
     * @return $this
     */
    public function setPaginatedData(array $paginatedData)
    {
        if (is_array($paginatedData)) {
            $paginate = [
                'current' => (int)$paginatedData['current_page'],
                'total' => (int)$paginatedData['total'],
                'per_page' => (int)$paginatedData['per_page'],
                'last_page' => (int)$paginatedData['last_page']

            ];
            $this->paginatedData = $paginate;
        }
        return $this;
    }

    /**
     * This will responsible for the  JSON_NUMERIC_CHECK if it is true; default is true
     * @return boolean
     */
    public function isJsonNumericCheck()
    {
        return $this->jsonNumericCheck;
    }

    /**
     * @param boolean $jsonNumericCheck
     * @return $this
     */
    public function setJsonNumericCheck($jsonNumericCheck)
    {
        $this->jsonNumericCheck = $jsonNumericCheck;
        return $this;
    }
    //#################################### Helper methods ##################################################//

    /**
     * This method is containing the standard http status codes
     * @return array
     */
    private function codes()
    {
        $a_http_status_codes = array(
            100 => 'Continue', 102 => 'Processing',
            200 => 'OK', 201 => 'Created', 202 => 'Accepted', 203 => 'Non-Authoritative Information', 204 => 'No Content',
            205 => 'Reset Content', 206 => 'Partial Content', 207 => 'Multi-Status', 208 => 'Already Reported',
            226 => 'IM Used', 300 => 'Multiple Choices', 301 => 'Moved Permanently', 302 => 'Found', 303 => 'See Other',
            304 => 'Not Modified', 305 => 'Use Proxy', 307 => 'Temporary Redirect', 308 => 'Permanent Redirect',
            400 => 'Bad Request', 401 => 'Unauthorized', 402 => 'Payment Required', 403 => 'Forbidden', 404 => 'Not Found',
            405 => 'Method Not Allowed', 406 => 'Not Acceptable', 407 => 'Proxy Authentication Required', 408 => 'Request Timeout',
            409 => 'Conflict', 410 => 'Gone', 411 => 'Length Required', 412 => 'Precondition Failed',
            413 => 'Request Entity Too Large', 414 => 'Request-URI Too Long', 415 => 'Unsupported Media Type',
            416 => 'Requested Range Not Satisfiable', 417 => 'Expectation Failed', 421 => 'Misdirected Request',
            422 => 'Unprocessable Entity', 423 => 'Locked', 424 => 'Failed Dependency', 426 => 'Upgrade Required',
            428 => 'Precondition Required', 429 => 'Too Many Requests', 431 => 'Request Header Fields Too Large',
            451 => 'Unavailable For Legal Reasons', 500 => 'Internal Server Error', 501 => 'Not Implemented',
            502 => 'Bad Gateway', 503 => 'Service Unavailable', 504 => 'Gateway Timeout', 505 => 'HTTP Version Not Supported',
            506 => 'Variant Also Negotiates', 507 => 'Insufficient Storage', 508 => 'Loop Detected',
            510 => 'Not Extended', 511 => 'Network Authentication Required'
        );
        return $a_http_status_codes;
    }

    //#################################### Response/Respond methods ##################################################//

    /**
     * This will be the main responder or response maker
     * @param $data
     * @return mixed
     */
    public function respond($data)
    {
        if ($this->isJsonNumericCheck()) {
            return response()->json($data, $this->httpStatusCode, $this->httpHeader, JSON_NUMERIC_CHECK);
        }

        return response()->json($data, $this->httpStatusCode, $this->httpHeader);
    }


    /**
     * This will responsible for responding bad request
     * @param string $message
     * @return mixed
     */
    public function respondBadRequest($message = 'Bad Request!')
    {
        if (!!$this->customStatusCode) {
            return $this->setCustomStatusCode($this->customStatusCode)->respondWithError($message);
        }
        return $this->setCustomStatusCode(4000)->respondWithError($message);
    }

    /**
     * This is responsible for responding server error
     * @param string $message
     * @return mixed
     */
    public function respondServerError($message = 'Server Error!')
    {
        if (!!$this->customStatusCode) {
            return $this->setCustomStatusCode($this->customStatusCode)->respondWithError($message);
        }
        return $this->setCustomStatusCode(5000)->respondWithError($message);
    }

    /**
     * This will respond for conflicting error response
     * @param string $message
     * @return mixed
     */
    public function respondConflict($message = 'Conflict!')
    {
        if (!!$this->customStatusCode) {
            return $this->setCustomStatusCode($this->customStatusCode)->respondWithError($message);
        }
        return $this->setCustomStatusCode(4009)->respondWithError($message);
    }

    /**
     * This will respond when processing error
     * @param string $message
     * @return mixed
     */
    public function respondProcessingError($message = 'Error in processing!')
    {
        if (!!$this->customStatusCode) {
            return $this->setCustomStatusCode($this->customStatusCode)->respondWithError($message);
        }
        return $this->setCustomStatusCode(4022)->respondWithError($message);
    }

    /**
     * This will respond for unauthorized response
     * @param string $message
     * @return mixed
     */
    public function respondUnauthorized($message = 'Unauthorized!')
    {
        if (!!$this->customStatusCode) {
            return $this->setCustomStatusCode($this->customStatusCode)->respondWithError($message);
        }
        return $this->setCustomStatusCode(4001)->respondWithError($message);
    }

    /**
     * This will responsible for responding forbidden error
     * @param string $message
     * @return mixed
     */
    public function respondForbidden($message = 'Forbidden!')
    {
        if (!!$this->customStatusCode) {
            return $this->setCustomStatusCode($this->customStatusCode)->respondWithError($message);
        }
        return $this->setCustomStatusCode(4003)->respondWithError($message);
    }

    /**
     * This method will be responsible for any successful respond
     * @param $message
     * @return mixed
     */
    public function respondWithSuccess($message = '')
    {
        return $this->respond([
            'success' => [
                'message' => $message
            ],
            'status' => (!!$this->customStatusCode) ? $this->customStatusCode : 2000
        ]);
    }

    /**
     * This method will be responsible for any error respond
     * @param $message
     * @return mixed
     */
    public function respondWithError($message = null)
    {
        $data['error']['message'] = $message;
        if ($this->errorCode) {
            $data['error']['error_code'] = $this->errorCode;
        }
        if (env('APP_DEBUG', 'false')) {
            $data['error']['error_details'] = (!!$this->exception) ? $this->exception : null;
            //log the exception for monitoring
            Log::error($this->exception);
        }
        $data['status'] = $this->customStatusCode;
        return $this->respond($data);
    }

    /**
     * This method will responsible for the successful response of a resource creation
     * @param string $message
     * @return mixed
     */
    public function respondWithCreated($message = "Created successfully")
    {
        $respond['success'] = [
            'message' => $message
        ];
        if (!!$this->resourceId) {
            $respond['success'][$this->resourceIdName] = $this->resourceId;
        }
        $respond['status'] = (!!$this->customStatusCode) ? $this->customStatusCode : 2000;
        return $this->respond($respond);
    }

    public function respondWithCreatedItem($data, $message = "Created successfully")
    {
        $respond['success'] = [
            'message' => $message
        ];
        if (!!$this->resourceId) {
            $respond['success'][$this->resourceIdName] = $this->resourceId;
        }
        $respond['status'] = (!!$this->customStatusCode) ? $this->customStatusCode : 2000;
        $respond['payload'] = $data;
        return $this->respond($respond);
    }

    /**
     * This method will respond with an item
     * @param $item
     * @return mixed
     */
    public function respondWithItem(array $item)
    {
        if ($this->resourceType !== 'results') {
            $data[self::RESOURCE][$this->resourceType] = $item;
        } else {
            $data[self::RESOURCE] = $item;
        }
        $data['status'] = (!!$this->customStatusCode) ? $this->customStatusCode : 2000;
        return $this->respond($data);
    }

    /**
     * This method is responsible for responding a collection of items
     * @param array $items
     * @return mixed
     */
    public function respondWithCollection(array $items)
    {
        if (!!$this->resourceCount) {
            $data[self::RESOURCE]['total_count'] = $this->resourceCount;
        }
        if (!!$this->resourceType) {
            $data[self::RESOURCE][$this->resourceType] = $items;
        } else {
            $data[self::RESOURCE] = $items;
        }
        if (!!$this->paginatedData) {
            if (!!$this->resourceType) {
                $data[self::RESOURCE]['paginate'] = $this->paginatedData;
            } else {
                $data['paginate'] = $this->paginatedData;
            }
        }
        $data['status'] = (!!$this->customStatusCode) ? $this->customStatusCode : 2000;
        return $this->respond($data);
    }

    /**
     * This method is responsible for raising entity not found
     * @param string $message
     * @return mixed
     */
    public function respondWithNotFound($message = 'Not found!')
    {
        if (!!$this->customStatusCode) {
            return $this->setCustomStatusCode($this->customStatusCode)->respondWithError($message);
        }
        return $this->setCustomStatusCode(4004)->respondWithError($message);
    }

    /**
     * This will responsible for request validation
     * @param $input
     * @param $rules
     * @param array $message
     * @return mixed
     */
    public function validateInput($input, $rules, $message = [])
    {
        return Validator::make($input, $rules, $message);
    }

    /**
     * This method is responsible for for validation
     * @param $error
     * @return mixed
     */
    public function respondWithValidationError($error)
    {
        if ($this->errorMessage) {
            $data['error']['message'] = $this->errorMessage;
        }
        $data['error']['errors'] = $error;
        if (!!$this->errorCode) {
            $data['error']['error_code'] = $this->errorCode;
        }
        $data['status'] = (!!$this->customStatusCode) ? $this->customStatusCode : 4000;
        return $this->respond($data);
    }
}
