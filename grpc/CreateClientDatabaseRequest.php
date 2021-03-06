<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: kubernetes.proto

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;
use Google\Protobuf\Internal\GPBWrapperUtils;

/**
 * Generated from protobuf message <code>CreateClientDatabaseRequest</code>
 */
class CreateClientDatabaseRequest extends \Google\Protobuf\Internal\Message
{
    /**
     * Generated from protobuf field <code>.Database database = 1;</code>
     */
    private $database = null;
    /**
     * Generated from protobuf field <code>.DatabaseService database_service = 2;</code>
     */
    private $database_service = null;
    /**
     * Generated from protobuf field <code>.DatabasePvc database_pvc = 3;</code>
     */
    private $database_pvc = null;
    /**
     * Generated from protobuf field <code>.DatabaseNodePort database_node_port = 4;</code>
     */
    private $database_node_port = null;
    /**
     * Generated from protobuf field <code>string configData = 5;</code>
     */
    private $configData = '';

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type \Database $database
     *     @type \DatabaseService $database_service
     *     @type \DatabasePvc $database_pvc
     *     @type \DatabaseNodePort $database_node_port
     *     @type string $configData
     * }
     */
    public function __construct($data = NULL) {
        \GPBMetadata\Kubernetes::initOnce();
        parent::__construct($data);
    }

    /**
     * Generated from protobuf field <code>.Database database = 1;</code>
     * @return \Database
     */
    public function getDatabase()
    {
        return $this->database;
    }

    /**
     * Generated from protobuf field <code>.Database database = 1;</code>
     * @param \Database $var
     * @return $this
     */
    public function setDatabase($var)
    {
        GPBUtil::checkMessage($var, \Database::class);
        $this->database = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>.DatabaseService database_service = 2;</code>
     * @return \DatabaseService
     */
    public function getDatabaseService()
    {
        return $this->database_service;
    }

    /**
     * Generated from protobuf field <code>.DatabaseService database_service = 2;</code>
     * @param \DatabaseService $var
     * @return $this
     */
    public function setDatabaseService($var)
    {
        GPBUtil::checkMessage($var, \DatabaseService::class);
        $this->database_service = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>.DatabasePvc database_pvc = 3;</code>
     * @return \DatabasePvc
     */
    public function getDatabasePvc()
    {
        return $this->database_pvc;
    }

    /**
     * Generated from protobuf field <code>.DatabasePvc database_pvc = 3;</code>
     * @param \DatabasePvc $var
     * @return $this
     */
    public function setDatabasePvc($var)
    {
        GPBUtil::checkMessage($var, \DatabasePvc::class);
        $this->database_pvc = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>.DatabaseNodePort database_node_port = 4;</code>
     * @return \DatabaseNodePort
     */
    public function getDatabaseNodePort()
    {
        return $this->database_node_port;
    }

    /**
     * Generated from protobuf field <code>.DatabaseNodePort database_node_port = 4;</code>
     * @param \DatabaseNodePort $var
     * @return $this
     */
    public function setDatabaseNodePort($var)
    {
        GPBUtil::checkMessage($var, \DatabaseNodePort::class);
        $this->database_node_port = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>string configData = 5;</code>
     * @return string
     */
    public function getConfigData()
    {
        return $this->configData;
    }

    /**
     * Generated from protobuf field <code>string configData = 5;</code>
     * @param string $var
     * @return $this
     */
    public function setConfigData($var)
    {
        GPBUtil::checkString($var, True);
        $this->configData = $var;

        return $this;
    }

}

