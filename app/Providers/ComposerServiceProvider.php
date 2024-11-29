<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
//use App\Models\Block;
use App\Models\EventCategory;
use App\User;
//use App\Models\DirectoryCategory;
use App\Repositories\BlockRepository;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        // Using class based composers...
        /*         View::composer(
                    'profile', 'App\Http\ViewComposers\ProfileComposer'
                ); */

        // Using Closure based composers...
        View::composer('frontend.member.partials.sidebar', function ($view) {
            $links = collect([
                [
                    'route' => 'member.dashboard',
                    'url' => 'dashboard',
                    'name' => __('Dashboard')
                ],
                [
                    'route' => 'member.approval-request.index',
                    'url' => 'approval-request',
                    'name' => __('Approval Requests')
                ],
                [
                    'route' => 'member.course-approval-request.index',
                    'url' => 'course-approval-request',
                    'name' => __('Course Approval Requests')
                ],
                [
                    'route' => 'member.notification.index',
                    'url' => 'notifications',
                    'name' => __('Notifications')
                ],
                [
                    'route' => 'member.favourite.index',
                    'url' => 'my-favourite',
                    'name' => __('My Favourites')
                ],
                [
                    'route' => 'member.resource.index',
                    'url' => 'profile/resource',
                    'name' => __('Manage Resources')
                ],
                [
                    'route' => 'member.course.index',
                    'url' => 'profile/course',
                    'name' => __('Manage Courses')
                ],
                [
                    'route' => 'member.article.index',
                    'url' => 'profile/article',
                    'name' => __('Manage Articles')
                ],
                [
                    'route' => 'member.user.index',
                    'url' => 'profile/user',
                    'name' => __('Manage Users')
                ],
                [
                    'route' => 'member.view-user.index',
                    'url' => 'profile/view-user',
                    'name' => __('View Users')
                ],
                [
                    'route' => 'member.profile.edit',
                    'url' => 'profile',
                    'name' => __('Profile')
                ],
                [
                    'route' => 'member.change-password.edit',
                    'url' => 'change-password',
                    'name' => __('Change Password')
                ],
            ]);

            // Manage Resources is only for Admins, Managers & Teacher Educators.
            // If not, don't show in menu list.

            if (!(auth()->user()->type == User::TYPE_ADMIN
                || auth()->user()->type == User::TYPE_MANAGER
                || auth()->user()->type == User::TYPE_TEACHER_EDUCATOR)) {
                //unset($links[4]);
                $links = $links->filter(function ($item) {
                    return $item['route'] != 'member.resource.index';
                });
            }

            // Approval Requests and Manage Users are only for Admin and Manager users.
            // If not, don't show in menu list.
            if (!(auth()->user()->type == User::TYPE_ADMIN || auth()->user()->type == User::TYPE_MANAGER)) {
                //unset($links[1]);
                //unset($links[5]);

                $links = $links->filter(function ($item) {
                    return ($item['route'] != 'member.approval-request.index'
                        // && $item['route'] != 'member.resource.index'
                        && $item['route'] != 'member.course-approval-request.index'
                        && $item['route'] != 'member.user.index'
                        && $item['route'] != 'member.article.index'
                    );
                });
            }

            // View User is only for Teacher Educators
            if (auth()->user()->type != User::TYPE_TEACHER_EDUCATOR) {
                $links = $links->filter(function ($item) {
                    return $item['route'] != 'member.view-user.index';
                });
                //unset($links[4]);
            }

            $view->with('links', $links);
        });

        View::composer('frontend.event.partials.categories', function ($view) {
            $categories = EventCategory::isPublished()->get();
            $view->with('categories', $categories);
        });

        /*         // Show Visible Blocks Only
                View::composer('frontend.partials.block', function ($view) {
                    $blocks = BlockRepository::getVisibleBlocks();

                    $view->with('blocks', $blocks);
                }); */
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
