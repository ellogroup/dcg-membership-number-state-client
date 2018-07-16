<?php

namespace Dcg\Client\MembershipNumberState\Config;
use Dcg\Client\MembershipNumberState\Exception\ConfigFileNotFoundException;
use Dcg\Client\MembershipNumberState\Exception\ConfigValueNotFoundException;

class Config {
    
    /**
     * @var array
     */
    private static $configFilePath = 'config/membership-number-state-client.php';

    /**
     * @var array
     */
    protected static $configValues = [];

    /**
     * @var self
     */
    protected static $instance = null;

    /**
     * singleton: return self
     *
     * @return self
     */
    public static function getInstance() {
        if (is_null(self::$instance)) {
            self::setInstance();
        }
        self::init();

        return self::$instance;
    }

    /**
     *  Set singleton instance
     */
    private static function setInstance () 
    {
        self::$instance = new self();
    }

    /**
     *  Get values from config file
     */
    private static function init ()
    {
        self::configFileToArray();
    }

    /**
     * Get values from config file
	 * @throws ConfigFileNotFoundException
     */
    private static function configFileToArray ()
    {  
        $filePath = self::$configFilePath;

        if (file_exists($filePath)) {
            self::$configValues = require $filePath;
        } else {
            throw new ConfigFileNotFoundException("Config file could not be found at: ".$filePath);
        }
    }

    /**
     *  Gets the values that were in the config
     * @return array
     */
    public static function getConfigValues ()
    {
        return self::$configValues;
    }

    /**
     * Gets specific key fom config
	 *
	 * @param string $key	The config value identifier
	 * @param string $default (Optional) The default value if the config value is not set
	 * @throws ConfigValueNotFoundException
     * @return string
     */
    public static function get($key, $default = null)
    {
        if (isset(self::$configValues[$key])) {
            return self::$configValues[$key];
        } elseif ($default !== null) {
        	return $default;
		} else {
        	throw new ConfigValueNotFoundException("The config value was not found: ".$key);
		}
    }

}