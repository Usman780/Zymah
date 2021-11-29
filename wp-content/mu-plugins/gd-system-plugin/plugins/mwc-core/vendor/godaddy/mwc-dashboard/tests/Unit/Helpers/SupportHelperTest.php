<?php

namespace GoDaddy\WordPress\MWC\Dashboard\Tests\Helpers;

use GoDaddy\WordPress\MWC\Common\Configuration\Configuration;
use GoDaddy\WordPress\MWC\Common\Repositories\ManagedWooCommerceRepository;
use GoDaddy\WordPress\MWC\Common\Tests\WPTestCase;
use GoDaddy\WordPress\MWC\Dashboard\Helpers\SupportHelper;
use GoDaddy\WordPress\MWC\Dashboard\Repositories\UserRepository;
use GoDaddy\WordPress\MWC\Dashboard\Repositories\WooCommercePluginsRepository;
use Mockery;
use WP_Mock;

/**
 * @covers \GoDaddy\WordPress\MWC\Dashboard\Helpers\SupportHelper
 */
final class SupportHelperTest extends WPTestCase
{
    /**
     * Tests the getSupportUser() method.
     *
     * @covers \GoDaddy\WordPress\MWC\Dashboard\Helpers\SupportHelper::getSupportUser()
     */
    public function testGetSupportUserByLogin()
    {
        $userLogin = 'test-login';

        WP_Mock::userFunction('get_transient');

        Configuration::set('support.support_user.login', $userLogin);

        $user = Mockery::mock('\WP_User');
        $user->user_login = $userLogin;

        WP_Mock::userFunction('get_user_by')
            ->withArgs(['login', $userLogin])
            ->andReturn($user);

        $this->assertSame($user, SupportHelper::getSupportUser());
    }

    /**
     * Tests the getSupportUser() method.
     *
     * @covers \GoDaddy\WordPress\MWC\Dashboard\Helpers\SupportHelper::getSupportUser()
     */
    public function testGetSupportUserByEmail()
    {
        $userLogin = 'test-login';
        $userEmail = 'test-user@example.com';

        WP_Mock::userFunction('get_transient');

        Configuration::set('support.support_user.login', $userLogin);
        Configuration::set('support.support_user.email', $userEmail);

        $user = Mockery::mock('\WP_User');
        $user->user_email = $userEmail;

        WP_Mock::userFunction('get_user_by')
            ->withArgs(['login', $userLogin])
            ->andReturn(false);

        WP_Mock::userFunction('get_user_by')
            ->withArgs(['email', $userEmail])
            ->andReturn($user);

        $this->assertSame($user, SupportHelper::getSupportUser());
    }

    /**
     * Tests the getSupportUser() method.
     *
     * @covers \GoDaddy\WordPress\MWC\Dashboard\Helpers\SupportHelper::getSupportUser()
     */
    public function testGetSupportUserNotFound()
    {
        $userLogin = 'test-login';
        $userEmail = 'test-user@example.com';

        WP_Mock::userFunction('get_transient');

        Configuration::set('support.support_user.login', $userLogin);
        Configuration::set('support.support_user.email', $userEmail);

        WP_Mock::userFunction('get_user_by')
            ->withArgs(['login', $userLogin])
            ->andReturn(false);

        WP_Mock::userFunction('get_user_by')
            ->withArgs(['email', $userEmail])
            ->andReturn(false);

        $this->assertSame(false, SupportHelper::getSupportUser());
    }

    /**
     * Tests the getSupportBotAppName() method.
     *
     * @covers \GoDaddy\WordPress\MWC\Dashboard\Helpers\SupportHelper::getSupportBotAppName()
     * @throws \Exception
     */
    public function testGetSupportBotAppNameNotReseller()
    {
        $appName = 'Support Bot App Name';

        WP_Mock::userFunction('get_transient');

        Configuration::set('support.support_bot.app_name', $appName);

        $this->mockStaticMethod(ManagedWooCommerceRepository::class, 'isReseller')
             ->andReturn(false);

        $this->assertSame($appName, SupportHelper::getSupportBotAppName());
    }

