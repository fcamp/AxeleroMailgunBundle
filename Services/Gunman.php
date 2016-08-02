<?php
namespace Axelero\MailgunBundle\Services;


use Mailgun\Mailgun;
use Mailgun\Messages\BatchMessage;
use Mailgun\Messages\MessageBuilder;

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

    /**
     * Batch sends the message
     * @param string $from
     * @param string $subject
     * @param string $body
     * @param array $recipients . Each item has to be ['email' => 'mail@address.com','first' => 'userFirstname', 'last' => 'userLastname']
     */
    public function batchSend($from, $subject, $body, array $recipients)
    {
        /**
         * @var $bb BatchMessage
         */
        $bb = $this->gun->BatchMessage($this->domain);
        $bb->setFromAddress($from);
        $bb->setSubject($subject);
        $bb->setHtmlBody($body);

        $bb->setTextBody($this->cleanUp($body));

        foreach ($recipients as $r) {
            $bb->addToRecipient($r['email'], array_filter(['first' => $r['first'], 'last' => $r['last']]));
        }

        $bb->finalize();
    }

    /**
     * Just a simple html/css clean utility
     * @param string $body
     * @return mixed|string
     */
    private function cleanUp($body)
    {
        $text = strip_tags($body, "<style>");
        $substring = substr($text, strpos($text, "<style"), strpos($text, "</style>") + 2);

        $text = str_replace($substring, "", $text);
        $text = str_replace(array("\t", "\r", "\n"), "", $text);
        $text = trim($text);

        return $text;
    }
}