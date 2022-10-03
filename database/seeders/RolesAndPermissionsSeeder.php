<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Admin;
use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        // Admins seeder
        $superadmin = Admin::create([
          'username' => 'مدير النظام',
          'email' => 'admin@sju.org',
          'mobile' => '01000000000',
          'branch_id' => null,
          'password' => bcrypt('password'),
        ]);
        $branch_manager = Admin::create([
          'username' => 'مدير فرع',
          'email' => 'manager@branch.org',
          'mobile' => '02000000000',
          'branch_id' => 1,
          'password' => bcrypt('password'),
        ]);
        $news_editor = Admin::create([
          'username' => 'محرر أخبار',
          'email' => 'editor@sju.org',
          'mobile' => '03000000000',
          'branch_id' => null,
          'password' => bcrypt('password'),
        ]);


        // Roles seeder
        $admin = Role::create([
            'name' => 'admin',
            'display_name' => 'مدير الموقع', // optional
            'description' => 'مديرو الموقع', // optional
        ]);
        
        $manager = Role::create([
            'name' => 'manager',
            'display_name' => 'مدير فرع', // optional
            'description' => 'مدراء الفروع', // optional
        ]);
        $editor = Role::create([
            'name' => 'editor',
            'display_name' => 'محرر أخبار', // optional
            'description' => 'محررو الأخبار', // optional
        ]);

        /**
         * PERMISSIONS
         */

        // Settings, Roles, and Moderators Permissions
        $manageOptions   = Permission::create([ 'name' => 'manage-options', 'display_name' => 'Manage site options', 'description' => 'Manage site options' ]);
        $manageRoles     = Permission::create([ 'name' => 'manage-roles', 'display_name' => 'Manage site roles and permissions', 'description' => 'Manage site roles and permissions' ]);
        $createModerator = Permission::create([ 'name' => 'create-moderator', 'display_name' => 'Create moderators', 'description' => 'Create moderators data' ]);
        $showModerator   = Permission::create([ 'name' => 'read-moderator', 'display_name' => 'Show moderators', 'description' => 'Show moderators data' ]);
        $updateModerator = Permission::create([ 'name' => 'update-moderator', 'display_name' => 'Update moderators', 'description' => 'Update moderators data' ]);
        $deleteModerator = Permission::create([ 'name' => 'delete-moderator', 'display_name' => 'Delete moderators', 'description' => 'Delete moderators data' ]);

        // Members permissions
        $createMember = Permission::create([ 'name' => 'create-member', 'display_name' => 'Create members', 'description' => 'Create members data' ]);
        $showMember   = Permission::create([ 'name' => 'read-member', 'display_name' => 'Show members', 'description' => 'Show members data' ]);
        $updateMember = Permission::create([ 'name' => 'update-member', 'display_name' => 'Update members', 'description' => 'Update members data' ]);
        $deleteMember = Permission::create([ 'name' => 'delete-member', 'display_name' => 'Delete members', 'description' => 'Delete members data' ]);

        // Subscribers permissions
        $createSubscriber = Permission::create([ 'name' => 'create-subscriber', 'display_name' => 'Create subscribers', 'description' => 'Create subscribers data' ]);
        $showSubscriber   = Permission::create([ 'name' => 'read-subscriber', 'display_name' => 'Show subscribers', 'description' => 'Show subscribers data' ]);
        $updateSubscriber = Permission::create([ 'name' => 'update-subscriber', 'display_name' => 'Update subscribers', 'description' => 'Update subscribers data' ]);
        $deleteSubscriber = Permission::create([ 'name' => 'delete-subscriber', 'display_name' => 'Delete subscribers', 'description' => 'Delete subscribers data' ]);

        // Volunteers permissions
        $createVolunteer = Permission::create([ 'name' => 'create-volunteer', 'display_name' => 'Create volunteers', 'description' => 'Create volunteers data' ]);
        $showVolunteer   = Permission::create([ 'name' => 'read-volunteer', 'display_name' => 'Show volunteers', 'description' => 'Show volunteers data' ]);
        $updateVolunteer = Permission::create([ 'name' => 'update-volunteer', 'display_name' => 'Update volunteers', 'description' => 'Update volunteers data' ]);
        $deleteVolunteer = Permission::create([ 'name' => 'delete-volunteer', 'display_name' => 'Delete volunteers', 'description' => 'Delete volunteers data' ]);

        // Courses permissions
        $createCourse = Permission::create([ 'name' => 'create-course', 'display_name' => 'Create courses', 'description' => 'Create courses data' ]);
        $showCourse   = Permission::create([ 'name' => 'read-course', 'display_name' => 'Show courses', 'description' => 'Show courses data' ]);
        $updateCourse = Permission::create([ 'name' => 'update-course', 'display_name' => 'Update courses', 'description' => 'Update courses data' ]);
        $deleteCourse = Permission::create([ 'name' => 'delete-course', 'display_name' => 'Delete courses', 'description' => 'Delete courses data' ]);

        // Namings permissions
        $ManageNamings  = Permission::create([ 'name' => 'manage-namings', 'display_name' => 'Manage namings', 'description' => 'Manage namings data' ]);

        // Templates permissions
        $createTemplate = Permission::create([ 'name' => 'create-template', 'display_name' => 'Create templates', 'description' => 'Create templates data' ]);
        $showTemplate   = Permission::create([ 'name' => 'read-template', 'display_name' => 'Show templates', 'description' => 'Show templates data' ]);
        $updateTemplate = Permission::create([ 'name' => 'update-template', 'display_name' => 'Update templates', 'description' => 'Update templates data' ]);
        $deleteTemplate = Permission::create([ 'name' => 'delete-template', 'display_name' => 'Delete templates', 'description' => 'Delete templates data' ]);

        // Questionnaires permissions
        $createQuestionnaire = Permission::create([ 'name' => 'create-questionnaire', 'display_name' => 'Create questionnaires', 'description' => 'Create questionnaires data' ]);
        $showQuestionnaire   = Permission::create([ 'name' => 'read-questionnaire', 'display_name' => 'Show questionnaires', 'description' => 'Show questionnaires data' ]);
        $updateQuestionnaire = Permission::create([ 'name' => 'update-questionnaire', 'display_name' => 'Update questionnaires', 'description' => 'Update questionnaires data' ]);
        $deleteQuestionnaire = Permission::create([ 'name' => 'delete-questionnaire', 'display_name' => 'Delete questionnaires', 'description' => 'Delete questionnaires data' ]);

        // Settings permissions
        $ManageSettings  = Permission::create([ 'name' => 'manage-settings', 'display_name' => 'Manage settings', 'description' => 'Manage settings data' ]);

        // Menus permissions
        $createMenu = Permission::create([ 'name' => 'create-menu', 'display_name' => 'Create menus', 'description' => 'Create menus data' ]);
        $showMenu   = Permission::create([ 'name' => 'read-menu', 'display_name' => 'Show menus', 'description' => 'Show menus data' ]);
        $updateMenu = Permission::create([ 'name' => 'update-menu', 'display_name' => 'Update menus', 'description' => 'Update menus data' ]);
        $deleteMenu = Permission::create([ 'name' => 'delete-menu', 'display_name' => 'Delete menus', 'description' => 'Delete menus data' ]);

        // Pages permissions
        $createPage = Permission::create([ 'name' => 'create-page', 'display_name' => 'Create pages', 'description' => 'Create pages data' ]);
        $showPage   = Permission::create([ 'name' => 'read-page', 'display_name' => 'Show pages', 'description' => 'Show pages data' ]);
        $updatePage = Permission::create([ 'name' => 'update-page', 'display_name' => 'Update pages', 'description' => 'Update pages data' ]);
        $deletePage = Permission::create([ 'name' => 'delete-page', 'display_name' => 'Delete pages', 'description' => 'Delete pages data' ]);

        // Posts permissions
        $createPost = Permission::create([ 'name' => 'create-post', 'display_name' => 'Create posts', 'description' => 'Create posts data' ]);
        $showPost   = Permission::create([ 'name' => 'read-post', 'display_name' => 'Show posts', 'description' => 'Show posts data' ]);
        $updatePost = Permission::create([ 'name' => 'update-post', 'display_name' => 'Update posts', 'description' => 'Update posts data' ]);
        $deletePost = Permission::create([ 'name' => 'delete-post', 'display_name' => 'Delete posts', 'description' => 'Delete posts data' ]);

        // Ads permissions
        $createAd = Permission::create([ 'name' => 'create-ad', 'display_name' => 'Create ads', 'description' => 'Create ads data' ]);
        $showAd   = Permission::create([ 'name' => 'read-ad', 'display_name' => 'Show ads', 'description' => 'Show ads data' ]);
        $updateAd = Permission::create([ 'name' => 'update-ad', 'display_name' => 'Update ads', 'description' => 'Update ads data' ]);
        $deleteAd = Permission::create([ 'name' => 'delete-ad', 'display_name' => 'Delete ads', 'description' => 'Delete ads data' ]);

        // Links permissions
        $ManageLinks  = Permission::create([ 'name' => 'manage-links', 'display_name' => 'Manage links', 'description' => 'Manage links data' ]);

        // Upload Center permissions
        $ManageUploadCenter  = Permission::create([ 'name' => 'manage-uploadcenter', 'display_name' => 'Manage upload center', 'description' => 'Manage upload center data' ]);

        // Technical support permissions
        $ManageMemberSupport     = Permission::create([ 'name' => 'manage-membersupport', 'display_name' => 'Manage members support', 'description' => 'Manage members support data' ]);
        $ManageSubscriberSupport = Permission::create([ 'name' => 'manage-subscribersupport', 'display_name' => 'Manage subscribers support', 'description' => 'Manage subscribers support data' ]);
        $ManageVolunteerSupport  = Permission::create([ 'name' => 'manage-volunteersupport', 'display_name' => 'Manage volunteers support', 'description' => 'Manage volunteers support data' ]);

        // Studio permissions
        $ManageStudio  = Permission::create([ 'name' => 'manage-studio', 'display_name' => 'Manage studio', 'description' => 'Manage studio data' ]);

        // SMS permissions
        $ManageSMS  = Permission::create([ 'name' => 'manage-sms', 'display_name' => 'Manage sms', 'description' => 'Manage sms data' ]);

        // Attach permissions to roles
        $admin->attachPermissions(
          [
            // Settings, Roles, and Moderators Permissions
            $manageOptions, $manageRoles, $createModerator, $showModerator, $updateModerator, $deleteModerator,

            // Members permissions
            $createMember, $showMember, $updateMember, $deleteMember,

            // Subscribers permissions
            $createSubscriber, $showSubscriber, $updateSubscriber, $deleteSubscriber,

            // Volunteers permissions
            $createVolunteer, $showVolunteer, $updateVolunteer, $deleteVolunteer,

            // Courses permissions
            $createCourse, $showCourse, $updateCourse, $deleteCourse,

            // Namings permissions
            $ManageNamings,

            // Templates permissions
            $createTemplate, $showTemplate, $updateTemplate, $deleteTemplate,

            // Questionnaires permissions
            $createQuestionnaire, $showQuestionnaire, $updateQuestionnaire, $deleteQuestionnaire,

            // Settings permissions
            $ManageSettings,

            // Menus permissions
            $createMenu, $showMenu, $updateMenu, $deleteMenu,

            // Pages permissions
            $createPage, $showPage, $updatePage, $deletePage,

            // Posts permissions
            $createPost, $showPost, $updatePost, $deletePost,

            // Ads permissions
            $createAd, $showAd, $updateAd, $deleteAd,

            // Links permissions
            $ManageLinks,

            // Upload Center permissions
            $ManageUploadCenter,

            // Technical support permissions
            $ManageMemberSupport, $ManageSubscriberSupport, $ManageVolunteerSupport,

            // Studio permissions
            $ManageStudio,

            // SMS permissions
            $ManageSMS
          ]
        );
        $manager->attachPermissions([$showMember, $updateMember]);
        $editor->attachPermissions([$showMember]);

        // Attach roles to admins
        $superadmin->attachRole($admin);
        $branch_manager->attachRole($manager);
        $news_editor->attachRole($editor);


    }
}
