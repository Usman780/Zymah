<?php

namespace GoDaddy\WordPress\MWC\Common\Email;

use GoDaddy\WordPress\MWC\Common\Email\Contracts\SendableContract;
use GoDaddy\WordPress\MWC\Common\Helpers\ArrayHelper;
use GoDaddy\WordPress\MWC\Common\Register\Register;

/**
 * Email class.
 */
class Email implements SendableContract
{
    /**
     * Recipient's email.
     *
     * @var string
     */
    protected $to;

    /**
     * Email subject.
     *
     * @var string
     */
    protected $subject;

    /**
     * Email body.
     *
     * @var string
     */
    protected $body;

    /**
     * Key-value array of headers.
     *
     * @var mixed
     */
    protected $headers = [];

    /**
     * Content type, sent as a header and used by the getContentType() callback.
     *
     * @var string
     */
    protected $contentType;

    /**
     * Constructor.
     *
     * @param string $to recipient's email
     */
    public function __construct(string $to)
    {
        $this->setTo($to);
    }

    /**
     * Sets the recipient's email.
     *
     * @param string $to
     * @return Email
     */
    public function setTo(string $to): self
    {
        $this->to = $to;

        return $this;
    }

    /**
     * Sets the email subject.
     *
     * @param string $subject
     * @return Email
     */
    public function setSubject(string $subject) : self
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Sets the email body.
     *
     * @param string $body
     * @return Email
     */
    public function setBody(string $body) : self
    {
        $this->body = $body;

        return $this;
    }

    /**
     * Sets the email headers.
     *
     * @param mixed $headers
     * @return Email
     */
    public function setHeaders($headers) : self
    {
        $this->headers = $headers;

        return $this;
    }

    /**
     * Sets the email content type and adds a header for it.
     *
     * @param string $contentType
     * @return Email
     * @throws \Exception
     */
    public function setContentType(string $contentType): self
    {
        $this->contentType = $contentType;

        $this->headers = ArrayHelper::combine($this->headers ?: [], ['Content-type' => $contentType]);

        return $this;
    }

    /**
     * Gets the recipient's email.
     *
     * @return string
     */
    public function getTo(): string
    {
        return $this->to;
    }

    /**
     * Gets the email subject.
     *
     * @return string
     */
    public function getSubject(): string
    {
        return $this->subject;
    }

    /**
     * Gets the email body.
     *
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * Gets the email headers.
     *
     * @return mixed
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * Gets the email content type.
     *
     * @return string
     */
    public function getContentType(): string
    {
        return $this->contentType;
    }

    /**
     * Sends the email.
     *
     * @throws \Exception
     */
    public function send()
    {
        // set the content type for this email
        $filter = Register::filter()
                ->setGroup('wp_mail_content_type')
                ->setHandler([$this, 'getContentType'])
                ->setPriority(10)
                ->setArgumentsCount(1);

        $filter->execute();

        wp_mail($this->to, $this->subject ?: '', $this->body ?: '', $this->headers);

        // clear the content type for other emails
        $filter->deregister();
    }
}
