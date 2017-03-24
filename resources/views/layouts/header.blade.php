<div class="top_nav">
    <div class="nav_menu">
        <nav>
            <div class="nav toggle">
                <a id="menu_toggle"><i class="fa fa-bars"></i></a>
            </div>

            <ul class="nav navbar-nav navbar-right">
                
                <li class="">
                    <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                        <img src="{{asset('/resources/images/user.png')}}" alt=""> {{Auth::user()->username}}
                        <span class=" fa fa-angle-down"></span>
                    </a>
                    <ul class="dropdown-menu dropdown-usermenu pull-right">
                        <li><a href="{{'/site/profile'}}"> {{trans('general.profile')}} </a></li>
                        <li>
                            <a href="{{ route('logout') }}"
                               onclick="event.preventDefault();
                               document.getElementById('logout-form').submit();">
                                    <i class="fa fa-sign-out pull-right"></i> {{trans('general.logout')}}
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
                            </form>
                        </li>
                    </ul>
                </li>       
                
                <li class="">
                    <?php
                        $locale = Session::get('locale', Config::get('app.fallback_locale'));                        
                        $flags = Config::get('app.locales_flag');
                        $locates = Config::get('app.locales');
                    ?>
                    <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                        <img alt="" class="lang-img" src="{{ asset('/resources/images/flags/' . $flags[$locale]) }}" />
                        <span class="langname"> {{ $locates[$locale] }} </span>
                        <i class="fa fa-angle-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-default">
                        @foreach($locates as $key=>$val)
                        <li>
                            <a class="lang-option" data-lang-name="{{$val}}" data-locale="{{$key}}" href="javascript:;">
                                <img src="{{ asset('/resources/images/flags/' . $flags[$key]) }}"> {{$val}}
                            </a>
                        </li>     
                        @endforeach
                    </ul>
                </li>                                           
            </ul>
        </nav>
    </div>
</div>