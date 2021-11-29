<?php

namespace GoDaddy\WordPress\MWC\Dashboard\API\Controllers;

use Exception;
use GoDaddy\WordPress\MWC\Common\Helpers\StringHelper;
use GoDaddy\WordPress\MWC\Dashboard\Helpers\SupportHelper;
use WP_Error;
use WP_REST_Request;
use WP_REST_Response;
use WP_User;

/**
 * Support controller class.
 */
class Support extends AbstractController
{
    /**
     * Controller constructor.
     */
    public function __construct()
    {
        $this->route = 'support-requests';
    }

    /**
     * Registers the API route for the support endpoint.
     */
    public function registerRoutes()
    {
        register_rest_route($this->namespace, "/{$this->route}", [
            [
                'methods'             => 'POST', // WP_REST_Server::CREATABLE
                'callback'            => [$this, 'createItem'],
                'permission_callback' => [$this, 'createItemPermissionsCheck'],
                'args'                => $this->getItemSchema(),
            ],
            'schema' => [$this, 'getItemSchema'],
        ]);
    }

    /**
     * Gets the schema.
     *
     * @return array
     */
    public function getItemSchema(): array
    {
        return [
            'replyTo'         => [
                'required'    => true,
                'description' => __('The e-mail address the support team will reply to', 'mwc-dashboard'),
                'type'        => 'string',
                'context'     => ['view', 'edit'],
            ],
            'plugin'          => [
                'description' => __('The plugin name', 'mwc-dashboard'),
                'type'        => [ 'null', 'string' ],
                'context'     => ['view', 'edit'],
            ],
            'subject'         => [
                'required'    => true,
                'description' => __('The subject', 'mwc-dashboard'),
                'type'        => 'string',
                'context'     => ['view', 'edit'],
            ],
            'message'         => [
                'required'    => true,
                'description' => __('The message', 'mwc-dashboard'),
                'type'        => 'string',
                'context'     => ['view', 'edit'],
            ],
            'reason'          => [
                'required'    => true,
                'description' => __('The reason field', 'mwc-dashboard'),
                'type'        => 'string',
                'context'     => ['view', 'edit'],
            ],
            'createDebugUser' => [
                'description' => __('Whether or not to create a debug user', 'mwc-dashboard'),
                'type'        => 'bool',
                'context'     => ['view', 'edit'],
            ],
        ];
    }

    /**
     * Creates an item.
     *
     * @param WP_REST_Request $request full details about the request
     *
     * @return WP_REST_Response|WP_Error
     */
    public function createItem(WP_REST_Request $request)
    {
        $subject = StringHelper::sanitize($request->get_param('subject'));
        $message = StringHelper::sanitize($request->get_param('message'));
        $replyTo = StringHelper::sanitize($request->get_param('replyTo'));
        $reason = StringHelper::sanitize($request->get_param('reason'));
        $pluginName = !empty($request->get_param('plugin')) ? StringHelper::sanitize($request->get_param('plugin')) : '';
        $createSupportUser = ! empty(StringHelper::sanitize($request->get_param('createDebugUser')));

        // determines whether the support user must be created or not
        $supportUser = $createSupportUser ? SupportHelper::createSupportUser() : null;

        try {
            $supportRequestData = SupportHelper::getSupportRequestData($subject, $message, $replyTo, $reason, $pluginName);

            SupportHelper::createSupportRequest($replyTo, $supportRequestData);
        } catch (Exception $ex) {
            return new WP_Error('mwc_dashboard_support_request_error', $ex->getMessage());
        }

        return rest_ensure_response([
            'reason'          => $reason,
            'replyTo'         => $replyTo,
            'plugin'          => $pluginName,
            'subject'         => $subject,
            'message'         => $message,
            'createDebugUser' => $createSupportUser,
            'debugUserId'     => $supportUser->ID ?? '',
        ]);
    }
}
