<?php

namespace GoDaddy\WordPress\MWC\Dashboard\Message;

use GoDaddy\WordPress\MWC\Common\Traits\CanConvertToArrayTrait;
use GoDaddy\WordPress\MWC\Common\Traits\HasUserMetaTrait;

class MessageStatus
{
    use HasUserMetaTrait;
    use CanConvertToArrayTrait;

    /**
     * Message status: Unread.
     *
     * @since x.y.z
     *
     * @var string
     */
    const STATUS_UNREAD = 'unread';

    /**
     * Message status: Read.
     *
     * @since x.y.z
     *
     * @var string
     */
    const STATUS_READ = 'read';

    /**
     * Message status: Deleted.
     *
     * @since x.y.z
     *
     * @var string
     */
    const STATUS_DELETED = 'deleted';

    /**
     * Related message ID.
     *
     * @since x.y.z
     *
     * @var string
     */
    protected $messageId;

    /**
     * The current status.
     *
     * @since x.y.z
     *
     * @var string
     */
    protected $status;

    /**
     * MessageStatus constructor.
     *
     * @param Message $message
     * @param int     $userId
     */
    public function __construct(Message $message, int $userId)
    {
        $this->messageId = $message->getId();
        $this->userId = $userId;
        $this->metaKey = '_mwc_dashboard_message_status_'.$this->messageId;
        $this->status = $this->getStatus();

        $this->loadUserMeta(static::STATUS_UNREAD);
    }

    /**
     * Checks if message status is deleted or not.
     *
     * @since x.y.z
     *
     * @return bool
     */
    public function isDeleted() : bool
    {
        return self::STATUS_DELETED === $this->getStatus();
    }

    /**
     * Gets the message status state.
     *
     * @since x.y.z
     *
     * @return string|null
     */
    public function getStatus() : string
    {
        return $this->getUserMeta() ?? static::STATUS_UNREAD;
    }
}
