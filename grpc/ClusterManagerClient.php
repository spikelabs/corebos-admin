<?php
// GENERATED CODE -- DO NOT EDIT!

namespace {

    /**
     */
    class ClusterManagerClient extends \Grpc\BaseStub {

        /**
         * @param string $hostname hostname
         * @param array $opts channel options
         * @param \Grpc\Channel $channel (optional) re-use channel object
         */
        public function __construct($hostname, $opts, $channel = null) {
            parent::__construct($hostname, $opts, $channel);
        }

        /**
         * @param \CreateClientRequest $argument input argument
         * @param array $metadata metadata
         * @param array $options call options
         */
        public function CreateClient(\CreateClientRequest $argument,
                                     $metadata = [], $options = []) {
            return $this->_simpleRequest('/ClusterManager/CreateClient',
                $argument,
                ['\CreateClientResponse', 'decode'],
                $metadata, $options);
        }

    }
}
