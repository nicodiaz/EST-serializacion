<?php

class DB
{

    const CHARSET_TYPE = 'UTF-8';

    private $_config;

    /**
     * The constructor of the class receive an array containing the following indexes:
     *
     * 'host': database the register the emails
     * 'port': the port of the database
     * 'user': the username of the database
     * 'password': the password of the database
     * 'dbname': the name of the database
     *
     * @param array $config            
     */
    public function __construct(array $config)
    {
        $keys1 = array(
            'host',
            'port',
            'user',
            'password',
            'dbname'
        );
        
        $this->_config = array_fill_keys($keys1, 'value');
        
        $keys2 = array_keys($config); // the user provided keys
        $diff = array_merge(array_diff_key($keys1, $keys2), array_diff_key($keys2, $keys1));
        
        if (! empty($diff)) {
            throw new \InvalidArgumentException("The config array in constructor is not complete (see the documentation of the constructor)");
        }
        
        // If reach here, everything is fine
        $this->_config = $config;
    }

    /**
     *
     * @var \PDO
     */
    protected $_pdoConnection;

    /**
     * Function to create (or retrieve) the current connection to the DB
     *
     * @throws Connection
     * @return \PDO
     */
    protected function getConnection()
    {
        if (empty($this->_pdoConnection)) {
            $result = $this->initConnection();
            
            if (is_string($result)) {
                throw new Connection($result);
            }
            
            // If reach here, everything was fine
            $this->_pdoConnection = $result;
        }
        
        return $this->_pdoConnection;
    }

    public function setConnection(\PDO $conn)
    {
        $this->_pdoConnection = $conn;
    }

    /**
     * Process the emails that are stored in the datasource and send them
     */
    public function processQueue()
    {
        $conn = $this->getConnection();
        
        $stmt = $conn->query('
			SELECT e.*, t.subject, t.body 
			FROM emails e INNER JOIN types t ON e.typeId = t.id 
			WHERE sent = 0
		');
        
        $data = $stmt->fetch();
        while (! empty($data)) {
            $data['body'] = $this->_replaceBodyParams($data['body'], $data['params']);
            
            $result = $this->sendMail($data['email'], $data['subject'], $data['body']);
            
            // If success, update the email as sent
            if ($result == true) {
                $conn->prepare('UPDATE emails SET sent = 1 WHERE id = :emailId')->execute(array(
                    ':emailId' => $data['id']
                ));
            }
            
            // Next value
            $data = $stmt->fetch();
        }
    }



    /**
     * Add an email to the queue to be processed by the cron job
     * Returns true on success or false on failure.
     *
     * This function doesn't work in transactional way, that must be implemented
     *
     * The params is replaced in the type text, with the placeholders '__?0__', '__?1__'
     *
     * @param string $email            
     * @param int $type            
     * @param unknown_type $params            
     *
     * @throws \InvalidArgumentException
     *
     * @return bool
     */
    public function enqueueEmail($email, $type = 1, $params = array())
    {
        // Preconditions
        if (empty($email) || empty($type) || $type <= 0) {
            throw new \InvalidArgumentException("Error adding an email to the queue: is empty or the type doesn't exists");
        }
        
        $conn = $this->getConnection();
        
        $params = json_encode($params);
        
        $st = $conn->prepare('INSERT INTO emails (typeId, email, params) VALUES(:typeId, :email, :params)');
        
        return $st->execute(array(
            ':typeId' => $type,
            ':email' => $email,
            ':params' => $params
        ));
    }

    /**
     * Function to create the (initial) connection to MySQL Database
     *
     * @return void
     */
    protected function initConnection()
    {
        $dsn = 'mysql:host=' . $this->_config['host'] . ';port=' . $this->_config['port'] . ';dbname=' . $this->_config['dbname'];
        $username = $this->_config['user'];
        $password = $this->_config['password'];
        $options = array(
            \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
        );
        
        $result = false;
        
        try {
            $result = new \PDO($dsn, $username, $password, $options);
        } catch (\PDOException $e) {
            $result = $e->getMessage();
        }
        
        return $result;
    }
}