    /**
     * Tests the getSupportBotAppName() method.
     *
     * @covers \GoDaddy\WordPress\MWC\Dashboard\Helpers\SupportHelper::getSupportBotAppName()
     * @throws \Exception
     */
    public function testGetSupportBotAppNameReseller()
    {
        $appName = 'Support Bot App Name for Reseller';

        WP_Mock::userFunction('get_transient');

        Configuration::set('support.support_bot.app_name_reseller', $appName);

        $this->mockStaticMethod(ManagedWooCommerceRepository::class, 'isReseller')
             ->andReturn(true);

        $this->assertSame($appName, SupportHelper::getSupportBotAppName());
    }

    /**
     * Tests the getSupportBotContext() method.
     *
     * @covers \GoDaddy\WordPress\MWC\Dashboard\Helpers\SupportHelper::getSupportBotContext()
     *
     * @param bool $isReseller
     * @param string $expected
     *
     * @throws \Exception
     * @dataProvider dataProviderGetSupportBotContext
     */
    public function testGetSupportBotContext(bool $isReseller, string $expected)
    {
        $this->mockStaticMethod(ManagedWooCommerceRepository::class, 'isReseller')
             ->andReturn($isReseller);

        $this->assertSame($expected, SupportHelper::getSupportBotContext());
    }

    /** @see testGetSupportBotContext */
    public function dataProviderGetSupportBotContext() : array
    {
        return [
            [false, 'godaddy'],
            [true, 'reseller'],
        ];
    }

    /**
     * Tests the isSupportBotConnected() method.
     *
     * @covers \GoDaddy\WordPress\MWC\Dashboard\Helpers\SupportHelper::isSupportBotConnected()
     * @throws \Exception
     */
    public function testIsSupportBotConnectedWithKeys()
    {
        global $wpdb;

        $this->mockStaticMethod(SupportHelper::class, 'getSupportBotAppName')
             ->andReturn('app');

        $wpdb = Mockery::mock('\WPDB');
        $wpdb->prefix = '';
        $wpdb->shouldReceive('prepare')
             ->once()
             ->withArgs(["SELECT COUNT(key_id) FROM {$wpdb->prefix}woocommerce_api_keys WHERE description LIKE %s", 'app%'])
             ->andReturnArg(0);
        $wpdb->shouldReceive('get_var')
             ->once()
             ->withArgs(["SELECT COUNT(key_id) FROM {$wpdb->prefix}woocommerce_api_keys WHERE description LIKE %s"])
             ->andReturn(1);

        $this->assertTrue(SupportHelper::isSupportBotConnected());
    }

    /**
     * Tests the isSupportBotConnected() method.
     *
     * @covers \GoDaddy\WordPress\MWC\Dashboard\Helpers\SupportHelper::isSupportBotConnected()
     * @throws \Exception
     */
    public function testIsSupportBotConnectedWithoutKeys()
    {
        global $wpdb;

        $this->mockStaticMethod(SupportHelper::class, 'getSupportBotAppName')
             ->andReturn('app');

        $wpdb = Mockery::mock('\WPDB');
        $wpdb->prefix = '';
        $wpdb->shouldReceive('prepare')
             ->once()
             ->withArgs(["SELECT COUNT(key_id) FROM {$wpdb->prefix}woocommerce_api_keys WHERE description LIKE %s", 'app%'])
             ->andReturnArg(0);
        $wpdb->shouldReceive('get_var')
             ->once()
             ->withArgs(["SELECT COUNT(key_id) FROM {$wpdb->prefix}woocommerce_api_keys WHERE description LIKE %s"])
             ->andReturn(0);

        $this->assertFalse(SupportHelper::isSupportBotConnected());
    }

    /**
     * Tests the getSupportBotConnectUrl() method.
     *
     * @covers \GoDaddy\WordPress\MWC\Dashboard\Helpers\SupportHelper::getSupportBotConnectUrl()
     * @throws \Exception
     */
    public function testGetSupportBotConnectUrl()
    {
        $baseUrl = 'https://support-bot.test';
        $context = 'godaddy';

        $this->mockStaticMethod(SupportHelper::class, 'getSupportBotContext')
             ->andReturn($context);

        Configuration::set('support.support_bot.connect_url', $baseUrl);

        $siteUrl = 'https://example.test';

        WP_Mock::userFunction('site_url')
            ->once()
            ->andReturn($siteUrl);

        WP_Mock::userFunction('add_query_arg')
            ->once()
            ->withArgs([['context' => $context, 'url' => urlencode($siteUrl) ], $baseUrl])
            // the returned string does not matter, as we do not want to test add_query_arg
            ->andReturn($baseUrl);

        SupportHelper::getSupportBotConnectUrl();

        $this->assertConditionsMet();
    }

