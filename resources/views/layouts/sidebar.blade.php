<div class="left_col">
    <div class="left_col scroll-view">
        <div class="navbar nav_title" style="border: 0;">
            <a href="{{ url('/') }}" class="site_title"><i class="fa fa-film"></i> <span>MoviesCreed</span></a>
        </div>

        <div class="clearfix"></div>
        <!-- menu profile quick info -->
        <div class="profile" onclick="window.location='{{asset('/site/profile')}}'">
            <div class="profile_pic">
                <img src="{{asset('/resources/images/user.png')}}" alt="..." class="img-circle profile_img">
            </div>
            <div class="profile_info">
                <span>{{trans('general.welcome')}},</span>
                <h2>{{Auth::user()->username}}</h2>
            </div>
            <div class="clearfix"></div>
        </div>        
        <!-- /menu profile quick info -->

        <br />

        <!-- sidebar menu -->
        <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
            <div class="menu_section">
                <ul class="nav side-menu">
                    @if(Auth::user()->can('rbac-*'))
                    <li>
                        <a>
                            <i class="fa fa-users"></i>
                            {{trans('general.role_base')}}
                            <span class="fa fa-chevron-down"></span>
                        </a>
                        <ul class="nav child_menu">
                            @if(Auth::user()->can('rbac-user-*'))
                            <li>
                                <a href="{{ url('/admin/rbac/users') }}">
                                    {{trans('general.users')}}
                                </a>
                            </li>
                            @endif
                            
                            @if(Auth::user()->can('rbac-role-*'))
                            <li>
                                <a href="{{ url('/admin/rbac/roles') }}">
                                    {{trans('general.roles')}}
                                </a>
                            </li>
                            @endif
                            
                            @if(Auth::user()->can('rbac-permission-*'))
                            <li>
                                <a href="{{ url('/admin/rbac/permissions') }}">
                                    {{trans('general.permissions')}}
                                </a>
                            </li>
                            @endif
                            
                            @if(Auth::user()->can('rbac-group-*'))
                            <li>
                                <a href="{{ url('/admin/rbac/groups') }}">
                                    {{trans('general.groups')}}
                                </a>
                            </li>
                            @endif
                        </ul>
                    </li> 
                    @endif

                    @if(Auth::check())
                        <li>
                            <a>
                                <i class="fa fa-film"></i>
                                {{trans('general.utilities')}}
                                <span class="fa fa-chevron-down"></span>
                            </a>
                            <ul class="nav child_menu">
                                <li>
                                    <a href="{{ url('/utilities/pdf_reader') }}">
                                        {{trans('general.pdf_reader')}}
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endif
                </ul>
            </div>            

        </div>
        <!-- /sidebar menu -->

        <!-- /menu footer buttons -->
        <div class="sidebar-footer hidden-small">
            <a data-toggle="tooltip" data-placement="top" title="Settings">
                <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
            </a>
            <a data-toggle="tooltip" data-placement="top" title="FullScreen">
                <span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span>
            </a>
            <a data-toggle="tooltip" data-placement="top" title="Lock">
                <span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span>
            </a>
            <a data-toggle="tooltip" data-placement="top" title="Logout">
                <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
            </a>
        </div>
        <!-- /menu footer buttons -->
    </div>
</div>