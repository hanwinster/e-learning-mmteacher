<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Repositories\ResourceRepository;
use App\Repositories\UserRepository;
use App\Repositories\CollegeRepository;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        //return parent::toArray($request);
        $years = ResourceRepository::getEducationCollegeYears();

        $ec_years = [];
        if ($this->suitable_for_ec_year) {
            if (strpos($this->suitable_for_ec_year, ',') !== false) {
                $ec_years = explode(',', $this->suitable_for_ec_year);
            } else {
                $ec_years[] = $this->suitable_for_ec_year;
            }
        }

        $year_study = [];
        foreach ($ec_years as $ec_year) {
            $year_study[] = array_key_exists($ec_year, $years) ? $years[$ec_year] : '';
        }

        $user_types = UserRepository::getUserTypes(false);

        $ec_colleges = CollegeRepository::getItemList(false);

        $subjects = [];
        foreach ($this->subjects as $subject) {
            $subjects[] = ['id' => $subject->id, 'title' => $subject->title, 'slug' => $subject->slug];
        }

        $showTotalResources = false;
        $showApprovalRequests = false;
        $showManageResources = false;
        $showManageUsers = false;
        $showManageArticles = false;
        $showViewUsers = false;

        if ($this->isAdmin() || $this->isManager() || $this->isTeacherEducator()) {
            $showTotalResources = true;
            $showManageResources = true;
        }

        if ($this->isAdmin() || $this->isManager()) {
            $showApprovalRequests = true;
            $showManageUsers = true;
            $showManageArticles = true;
        }

        if ($this->isTeacherEducator()) {
            $showViewUsers = true;
        }

        $default_thumbnail = asset('assets/img/avatar.png');

        return [
            'id' => $this->id,
            'name' => $this->name,
            'username' => $this->username,
            'email' => $this->email,
            'gender' => $this->gender,
            'mobile_no' => $this->mobile_no,
            'notification_channel' => $this->notification_channel,
            'type_name' => $this->getType(),
            'type' => $this->type,
            'user_type' => $this->user_type,
            'user_type_name' => $this->when(($this->user_type && isset($user_types[$this->user_type])), $user_types[$this->user_type] ?? ''),
            'ec_college' => $this->ec_college,
            'ec_college_name' => ($this->ec_college != null && isset($ec_colleges[$this->ec_college])) ? $ec_colleges[$this->ec_college] : null,
            'suitable_for_ec_year' => $year_study,
            'organization' => $this->organization,
            'affiliation' => $this->affiliation, 
            'position' => $this->position,
            'country_code' => $this->country_code,
            'subjects' => $subjects,
            'thumb_image' => ($this->getProfileImagePath('large')) ? asset($this->getProfileImagePath('large')) : $default_thumbnail,
            'approved' => $this->approved,
            'verified' => $this->verified,
            'blocked' => $this->blocked,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'email_verified_at' => $this->email_verified_at,
            'sms_verified_at' => $this->sms_verified_at,
            //'subscribe_to_new_resources' => $this->subscribe_to_new_resources,
            // 'flags' => [
            //     'show_total_resources_in_dashboard_page' => (int)$showTotalResources,
            //     'show_approval_requests_page' => (int)$showApprovalRequests,
            //     'show_manage_resources_page' => (int)$showManageResources,
            //     'show_manage_users_page' => (int)$showManageUsers,
            //     'show_manage_articles_page' => (int)$showManageArticles,
            //     'show_view_users_page' => (int)$showViewUsers
            // ],
        ];
    }
}
