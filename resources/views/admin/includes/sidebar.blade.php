<div class="vertical-menu">

    <div data-simplebar class="h-100">

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu"> 
                <li>
                    <a href="/admin-dashboard" class="waves-effect">
                        <i class="bx bx-home-circle"></i>
                        <span key="t-dashboards">Dashboards</span>
                    </a>
                </li> 
                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bx-border-radius"></i>
                        <span key="t-authentication">Manage Categories</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{ route('root-category.create') }}" key="t-login">Root Category</a></li> 
                        <li><a href="{{ route('sub-category.create') }}">Sub Category</a></li>
                        <li><a href="{{ route('sub-sub-category.create') }}">Sub Sub Category</a></li>
                    </ul>
                </li>
 
                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bx-slider-alt"></i>
                        <span key="t-authentication">Manage Slider</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{ route('ott-slider.create') }}" key="t-login">Create Slider</a></li>  
                        <li><a href="{{ route('ott-slider.index') }}" key="t-login">Slider List</a></li>  
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bx-slider-alt"></i>
                        <span key="t-authentication">Manage Content</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{ route('ott-content.create') }}" key="t-login">Create Content</a></li>  
                        <li><a href="{{ route('ott-content.index') }}" key="t-login">Content List</a></li>  
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bx-slider-alt"></i>
                        <span key="t-authentication">Manage Series</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{ route('ott-series.create') }}" key="t-login">Series</a></li>   
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bx-slider-alt"></i>
                        <span key="t-authentication">Manage Custom Section</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{ route('frontend-custom-content-section.create') }}" key="t-login">Custom Content Section</a></li>   
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bx-slider-alt"></i>
                        <span key="t-authentication">Manage Admins</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false"> 
                        <li><a href="{{ route('admin.create') }}" key="t-login">Create Admin</a></li>    
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bx-slider-alt"></i>
                        <span key="t-authentication">Manage Role</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false"> 
                        <li><a href="{{ route('roles.index') }}" key="t-login">Role List</a></li>    
                        <li><a href="{{ route('roles.create') }}" key="t-login">Role Create</a></li>    
                    </ul>
                </li>

                  
            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>
