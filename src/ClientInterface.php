<?php

namespace A1\Channel;

interface ClientInterface
{
    /**
     * Получение списка имен отправителей
     *
     * @return void
     */
    public function getSenderNames();

    /**
     * Получение списка имен отправителей
     *
     * @return void
     */
    public function sendSms($phoneNumber, $message, $senderName = null, $customMessageId = null, $sendOnDateTime = null);

    /**
     * Получение стоимости SMS-сообщения
     *
     * @param string $phoneNumber
     * @param string $message
     * @return void
     */
    public function getCostSms($phoneNumber, $message);

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
    public function sendBulkSms(array $messages);

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
    public function getCostBulkSms(array $messages);

    /**
     * Получение статуса SMS-сообщения
     *
     * @param string $messageId
     * @return void
     */
    public function getStatusSms($messageId);

    /**
     * Получение статуса SMS-сообщения с собственным ID
     *
     * @param string $customMessageId
     * @return void
     */
    public function getCustomStatusSms($customMessageId);
}
