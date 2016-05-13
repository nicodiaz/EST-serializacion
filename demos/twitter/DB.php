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
     * to the receiver
     */
    public function getTweets()
    {
        $conn = $this->getConnection();
        
        $stmt = $conn->query('
			SELECT raw 
			FROM tweets 
		');

        $data = $stmt->fetch();
        
        $results = array();
        
        foreach ($data as $value) 
        {
            $results[] = unserialize($value);
        }
        
        return $results;
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
    public function saveTweet($tweet)
    {
        // Preconditions
        if (empty($tweet)) {
            throw new \InvalidArgumentException("Error: Empty tweet cannot be saved");
        }
        
        $conn = $this->getConnection();
        
        $st = $conn->prepare('
            INSERT INTO tweets (date, texto, username, imageurl, raw) 
            VALUES(:date, :texto, :username, :imageurl, :raw)
        ');
        
        return $st->execute(array(
            ':date' => gmdate('Y-m-d H:i:s', strtotime($tweet->created_at)),
            ':texto' => $tweet->text,
            ':username' => $tweet->user->name,
            ':imageurl' => $tweet->user->profile_image_url,
            ':raw' => serialize($tweet),
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
