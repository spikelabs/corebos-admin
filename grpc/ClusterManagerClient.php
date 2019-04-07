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
         * @param \CreateClientDatabaseRequest $argument input argument
         * @param array $metadata metadata
         * @param array $options call options
         */
        public function CreateClientDatabase(\CreateClientDatabaseRequest $argument,
                                             $metadata = [], $options = []) {
            return $this->_simpleRequest('/ClusterManager/CreateClientDatabase',
                $argument,
                ['\ClientResponse', 'decode'],
                $metadata, $options);
        }

        /**
         * @param \CreateClientDeploymentRequest $argument input argument
         * @param array $metadata metadata
         * @param array $options call options
         */
        public function CreateClientDeployment(\CreateClientDeploymentRequest $argument,
                                               $metadata = [], $options = []) {
            return $this->_simpleRequest('/ClusterManager/CreateClientDeployment',
                $argument,
                ['\ClientResponse', 'decode'],
                $metadata, $options);
        }

    }
}
