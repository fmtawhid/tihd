<?php

namespace Modules\NotificationTemplate\database\seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Constant\Models\Constant;
use Modules\NotificationTemplate\Models\NotificationTemplate;

class NotificationTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Disable foreign key checks!
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        /*
         * NotificationTemplates Seed
         * ------------------
         */

        // DB::table('notificationtemplates')->truncate();
        // echo "Truncate: notificationtemplates \n";

        $types = [

            [
                'type' => 'notification_type',
                'value' => 'change_password',
                'name' => 'Chnage Password',
            ],
            [
                'type' => 'notification_type',
                'value' => 'forget_email_password',
                'name' => 'Forget Email/Password',
            ],
            [
                'type' => 'notification_param_button',
                'value' => 'id',
                'name' => 'ID',
            ],
            [
                'type' => 'notification_param_button',
                'value' => 'user_name',
                'name' => 'User Name',
            ],
            [
                'type' => 'notification_param_button',
                'value' => 'description',
                'name' => 'Description / Note',
            ],
            [
                'type' => 'notification_param_button',
                'value' => 'logged_in_user_fullname',
                'name' => 'Your Name',
            ],
            [
                'type' => 'notification_param_button',
                'value' => 'logged_in_user_role',
                'name' => 'Your Position',
            ],
            [
                'type' => 'notification_param_button',
                'value' => 'user_id',
                'name' => 'User\' ID',
            ],
            [
                'type' => 'notification_param_button',
                'value' => 'user_password',
                'name' => 'User Password',
            ],
            [
                'type' => 'notification_param_button',
                'value' => 'link',
                'name' => 'Link',
            ],
            [
                'type' => 'notification_param_button',
                'value' => 'site_url',
                'name' => 'Site URL',
            ],
            [
                'type' => 'notification_to',
                'value' => 'user',
                'name' => 'User',
            ],
            [
                'type' => 'notification_to',
                'value' => 'admin',
                'name' => 'Admin',
            ],
            [
                'type' => 'notification_to',
                'value' => 'demo_admin',
                'name' => 'Demo Admin',
            ],
            [
                'type' => 'notification_type',
                'value' => 'tv_show_add',
                'name' => 'TV Show Add',
            ],
            [
                'type' => 'notification_type',
                'value' => 'movie_add',
                'name' => 'Movie Add',
            ],
            [
                'type' => 'notification_type',
                'value' => 'episode_add',
                'name' => 'Episode Add',
            ],
            [
                'type' => 'notification_type',
                'value' => 'season_add',
                'name' => 'Season Add',
            ],
            [
                'type' => 'notification_type',
                'value' => 'new_subscription',
                'name' => 'New Subscription',
            ],
        ];

        foreach ($types as $value) {
            Constant::updateOrCreate(['type' => $value['type'], 'value' => $value['value']], $value);
        }

        // Enable foreign key checks!
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        DB::table('notification_templates')->delete();
        DB::table('notification_template_content_mapping')->delete();




        $template = NotificationTemplate::create([
            'type' => 'change_password',
            'name' => 'change_password',
            'label' => 'Change Password',
            'status' => 1,
            'channels' => ['IS_MAIL' => '0', 'PUSH_NOTIFICATION' => '1', 'IS_CUSTOM_WEBHOOK' => '0'],
        ]);
        $template->defaultNotificationTemplateMap()->create([
            'language' => 'en',
            'notification_link' => '',
            'notification_message' => '',
            'status' => 1,
            'subject' => 'Change Password',
            'template_detail' => '
            <p>Subject: Password Change Confirmation</p>
            <p>Dear [[ user_name ]],</p>
            <p>&nbsp;</p>
            <p>We wanted to inform you that a recent password change has been made for your account. If you did not initiate this change, please take immediate action to secure your account.</p>
            <p>&nbsp;</p>
            <p>To regain control and secure your account:</p>
            <p>&nbsp;</p>
            <p>Visit [[ link ]].</p>
            <p>Follow the instructions to verify your identity.</p>
            <p>Create a strong and unique password.</p>
            <p>Update passwords for any other accounts using similar credentials.</p>
            <p>If you have any concerns or need assistance, please contact our customer support team.</p>
            <p>&nbsp;</p>
            <p>Thank you for your attention to this matter.</p>
            <p>&nbsp;</p>
            <p>Best regards,</p>
            <p>[[ logged_in_user_fullname ]]<br />[[ logged_in_user_role ]]<br />[[ company_name ]]</p>
            <p>[[ company_contact_info ]]</p>
          ',
        ]);

        $template = NotificationTemplate::create([
            'type' => 'forget_email_password',
            'name' => 'forget_email_password',
            'label' => 'Forget Email/Password',
            'status' => 1,
            'channels' => ['IS_MAIL' => '0', 'PUSH_NOTIFICATION' => '1', 'IS_CUSTOM_WEBHOOK' => '0'],
        ]);
        $template->defaultNotificationTemplateMap()->create([
            'language' => 'en',
            'notification_link' => '',
            'notification_message' => '',
            'status' => 1,
            'subject' => 'Forget Email/Password',
            'template_detail' => '
            <p>Subject: Password Reset Instructions</p>
            <p>&nbsp;</p>
            <p>Dear [[ user_name ]],</p>
            <p>A password reset request has been initiated for your account. To reset your password:</p>
            <p>&nbsp;</p>
            <p>Visit [[ link ]].</p>
            <p>Enter your email address.</p>
            <p>Follow the instructions provided to complete the reset process.</p>
            <p>If you did not request this reset or need assistance, please contact our support team.</p>
            <p>&nbsp;</p>
            <p>Thank you.</p>
            <p>&nbsp;</p>
            <p>Best regards,</p>
            <p>[[ logged_in_user_fullname ]]<br />[[ logged_in_user_role ]]<br />[[ company_name ]]</p>
            <p>[[ company_contact_info ]]</p>
            <p>&nbsp;</p>
          ',
        ]);

        $template = NotificationTemplate::create([
            'type' => 'tv_show_add',
            'name' => 'tv_show_add',
            'label' => 'TV Show Added',
            'status' => 1,
            'to' => '["user","admin","demo_admin"]',
            'channels' => ['IS_MAIL' => '0', 'PUSH_NOTIFICATION' => '1', 'IS_CUSTOM_WEBHOOK' => '0'],
        ]);
        $template->defaultNotificationTemplateMap()->create([
            'language' => 'en',
            'notification_link' => '',
            'notification_message' => 'Thank you for choosing Us for your recent order. We are delighted to confirm that your order has been successfully placed.!',
            'status' => 1,
            'subject' => 'TV Show Added!',
            'template_detail' => '<p>Thank you for choosing Us for your recent order. We are delighted to confirm that your order has been successfully placed.!</p>',
        ]);

        $template = NotificationTemplate::create([
            'type' => 'movie_add',
            'name' => 'movie_add',
            'label' => 'Movie Added',
            'status' => 1,
            'to' => '["user","admin","demo_admin"]',
            'channels' => ['IS_MAIL' => '0', 'PUSH_NOTIFICATION' => '1', 'IS_CUSTOM_WEBHOOK' => '0'],
        ]);
        $template->defaultNotificationTemplateMap()->create([
            'language' => 'en',
            'notification_link' => '',
            'notification_message' => "We're excited to let you know that your order is now being prepared and will soon be on its way to satisfy your taste buds!",
            'status' => 1,
            'subject' => 'Movie Added!',
            'template_detail' => "<p>We're excited to let you know that your order is now being prepared and will soon be on its way to satisfy your taste buds!</p>",
        ]);

        $template = NotificationTemplate::create([
            'type' => 'episode_add',
            'name' => 'episode_add',
            'label' => 'Episode Added',
            'status' => 1,
            'to' => '["user","admin","demo_admin"]',
            'channels' => ['IS_MAIL' => '0', 'PUSH_NOTIFICATION' => '1', 'IS_CUSTOM_WEBHOOK' => '0'],
        ]);
        $template->defaultNotificationTemplateMap()->create([
            'language' => 'en',
            'notification_link' => '',
            'notification_message' => "We're delighted to inform you that your order has been successfully delivered to your doorstep.",
            'status' => 1,
            'subject' => 'Episode Added!',
            'template_detail' => "<p>We're delighted to inform you that your order has been successfully delivered to your doorstep.</p>",
        ]);

        $template = NotificationTemplate::create([
            'type' => 'season_add',
            'name' => 'season_add',
            'label' => 'Season Added',
            'status' => 1,
            'to' => '["user","admin","demo_admin"]',
            'channels' => ['IS_MAIL' => '0', 'PUSH_NOTIFICATION' => '1', 'IS_CUSTOM_WEBHOOK' => '0'],
        ]);
        $template->defaultNotificationTemplateMap()->create([
            'language' => 'en',
            'notification_link' => '',
            'notification_message' => 'We regret to inform you that your recent order has been cancelled.',
            'status' => 1,
            'subject' => 'Season Added!',
            'template_detail' => '<p>We regret to inform you that your recent order has been cancelled.</p>',
        ]);

        $template = NotificationTemplate::create([
            'type' => 'new_subscription',
            'name' => 'new_subscription',
            'label' => 'New User Subscribed',
            'status' => 1,
            'to' => '["admin","demo_admin","user"]',
            'channels' => ['IS_MAIL' => '0', 'PUSH_NOTIFICATION' => '1', 'IS_CUSTOM_WEBHOOK' => '0'],
        ]);
        $template->defaultNotificationTemplateMap()->create([
            'language' => 'en',
            'notification_link' => '',
            'notification_message' => 'A new user has subscribed',
            'status' => 1,
            'subject' => 'New User is subscribe!',
            'template_detail' => 'A new user has subscribed',
        ]);
        $template = NotificationTemplate::create([
            'type' => 'cancle_subscription',
            'name' => 'cancle_subscription',
            'label' => 'User Cancel Subscription',
            'status' => 1,
            'to' => '["admin","demo_admin","user"]',
            'channels' => ['IS_MAIL' => '0', 'PUSH_NOTIFICATION' => '1', 'IS_CUSTOM_WEBHOOK' => '0'],
        ]);
        $template->defaultNotificationTemplateMap()->create([
            'language' => 'en',
            'notification_link' => '',
            'notification_message' => 'A user has cancle subscription',
            'status' => 1,
            'subject' => 'A User is cancle subscribe!',
            'template_detail' => 'A user has cancle subscription',
        ]);

    }
}
