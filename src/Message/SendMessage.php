<?php
declare(strict_types=1);

namespace App\Message;

/**
 * Changes:
 *  - Added Text attribute to the SendMessage class to be able to handle the actual text of the message
 */
class SendMessage
{
    /**
     * @var string
     */
    private readonly string $text;

    /**
     * @param string $text
     */
    public function __construct(string $text)
    {
        $this->text = $text;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }
}