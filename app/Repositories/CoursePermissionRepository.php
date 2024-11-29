<?php

namespace App\Repositories;

use App\Models\Course;
use App\User;

class CoursePermissionRepository
{
    /**
     * Check if user can edit the resource
     *
     * @param App\Models\Resource $course
     * @return boolean
     */
    public static function canEdit($course)
    {   
        if (!$user = auth()->user()) {
            return false;
        }
        
        // if ($user->isAdmin() || ( ($user->isUnescoManager() || $user->isManager())  && $course->is_locked != 1)
        //     || ($user->id == $course->user_id && $course->allow_edit == 1 && $course->is_locked != 1)) {
        //     return true;
        // }
        if ( $user->isAdmin() || 
             ( $user->isUnescoManager()  && ($course->user_id === auth()->user()->id) ) ||  
             ( $user->isManager()  && ($course->user_id === auth()->user()->id) ) ||
             ( $user->isTeacherEducator()  && ($course->user_id === auth()->user()->id) && $course->allow_edit && !$course->is_locked ) || 
             ( $course->collaborators && in_array(auth()->user()->id, $course->collaborators) && ( $course->allow_edit ) )
            ) {
            return true;
        }

        return false;
    }

    public static function canAdd($course)
    {   
        if (!$user = auth()->user()) {
            return false;
        }
        
        if ( $user->isAdmin() || 
             ( $user->isUnescoManager()  && ($course->user_id === auth()->user()->id) ) ||  
             ( $user->isManager()  && ($course->user_id === auth()->user()->id) ) ||
             ( $user->isTeacherEducator()  && ($course->user_id === auth()->user()->id) && !$course->is_locked ) || 
             ( $course->collaborators && in_array(auth()->user()->id, $course->collaborators) && ( !$course->is_locked ) )
            ) {
            return true;
        }

        return false;
    }

    /**
     * Check if user can approve the resource
     *
     * @param App\Models\Resource $course
     * @return boolean
     */
    public static function canApprove()
    {
        $user = auth()->user();

        if ($user->isAdmin() || $user->isUnescoManager() || $user->isManager()) {
            return true;
        }

        return false;
    }

    /**
     * Check if user can lock the resource
     *
     * @param App\Models\Resource $course
     * @return boolean
     */
    public static function canLock($course=null)
    {
        $user = auth()->user();

        if($course) {
            if ($user->isAdmin() || $course->user_id === $user->id) {
                return true;
            }
        } else {
            return $user->isAdmin() ? true : false;
        }
        
        return false;
    }

    /**
     * Check if user can publish the resource
     *
     * @param App\Models\Resource $course
     * @return boolean
     */
    public static function canPublish($course=null)
    {
        $user = auth()->user();

        if($course) {
            if ($user->isAdmin() || 
                ($user->isUnescoManager() && $user->id === $course->user_id) || 
                ($user->isManager() && $user->id === $course->user_id) 
            ) {
                return true;
            }
        } else {
            if ( $user->isAdmin() || $user->isUnescoManager()  || $user->isManager() ) {
                return true;
            }
        }

        return false;
    }
}
