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
     * @var Mailgun
     */
    private $gun;

    /**
     * @return Mailgun
     */
    public function getGun()
    {
        return empty($this->key) ? $this->gun : new Mailgun($this->key);
    }

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
     * @param array $recipients . Each item has to be ['email' => 'mail@address.com','first' => 'userFirstName', 'last' => 'userLastName']
     * @return \string[] Send Status
     */
    public function batchSend($from, $subject, $body, array $recipients)
    {
        /**
         * @var $bb BatchMessage
         */
        $gun = $this->getGun();
        $bb = $gun->BatchMessage($this->domain);
        $bb->setFromAddress($from);
        $bb->setSubject($subject);
        $bb->setHtmlBody($body);

        $bb->setTextBody($this->cleanUp($body));

        $notCustom = ['email', 'first', 'last'];

        foreach ($recipients as $recipent) {

            $bb->addToRecipient($recipent['email'], $recipent);

            //all the other fields are considered as custom data
            foreach ($this->array_filter_key($recipent, function ($i) use ($notCustom) {
                return array_search($i, $notCustom) === false;
            }) as $k => $v) {//
                $bb->addCustomData($k, $v);
            }
        }

        $bb->finalize();

        return $bb->getMessageIds();
    }

    /**
     * @param array $options
     * @return \stdClass
     */
    public function getEvents(array $options = [])
    {
        return $this->getGun()->get("{$this->domain}/events", $options);
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

    /**
     * Filtering a array by its keys using a callback.
     *
     * @param $array array The array to filter
     * @param $callback Callback The filter callback, that will get the key as first argument.
     *
     * @return array The remaining key => value combinations from $array.
     * @link https://gist.github.com/h4cc/8e2e3d0f6a8cd9cacde8
     */
    private function array_filter_key(array $array, $callback)
    {
        $matchedKeys = array_filter(array_keys($array), $callback);
        return array_intersect_key($array, array_flip($matchedKeys));
    }
}