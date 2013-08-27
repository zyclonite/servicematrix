<?php

class WsController extends CController {

    public function actions() {
        return array(
            'soap' => array(
                'class' => 'CWebServiceAction',
                'classMap' => array(
                    'Services' => 'SoapService',
                    'Servicetypes' => 'Servicetypes',
                    'Connections' => 'SoapConnection',
                    'Connectiontypes' => 'Connectiontypes',
                ),
            ),
        );
    }

    private function httpAuth($rights) {
        $identity = new UserIdentity($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']);
        if (!$identity->authenticate()) {
            throw new SOAPFault('Unauthorized', 401);
        }
        if ($identity->getState('rights') < $rights) {
            throw new SOAPFault('Forbidden', 403);
        }
    }

    /**
     * @param SoapService $Service
     * @return boolean Success is true if everything was successful
     * @soap
     */
    public function createService($service) {
        $this->httpAuth(2);
        if (isset($service->Name) && isset($service->Type)) {
            $model = new Services;
            $model->servicename = $service->Name;
            $type = Servicetypes::model()->find('`name` LIKE :s1', array(':s1' => $service->Type));
            if (!is_object($type)) {
                $type = Servicetypes::model()->find();
            }
            $model->type = $type->id;
            return $model->save();
        } else {
            return false;
        }
    }

    /**
     * @param SoapConnection $Connection
     * @return boolean Success is true if everything was successful
     * @soap
     */
    public function createConnection($connection) {
        $this->httpAuth(2);
        if (isset($connection->From) && isset($connection->To) && isset($connection->Type)) {
            $model = new Connections;
            $services = Services::model()->findAll('`servicename` LIKE :s1 OR `servicename` LIKE :s2', array(':s1' => $connection->From, ':s2' => $connection->To));
            if (count($services) == 2) {
                foreach ($services as $service) {
                    if (stristr($service->servicename, $connection->From)) {
                        $model->from = $service->id;
                    } else {
                        $model->to = $service->id;
                    }
                }
            }
            $type = Connectiontypes::model()->find('`name` LIKE :s1', array(':s1' => $connection->Type));
            if (!is_object($type)) {
                $type = Connectiontypes::model()->find();
            }
            $model->type = $type->id;
            if ($connection->Percent > 100 || $connection->Percent < 0) {
                $connection->Percent = 100;
            }
            $model->percent = $connection->Percent;
            return $model->save();
        } else {
            return false;
        }
    }

    /**
     * @param SoapService[] $Services
     * @return int Count is number of successful inserted rows
     * @soap
     */
    public function createServices($services) {
        $this->httpAuth(2);
        $count = 0;
        foreach ($services as $service) {
            if ($this->createService($service)) {
                $count++;
            }
        }
        return $count;
    }

    /**
     * @param SoapConnection[] $Connections
     * @return int Count is number of successful inserted rows
     * @soap
     */
    public function createConnections($connections) {
        $this->httpAuth(2);

        $count = 0;
        foreach ($connections as $connection) {
            if ($this->createConnection($connection)) {
                $count++;
            }
        }
        return $count;
    }

    /**
     * @param string $Name use % for wildcard search
     * @param string $Type use % for wildcard search
     * @return SoapService[] a list of services
     * @soap
     */
    public function getServices($name = '', $type = '') {
        $this->httpAuth(1);

        if ($name == '')
            $name = '%';
        if ($type == '')
            $type = '%';

        if ($name == '%' && $type == '%') {
            return SoapService::getAll();
        } else {
            return SoapService::searchAll($name, $type);
        }
    }

    /**
     * @return Servicetypes[] a list of servicetypes
     * @soap
     */
    public function getServicetypes() {
        $this->httpAuth(1);
        return Servicetypes::model()->findAll();
    }

    /**
     * @param string $From use % for wildcard search
     * @param string $To use % for wildcard search
     * @param string $Percent use > or < for search
     * @param string $Type use % for wildcard search
     * @return SoapConnection[] a list of connections
     * @soap
     */
    public function getConnections($from = '', $to = '', $percent = '', $type = '') {
        $this->httpAuth(1);

        if ($from == '')
            $from = '%';
        if ($to == '')
            $to = '%';
        if ($percent == '')
            $percent = '<101';
        if ($type == '')
            $type = '%';

        if ($from == '%' && $to == '%' && $percent == '<101' && $type == '%') {
            return SoapConnection::getAll();
        } else {
            return SoapConnection::searchAll($from, $to, $percent, $type);
        }
    }

    /**
     * @return Connectiontypes[] a list of connectiontypes
     * @soap
     */
    public function getConnectionTypes() {
        $this->httpAuth(1);
        return ConnectionTypes::model()->findAll();
    }

}
