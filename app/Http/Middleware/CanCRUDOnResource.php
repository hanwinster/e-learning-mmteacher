<?php

namespace App\Http\Middleware;

use Closure;
use App\Repositories\ResourcePermissionRepository;
use App\Models\Resource;

class CanCRUDOnResource
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $method = strtolower($request->method());
        $resource = Resource::find($request->route('resource'));

        if ($method == 'delete') {
            if (!ResourcePermissionRepository::canEdit($resource)) {
                return redirect()->route('member.dashboard')
                ->with('error', __('You don\'t have permission to delete the resource #' . $resource->id . '.'));
            }
        }

        if ($method == 'put' || $method == 'patch') {
            if (!ResourcePermissionRepository::canEdit($resource)) {
                return redirect()->route('member.dashboard')
                ->with('error', __('You don\'t have permission to update the resource #' . $resource->id . '.'));
            }
        }

        if ($request->is('*/profile/resource/*/edit')) {
            if (!ResourcePermissionRepository::canEdit($resource)) {
                return redirect()->route('member.dashboard')
                ->with('error', __('You don\'t have permission to edit the resource #'.$resource->id.'.'));
            }
        }

        return $next($request);
    }
}
