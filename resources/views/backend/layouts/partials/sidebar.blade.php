<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="/{{ config('app.locale') }}/dashboard" class="brand-link">
      <img src="{{ asset('assets/img/logos/E_learning.png') }}" 
        alt="{{__('E-learning') }}" class="brand-image" width="32px" height="32px">&nbsp;
      <span class="brand-text font-weight-normal">|&nbsp;&nbsp;
        {{__('E-learning') }}
      </span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" 
            role="menu" data-accordion="false">
          <li class="nav-item">
            <a href="/{{ config('app.locale') }}/dashboard"
               class="nav-link {{ Request::is('*/backend')? 'active' : '' }}">
              <i class="nav-icon fas fa-home"></i>
              <p>{{ __('Dashboard') }}</p>
            </a>
          </li>
          @if(auth()->user()->isAdmin() || auth()->user()->isManager() || auth()->user()->isTeacherEducator())
            <li class="nav-header">{{__('REQUESTS & NOTIFICATIONS') }}</li>
          @elseif(auth()->user()->isStudentTeacher())
            <li class="nav-header">{{__('NOTIFICATIONS') }}</li>
          @endif
          @if(auth()->user()->isAdmin() || auth()->user()->isManager())  {{-- || auth()->user()->isTeacherEducator()) --}}
            <li class="nav-item">
              <a href="{{ route('member.course-approval-request.index') }}" 
              class="nav-link {{ Request::is('*/course-approval-request')? 'active' : '' }}">
              <i class="fas fa-fw fa-tasks"></i>
                <p>{{__('Approval Requests') }}</p>
              </a>
            </li>
          @endif
         
          <li class="nav-item">
            <a href="{{ route('member.notification.index') }}" 
            class="nav-link {{ Request::is('*/notification')? 'active' : '' }}">
                <i class="fas fa-fw fa-bell"></i>
              <p>{{__('Notifications') }}</p>
            </a>
          </li>
          @if(auth()->user()->isAdmin() || auth()->user()->isManager())
            <li class="nav-header">{{__('USER MANAGEMENT') }}</li>
            <li class="nav-item">
              <a href="{{ route('member.user.index') }}" 
              class="nav-link {{ Request::is('*/profile/user')? 'active' : '' }}">
              <i class="fas fa-fw fa-user"></i>
                <p>{{__('Users') }}</p>
              </a>
            </li>
          @endif
          @if(auth()->user()->isAdmin() || auth()->user()->isManager() || auth()->user()->isTeacherEducator())
          <li class="nav-header">{{__('COURSE MANAGEMENT') }}</li>
          <li class="nav-item">
            <a href="{{ route('member.course.index') }}" 
            class="nav-link {{ Request::is('*/profile/course')? 'active' : '' }}">
            <i class="fas fa-fw fa-file"></i>
              <p>{{__('Courses') }}</p>
            </a>
          </li>
          @endif
          @if(auth()->user()->isAdmin())
            <li class="nav-item">
              <a href="{{ route('admin.course-category.index') }}" 
              class="nav-link {{ Request::is('*/admin/course-category')? 'active' : '' }}">
                  <i class="fas fa-fw fa-folder"></i>
                <p>{{ __('Course Categories') }}</p>
              </a>
            </li>
          
            <li class="nav-item">
              <a href="{{ route('admin.course-type.index') }}" 
              class="nav-link {{ Request::is('*/admin/course-type')? 'active' : '' }}">
                  <i class="fas fa-fw fa-border-style"></i>
                <p>{{ __('Course Types') }}</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('admin.course-level.index') }}" 
              class="nav-link {{ Request::is('*/admin/course-level')? 'active' : '' }}">
                  <i class="fas fa-fw fa-layer-group"></i>
                <p>{{ __('Course Levels') }}</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('admin.course-evaluation.index') }}" 
              class="nav-link {{ Request::is('*/admin/course-evaluation')? 'active' : '' }}">
                  <i class="fas fa-fw fa-check"></i>
                <p>{{ __('Course Evaluation') }}</p>
              </a>
            </li>
            <li class="nav-header">{{__('CMS') }}</li>
            <li class="nav-item menu-is-opening menu-open"> <!-- remove menu-is-opening menu-open to close the menu -->
              <a href="#" class="nav-link {{ Request::is('*/admin/page*')? 'active' : '' }}">
                <i class="nav-icon fas fa-table"></i>
                <p>{{__('CMS') }}
                  <i class="fas fa-angle-left right"></i>
                </p>
              </a>
              <ul class="nav nav-item">             
                <li class="nav-item">
                  <a href="{{ route('admin.college.index') }}" 
                  class="nav-link {{ Request::is('*/admin/college*')? 'active' : '' }}">
                    <i class="fas fa-fw fa-copy"></i>
                    <p>{{ __('Education Colleges') }}</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{ route('admin.year.index') }}" class="nav-link {{ Request::is('*/admin/year*')? 'active' : '' }}">
                    <i class="fas fa-fw fa-copy"></i>
                    <p>{{ __('Years') }}</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{ route('admin.page.index') }}" class="nav-link {{ Request::is('*/admin/page*')? 'active' : '' }}">
                    <i class="fas fa-fw fa-copy"></i>
                    <p>{{ __('Pages') }}</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{ route('admin.slide.index') }}" class="nav-link {{ Request::is('*/admin/slide*')? 'active' : '' }}">
                    <i class="fas fa-th"></i>
                    <p>{{ __('Slides') }}</p>
                  </a>
                </li> 
                <li class="nav-item">
                  <a href="{{ route('admin.contact.index') }}" class="nav-link {{ Request::is('*/admin/contact*')? 'active' : '' }}">
                    <i class="fas fa-th"></i>
                    <p>{{ __('Contact Messages') }}</p>
                  </a>
                </li> 
                <li class="nav-item">
                  <a href="{{ route('admin.article.index') }}" class="nav-link {{ Request::is('*/admin/article') || Request::is('*/admin/article/*')? 'active' : '' }}">
                    <i class="far fa-newspaper"></i>
                    <p>{{ __('Articles') }}</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{ route('admin.article-category.index') }}" class="nav-link {{ Request::is('*/admin/article-category*')? 'active' : '' }}">
                    <i class="fas fa-layer-group"></i>
                    <p>{{ __('Article Categories') }}</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{ route('admin.faq.index') }}" class="nav-link {{ Request::is('*/admin/faq') || Request::is('*/admin/faq/*')? 'active' : '' }}">
                  <i class="fas fa-fw fa-question-circle"></i>
                    <p>{{ __('FAQs') }}</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{ route('admin.faq-category.index') }}" class="nav-link {{ Request::is('*/admin/faq-category*')? 'active' : '' }}">
                  <i class="fas fa-layer-group"></i>
                    <p>{{ __('FAQ Categories') }}</p>
                  </a>
                </li> 
                @can('import_user')
                <li class="nav-item">
                  <a href="{{ route('admin.user.bulk-import') }}" class="nav-link {{ Request::is('*/admin/import/user*')? 'active' : '' }}">
                  <i class="fas fa-fw fa-people-arrows"></i>
                    <p>{{ __('Import Users') }}</p>
                  </a>
                </li>
                @endcan
                <li class="nav-item">
                  <a href="{{ route('admin.user.index') }}" class="nav-link {{ Request::is('*/admin/user*')? 'active' : '' }}">
                  <i class="fas fa-fw fa-users"></i>
                    <p>{{ __('Users') }}</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{ route('admin.role.index') }}" class="nav-link {{ Request::is('*/admin/role*')? 'active' : '' }}">
                  <i class="fas fa-fw fa-address-book"></i>
                    <p>{{ __('Roles') }}</p>
                  </a>
                </li>
              </ul>
            </li>
          @endif
          
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>