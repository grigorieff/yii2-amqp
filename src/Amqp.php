<?php

/**
 * @link https://github.com/grigorieff/yii2-amqp
 */

namespace grigorieff\amqp;

use PhpAmqpLib\Connection\AMQPConnection;
use PhpAmqpLib\Message\AMQPMessage;
use yii\base\Component;
use yii\base\InvalidConfigException;

class Amqp extends Component
{
    /**
     * @var null|AMQPConnection
     */
    protected $connection = null;
    /**
     * @var null|\AMQPChannel
     */
    protected $channel = null;

    /**
     * @var string
     */
    public $host = 'localhost';
    /**
     * @var int
     */
    public $port = 5672;
    /**
     * @var string
     */
    public $user = 'guest';
    /**
     * @var string
     */
    public $password = 'guest';

    public function init()
    {
        parent::init();

        if (!$this->host) {
            throw new InvalidConfigException("Host can't be empty!");
        }

        if (!$this->port) {
            throw new InvalidConfigException("Port can't be empty!");
        }

        if (!$this->user) {
            throw new InvalidConfigException("User can't be empty!");
        }

        if (!$this->password) {
            throw new InvalidConfigException("Password can't be empty!");
        }


        $this->connection = new AMQPConnection($this->host, $this->port, $this->user, $this->password);
        $this->channel = $this->connection->channel();
    }

    /**
     * @return null|\AMQPConnection
     */
    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * @return \AMQPChannel|null
     */
    public function getChannel()
    {
        return $this->channel;
    }


    /**
     * Basic publish AMQP Message
     * @param $message
     * @param string $exchange
     * @param string $routingKey
     */
    public function basicPublish($message, $exchange = '', $routingKey = '')
    {
        $message = new AMQPMessage($message, ['delivery_mode' => 2]);
        $this->channel->basic_publish($message, $exchange, $routingKey);
    }

    /**
     * Declare AMQP Queue
     * @param string $queue
     * @param bool|false $passive
     * @param bool|false $durable
     * @param bool|false $exclusive
     * @param bool|true $auto_delete
     * @param bool|false $nowait
     * @param null $arguments
     * @param null $ticket
     * @return mixed
     */
    public function queueDeclare($queue='',$passive=false,$durable=false, $exclusive=false, $auto_delete=true, $nowait=false, $arguments=null, $ticket=null)
    {
        return $this->channel->queue_declare($queue, $passive, $durable, $exclusive, $auto_delete, $nowait, $arguments, $ticket);
    }

    /**
     * Declare AMQP Exchange
     * @param $name
     * @param string $type
     * @param bool|false $passive
     * @param bool|true $durable
     * @param bool|false $auto_delete
     * @return mixed
     */
    public function exchangeDeclare($name, $type = 'fanout', $passive = false, $durable = true, $auto_delete = false) {
        return $this->channel->exchange_declare($name, $type, $passive, $durable, $auto_delete);
    }

    /**
     * Binding queue
     * @param $queue
     * @param $exchange
     * @param string $routingKey
     */
    public function bindQueueExchange($queue, $exchange, $routingKey = '') {
        $this->_channel->queue_bind($queue, $exchange, $routingKey);
    }

    /**
     * Closed AMQP connection
     */
    public function closeConnection() {
        $this->channel->close();
        $this->connection->close();
    }

    /**
     * Delete AMQP Exchange
     * @param $name
     */
    public function exchangeDelete($name) {
        $this->channel->exchange_delete($name);
    }
}