    /**
     * Tests the createSupportUser() method.
     *
     * @covers \GoDaddy\WordPress\MWC\Dashboard\Helpers\SupportHelper::createSupportUser()
     */
    public function testCreateSupportUser()
    {
        $userLogin = 'support-user';
        $userEmail = 'support-user@example.test';
        $userPassword = 'fake-password';
        $userId = 123;

        Configuration::set('support.support_user.login', $userLogin);
        Configuration::set('support.support_user.email', $userEmail);

        WP_Mock::userFunction('wp_generate_password')
               ->once()
               ->andReturn($userPassword);

        WP_Mock::userFunction('wp_create_user')
               ->once()
               ->withArgs([$userLogin, $userPassword, $userEmail])
               ->andReturn($userId);

        WP_Mock::userFunction('is_wp_error')
               ->once()
               ->withArgs([$userId])
               ->andReturn(false);

        $user = Mockery::mock('WP_User');
        $user->shouldReceive('add_role')->with('administrator');

        WP_Mock::userFunction('get_user_by')
               ->once()
               ->withArgs(['id', $userId])
               ->andReturn($user);

        $this->assertSame($user, SupportHelper::createSupportUser());
    }

    /**
     * Tests the createSupportUser() method.
     *
     * @covers \GoDaddy\WordPress\MWC\Dashboard\Helpers\SupportHelper::createSupportUser()
     */
    public function testCreateSupportUserError()
    {
        $userLogin = 'support-user';
        $userEmail = 'support-user@example.test';
        $userPassword = 'fake-password';

        Configuration::set('support.support_user.login', $userLogin);
        Configuration::set('support.support_user.email', $userEmail);

        WP_Mock::userFunction('wp_generate_password')
               ->once()
               ->andReturn($userPassword);

        $error = Mockery::mock('\WP_Error');
        WP_Mock::userFunction('wp_create_user')
               ->once()
               ->withArgs([$userLogin, $userPassword, $userEmail])
               ->andReturn($error);

        WP_Mock::userFunction('is_wp_error')
               ->once()
               ->withArgs([$error])
               ->andReturn(true);

        WP_Mock::userFunction('get_user_by')
               ->never();

        $this->assertSame(false, SupportHelper::createSupportUser());
    }

    /**
     * Tests the getCustomerUser() method.
     *
     * @covers \GoDaddy\WordPress\MWC\Dashboard\Helpers\SupportHelper::getCustomerUser()
     */
    public function testGetCustomerUserByEmail()
    {
        $userEmail = 'test-user@example.com';

        $user = Mockery::mock('\WP_User');
        $user->user_email = $userEmail;

        WP_Mock::userFunction('get_user_by')
               ->withArgs(['email', $userEmail])
               ->andReturn($user);

        $this->assertSame($user, SupportHelper::getCustomerUser($userEmail));
    }

    /**
     * Tests the getCustomerUser() method.
     *
     * @covers \GoDaddy\WordPress\MWC\Dashboard\Helpers\SupportHelper::getCustomerUser()
     */
    public function testGetCustomerUserCurrentUser()
    {
        $userEmail = 'test-user@example.com';
        $currentUserId = 123;

        $user = Mockery::mock('\WP_User');
        $user->ID = $currentUserId;

        WP_Mock::userFunction('get_user_by')
               ->withArgs(['email', $userEmail])
               ->andReturn(false);

        WP_Mock::userFunction('get_current_user_id')
               ->andReturn($currentUserId);

        WP_Mock::userFunction('get_user_by')
               ->withArgs(['id', $currentUserId])
               ->andReturn($user);

        $this->assertSame($user, SupportHelper::getCustomerUser($userEmail));
    }

