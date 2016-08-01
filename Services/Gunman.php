<?php
namespace Axelero\MailgunBundle\Services;


use Mailgun\Mailgun;

class Gunman
{
    /**
     * Mailgun Api Key
     * @var string
     */
    private $key;

    /**
     * Mailgun Api Domain
     * @var string
     */
    private $domain;

    /**
     * @vara Mailgun
     */
    private $gun;

    /**
     * Gunman constructor.
     * @param Mailgun $gun
     */
    function __construct(Mailgun $gun)
    {
        $this->gun = $gun;
    }

    /**
     * @param string $key
     * @return Gunman
     */
    public function setKey($key)
    {
        $this->key = $key;
        return $this;
    }


    /**
     * @param string $domain
     * @return Gunman
     */
    public function setDomain($domain)
    {
        $this->domain = $domain;
        return $this;
    }
}