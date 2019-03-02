<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: kubernetes.proto

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;
use Google\Protobuf\Internal\GPBWrapperUtils;

/**
 * Generated from protobuf message <code>Database</code>
 */
class Database extends \Google\Protobuf\Internal\Message
{
    /**
     * Generated from protobuf field <code>string name = 1;</code>
     */
    private $name = '';
    /**
     * Generated from protobuf field <code>string label = 2;</code>
     */
    private $label = '';
    /**
     * Generated from protobuf field <code>string db_username = 3;</code>
     */
    private $db_username = '';
    /**
     * Generated from protobuf field <code>string db_password = 4;</code>
     */
    private $db_password = '';
    /**
     * Generated from protobuf field <code>string db_database = 5;</code>
     */
    private $db_database = '';

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type string $name
     *     @type string $label
     *     @type string $db_username
     *     @type string $db_password
     *     @type string $db_database
     * }
     */
    public function __construct($data = NULL) {
        \GPBMetadata\Kubernetes::initOnce();
        parent::__construct($data);
    }

    /**
     * Generated from protobuf field <code>string name = 1;</code>
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Generated from protobuf field <code>string name = 1;</code>
     * @param string $var
     * @return $this
     */
    public function setName($var)
    {
        GPBUtil::checkString($var, True);
        $this->name = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>string label = 2;</code>
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Generated from protobuf field <code>string label = 2;</code>
     * @param string $var
     * @return $this
     */
    public function setLabel($var)
    {
        GPBUtil::checkString($var, True);
        $this->label = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>string db_username = 3;</code>
     * @return string
     */
    public function getDbUsername()
    {
        return $this->db_username;
    }

    /**
     * Generated from protobuf field <code>string db_username = 3;</code>
     * @param string $var
     * @return $this
     */
    public function setDbUsername($var)
    {
        GPBUtil::checkString($var, True);
        $this->db_username = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>string db_password = 4;</code>
     * @return string
     */
    public function getDbPassword()
    {
        return $this->db_password;
    }

    /**
     * Generated from protobuf field <code>string db_password = 4;</code>
     * @param string $var
     * @return $this
     */
    public function setDbPassword($var)
    {
        GPBUtil::checkString($var, True);
        $this->db_password = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>string db_database = 5;</code>
     * @return string
     */
    public function getDbDatabase()
    {
        return $this->db_database;
    }

    /**
     * Generated from protobuf field <code>string db_database = 5;</code>
     * @param string $var
     * @return $this
     */
    public function setDbDatabase($var)
    {
        GPBUtil::checkString($var, True);
        $this->db_database = $var;

        return $this;
    }

}