    /**
     * Tests the getCustomerUser() method.
     *
     * @covers \GoDaddy\WordPress\MWC\Dashboard\Helpers\SupportHelper::getCustomerUser()
     */
    public function testGetCustomerUserNoUser()
    {
        $userEmail = 'test-user@example.com';
        $currentUserId = 123;

        $user = Mockery::mock('\WP_User');
        $user->ID = $currentUserId;

        WP_Mock::userFunction('get_user_by')
               ->withArgs(['email', $userEmail])
               ->andReturn(false);

        WP_Mock::userFunction('get_current_user_id')
               ->andReturn(0);

        WP_Mock::userFunction('get_user_by')
               ->withArgs(['id', 0])
               ->andReturn(false);

        $this->assertSame(false, SupportHelper::getCustomerUser($userEmail));
    }

    /**
     * Tests the getSupportRequestData() method.
     *
     * @covers \GoDaddy\WordPress\MWC\Dashboard\Helpers\SupportHelper::getSupportRequestData()
     * @throws \Exception
     */
    public function testGetSupportRequestData()
    {
        $subject = 'Test subject';
        $message = 'Test message';
        $customerName = 'John Doe';
        $customerEmail = 'johndoe@example.test';
        $reason = 'hosting';
        $pluginName = 'Test plugin';
        $pluginData = ['key' => 'value'];
        $pluginVersion = '1.1.2';
        $pluginSupportEndDate = '2023-12-12';
        $supportBotContext = 'godaddy';
        $systemStatusReport = 'test status report';
        $supportUserId = 123;
        $supportUserPasswordResetUrl = 'https://reset.test';

        $expectedData = [
            'ticket'               => [
                'subject'     => $subject,
                'description' => $message,
            ],
            'customer'             => [
                'name'  => $customerName,
                'email' => $customerEmail,
            ],
            'reason' => $reason,
            'plugin'               => [
                'name'             => $pluginName,
                'version'          => $pluginVersion,
                'support_end_date' => $pluginSupportEndDate,
            ],
            'support_bot_context'  => $supportBotContext,
            'system_status_report' => $systemStatusReport,
            'support_user' => [
                'user_id' => $supportUserId,
                'password_reset_url' => $supportUserPasswordResetUrl,
            ],
        ];

        $user = Mockery::mock('\WP_User');

        $this->mockStaticMethod(SupportHelper::class, 'getCustomerUser')
            ->withArgs([$customerEmail])
             ->andReturn($user);

        $this->mockStaticMethod(UserRepository::class, 'getUserName')
            ->withArgs([$user])
             ->andReturn($customerName);

        $this->mockStaticMethod(WooCommercePluginsRepository::class, 'getPluginDataByName')
            ->withArgs([$pluginName])
             ->andReturn($pluginData);

        $this->mockStaticMethod(WooCommercePluginsRepository::class, 'getPluginVersion')
            ->withArgs([$pluginData])
             ->andReturn($pluginVersion);

        $this->mockStaticMethod(WooCommercePluginsRepository::class, 'getWooCommerceSubscriptionEnd')
            ->withArgs([$pluginData])
             ->andReturn($pluginSupportEndDate);

        $this->mockStaticMethod(SupportHelper::class, 'getSupportBotContext')
             ->andReturn($supportBotContext);

        $wooCommerceApi = Mockery::mock('\WC_API');
        $wooCommerceApi->shouldReceive('get_endpoint_data')
              ->once()
            ->withArgs(['/wc/v3/system_status'])
              ->andReturn($systemStatusReport);

        $wooCommerce = Mockery::mock('\WooCommerce');
        $wooCommerce->api = $wooCommerceApi;

        WP_Mock::userFunction('WC')
               ->andReturn($wooCommerce);

        $supportUser = Mockery::mock('\WP_User');
        $supportUser->ID = $supportUserId;

        $this->mockStaticMethod(SupportHelper::class, 'getSupportUser')
             ->andReturn($supportUser);

        $this->mockStaticMethod(UserRepository::class, 'getPasswordResetUrl')
            ->withArgs([$supportUser])
             ->andReturn($supportUserPasswordResetUrl);

        $body = SupportHelper::getSupportRequestData($subject, $message, $customerEmail, $reason, $pluginName);

        $this->assertNotEmpty($body);
        $this->assertIsArray($body);
        $this->assertEquals($expectedData, $body);
    }
}
