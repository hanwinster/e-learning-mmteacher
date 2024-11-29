<?php

namespace App;

class Permission extends \Spatie\Permission\Models\Permission
{
    public static function defaultPermissions() //used for seeding permission table only
    {
        return [
            // Resource
            'view_resource',
            'add_resource',
            'edit_resource',
            'delete_resource',
            'publish_resource',
            'approve_resource',
            'import_resource',
            'view_upload_requests',
            'approve_upload_requests',

            // Subject
            'view_subject',
            'add_subject',
            'edit_subject',
            'delete_subject',
            'publish_subject',

            // Page
            'view_page',
            'add_page',
            'edit_page',
            'delete_page',
            'publish_page',

            // Article
            'view_article',
            'add_article',
            'edit_article',
            'delete_article',
            'publish_article',

            'view_article_category',
            'add_article_category',
            'edit_article_category',
            'delete_article_category',
            'publish_article_category',

            'view_license',
            'add_license',
            'edit_license',
            'delete_license',
            'publish_license',

            'view_keyword',
            'add_keyword',
            'edit_keyword',
            'delete_keyword',
            'publish_keyword',

            'view_link_report',
            'add_link_report',
            'edit_link_report',
            'delete_link_report',
            'publish_link_report',

            'view_college',
            'add_college',
            'edit_college',
            'delete_college',
            'publish_college',

            'view_contact',
            'add_contact',
            'edit_contact',
            'delete_contact',
            'publish_contact',

            'view_slide',
            'add_slide',
            'edit_slide',
            'delete_slide',
            'publish_slide',

            'view_faq',
            'add_faq',
            'edit_faq',
            'delete_faq',
            'publish_faq',

            'view_faq_category',
            'add_faq_category',
            'edit_faq_category',
            'delete_faq_category',
            'publish_faq_category',

            'view_user',
            'add_user',
            'edit_user',
            'delete_user',
            'verify_user',
            'block_user',
            'approve_user',
            'import_user',

            'view_role',
            'add_role',
            'edit_role',
            'delete_role',

            'view_year',
            'add_year',
            'edit_year',
            'delete_year',

            'add_course',
            'edit_course',
            'detail_course',
            'delete_course',
            'publish_course',
            'approve_course',
            'view_upload_course_request',
            'approve_upload_course_request',
            'view_lecture',
            'add_lecture',
            'edit_lecture',
            'delete_lecture',
            'view_course_category',
            'add_course_category',
            'edit_course_category',
            'delete_course_category',
            'view_assignment',
            'add_assignment',
            'edit_assignment',
            'delete_assignment',
            'add_assignment_comment',
            'view_quiz',
            'add_quiz',
            'edit_quiz',
            'delete_quiz',
            'view_question',
            'add_question',
            'edit_question',
            'delete_question',
            'view_course_level',
            'add_course_level',
            'edit_course_level',
            'delete_course_level', 
        ];
    }
}
