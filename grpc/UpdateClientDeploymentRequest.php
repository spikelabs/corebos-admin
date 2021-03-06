<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: kubernetes.proto

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;
use Google\Protobuf\Internal\GPBWrapperUtils;

/**
 * Generated from protobuf message <code>UpdateClientDeploymentRequest</code>
 */
class UpdateClientDeploymentRequest extends \Google\Protobuf\Internal\Message
{
    /**
     * Generated from protobuf field <code>.Deployment deployment = 1;</code>
     */
    private $deployment = null;
    /**
     * Generated from protobuf field <code>string configData = 2;</code>
     */
    private $configData = '';

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type \Deployment $deployment
     *     @type string $configData
     * }
     */
    public function __construct($data = NULL) {
        \GPBMetadata\Kubernetes::initOnce();
        parent::__construct($data);
    }

    /**
     * Generated from protobuf field <code>.Deployment deployment = 1;</code>
     * @return \Deployment
     */
    public function getDeployment()
    {
        return $this->deployment;
    }

    /**
     * Generated from protobuf field <code>.Deployment deployment = 1;</code>
     * @param \Deployment $var
     * @return $this
     */
    public function setDeployment($var)
    {
        GPBUtil::checkMessage($var, \Deployment::class);
        $this->deployment = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>string configData = 2;</code>
     * @return string
     */
    public function getConfigData()
    {
        return $this->configData;
    }

    /**
     * Generated from protobuf field <code>string configData = 2;</code>
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

