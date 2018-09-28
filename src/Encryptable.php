<?php namespace Magros\Encryptable;

use Illuminate\Support\Facades\Config;

trait Encryptable
{

    public $aesKey = null;
    public $isEncrypted = true;
    private $prefix;

    public function getPrefix()
    {
        if(! $this->prefix){
            $this->prefix = Config::get('encrypt.prefix');
        }
        return $this->prefix;
    }

    public function getEncryptable()
    {
        return $this->encryptable;
    }

    /**
     * @param $val
     * @param $key
     * @return string
     */
    public function aesEncrypt($val, $key)
    {
        return openssl_encrypt($val, 'aes-128-ecb', $key, 0, $iv = '');
    }

    /**
     * @param $val
     * @param $key
     * @return string
     */
    public function aesDecrypt($val, $key)
    {
        return openssl_decrypt($val, 'aes-128-ecb', $key, 0, $iv = '');
    }

    /**
     * @return bool|null|string
     * @throws \Exception
     */
    public function getAescryptKey()
    {
        if ($this->aesKey === null) {
            if(! Config::get('encrypt.key')) throw new \Exception('The .env value ENCRYPT_KEY has to be set!!');
            $this->aesKey = substr(hash('sha256', Config::get('encrypt.key')), 0, 16);
        }
        return $this->aesKey;
    }

    /**
     * @param $key
     * @return bool
     */
    public function encryptable($key)
    {
        if($this->isEncrypted){
            return in_array($key, $this->encryptable);
        }
        return false;
    }

    /**
     * Decrypt a value.
     *
     * @param $value
     *
     * @return string
     * @throws \Exception
     */
    public function decryptAttribute($value)
    {
        $value = str_replace("{$this->getPrefix()}_",'',$value);

        $value = $this->aesDecrypt($value,$this->getAescryptKey());

        return $value;
    }


    /**
     * @param $value
     * @return string
     * @throws \Exception
     */
    public function encryptAttribute($value)
    {
        $value = $this->aesEncrypt($value, $this->getAescryptKey());

        return "{$this->getPrefix()}_{$value}";
    }

    /**
     * @param $key
     * @return string
     * @throws \Exception
     */
    public function getAttribute($key)
    {
        $value = parent::getAttribute($key);

        if ($this->encryptable($key)) {

            $value = $this->decryptAttribute($value);
        }

        return $value;
    }


    /**
     * @param $key
     * @param $value
     * @return mixed
     * @throws \Exception
     */
    public function setAttribute($key, $value)
    {
        if ($this->encryptable($key)) {
            $value = $this->encryptAttribute($value);
        }

        return parent::setAttribute($key, $value);
    }


    /**
     * @return mixed
     * @throws \Exception
     */
    public function getArrayableAttributes()
    {
        $attributes = parent::getArrayableAttributes();

        foreach ($attributes as $key => $attribute) {
            if ($this->encryptable($key)) {
                $attributes[$key] = $this->decryptAttribute($attribute);
            }
        }

        return $attributes;
    }

    /**
     * @return EncryptableQueryBuilder
     */
    public function newBaseQueryBuilder()
    {
        $connection = $this->getConnection();

        return new EncryptableQueryBuilder($connection, $this);
    }
}