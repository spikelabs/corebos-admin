<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: kubernetes.proto

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;
use Google\Protobuf\Internal\GPBWrapperUtils;

/**
 * Generated from protobuf message <code>UpdateClientIngressRequest</code>
 */
class UpdateClientIngressRequest extends \Google\Protobuf\Internal\Message
{
    /**
     * Generated from protobuf field <code>.Ingress ingress = 1;</code>
     */
    private $ingress = null;
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
     *     @type \Ingress $ingress
     *     @type string $configData
     * }
     */
    public function __construct($data = NULL) {
        \GPBMetadata\Kubernetes::initOnce();
        parent::__construct($data);
    }

    /**
     * Generated from protobuf field <code>.Ingress ingress = 1;</code>
     * @return \Ingress
     */
    public function getIngress()
    {
        return $this->ingress;
    }

    /**
     * Generated from protobuf field <code>.Ingress ingress = 1;</code>
     * @param \Ingress $var
     * @return $this
     */
    public function setIngress($var)
    {
        GPBUtil::checkMessage($var, \Ingress::class);
        $this->ingress = $var;

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
