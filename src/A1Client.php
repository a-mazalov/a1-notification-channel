<?php

namespace A1\Channel;

use A1\Channel\Exceptions\ExceptionApi;
use A1\Channel\Exceptions\ExceptionNotification;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;

class A1Client implements ClientInterface
{
    /**
     * Массив стандартных параметров, объединяется с пользовательскими
     *
     * @var array
     */
    protected $parameters;

    /**
     * Массив стандартных заголовков, объединяется с пользовательскими
     *
     * @var array
     */
    protected $headers;

    /**
     * Адрес API A1
     *
     * @var string
     */
    protected $url;

    /**
     * HTTP Client
     *
     * @var string
     */
    protected $httpClient;

    public function __construct()
    {
        $this->headers = [
            'headers' => [
                'Accept' => 'application/json',
            ]
        ];

        $this->parameters = [
            'query' => [
                "user" => config('a1.api_login'),
                "apikey" => config('a1.api_key'),
            ]
        ];

        $this->url = config('a1.api_endpoint');

        $this->httpClient = $this->makeHttpClient();
    }

    /**
     * Получение списка имен отправителей
     *
     * @return void
     */
    public function getSenderNames()
    {
        return $this->call('senderNames', 'GET');
    }

    /**
     * Получение списка имен отправителей
     *
     * @return void
     */
    public function sendSms($phoneNumber, $message, $senderName = null, $customMessageId = null, $sendOnDateTime = null)
    {
        return $this->call('send/sms', 'GET', [
            'msisdn' => $phoneNumber,
            'text' => $message,
            'sender' => $senderName,
            'custom_id' => $customMessageId,
            'send_on_datetime' => $sendOnDateTime
        ]);
    }

    /**
     * Получение стоимости SMS-сообщения
     *
     * @param string $phoneNumber
     * @param string $message
     * @return void
     */
    public function getCostSms($phoneNumber, $message)
    {
        return $this->call('cost/sms', 'GET', [
            'msisdn' => $phoneNumber,
            'text' => $message,
        ]);
    }

    /**
     * Пакетная отправка SMS-сообщений
     *
     * Производит пакетную отправку SMS-сообщений. Максимальное кол-во сообщений в пакете – 500 шт. Поля элементов массива messages в запросе:
     *
     * msisdn – номер получателя сообщения
     * text – текст сообщения
     * sender – имя отправителя
     * custom_id – пользовательский идентификатор для сообщения, который возвращается в ответе на данный запрпос. Строка длиной до 20 символов
     * send_on_datetime - желаемое время фактической отправки сообщения в формате ГГГГ-ММ-ДД чч:мм:00. Например: 2020-03-30 12:30:00
     *
     * $messages = [
     *    "msisdn" => "39622926331",
     *    "text" => "test message",
     *    "sender" => "MyCompany",
     *    "custom_id" => "123"
     * ]
     *
     * @return void
     *
     */
    public function sendBulkSms(array $messages)
    {
        return $this->call('sendBulk/sms', 'POST', [
            'messages' => json_encode($messages),
        ]);
    }

    /**
     * Пакетное получение стоимости SMS-сообщений
     *
     * Производит пакетную оценку стоимости СМС-сообщений. Максимальное кол-во сообщений в пакете – 500 шт.
     * Поля элементов массива messages в запросе:
     *      msisdn – номер получателя сообщения
     *      text – текст сообщения
     *
     * $messages = [
     *    "msisdn" => "39622926331",
     *    "text" => "test message",
     * ]
     *
     * @param array $messages
     * @return void
     */
    public function getCostBulkSms(array $messages)
    {
        return $this->call('costBulk/sms', 'POST', [
            'messages' => json_encode($messages),
        ]);
    }

    /**
     * Получение статуса SMS-сообщения
     *
     * @param string $messageId
     * @return void
     */
    public function getStatusSms($messageId)
    {
        return $this->call('status/sms', 'GET', [
            'message_id' => $messageId,
        ]);
    }

    /**
     * Получение статуса SMS-сообщения с собственным ID
     *
     * @param string $customMessageId
     * @return void
     */
    public function getCustomStatusSms($customMessageId)
    {
        return $this->call('status/sms', 'GET', [
            'message_id' => $customMessageId,
        ]);
    }

    public function makeHttpClient()
    {
        return Http::baseUrl($this->url);
    }

    /**
     * Вызвать API
     *
     * @param string url
     * @param string method = 'GET'
     * @param array options
     * @return collection
     */
    public function call($url, $method = 'GET',  $params = [], $headers = [])
    {
        $options = array_merge(
            $this->makeHeaders($headers),
            $this->makeParameters($params)
        );

        try {
            $response = $this->httpClient->send($method, $url, $options)->throw()->json();

            if (empty($response)) {
                throw ExceptionNotification::serviceEmptyResponse();
            }

            if ($response['status'] === false) {
                throw ExceptionApi::serviceRespondedWithAnError($response);
            }

            return $response;
        } catch (RequestException $exception) {
            throw $exception;
        }
    }

    /**
     * Создает массив параметров запроса
     *
     * @param array
     * @return array
     */
    public function makeParameters($params = [])
    {
        $mergedParams = [
            'query' => array_merge($this->parameters['query'], $params)
        ];

        return $mergedParams;
    }

    /**
     * Создает массив заголовков
     *
     * @param array headers
     * @return array
     */
    public function makeHeaders($headers = [])
    {
        $mergedHeaders = [
            'headers' => array_merge($this->headers['headers'], $headers)
        ];

        return $mergedHeaders;
    }
}
