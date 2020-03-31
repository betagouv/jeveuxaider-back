<!-- This file is used to store sidebar items, starting with Backpack\Base 0.9.0 -->
<li><a href="{{ backpack_url('dashboard') }}"><i class="fa fa-dashboard"></i> <span>{{ trans('backpack::base.dashboard') }}</span></a></li>
<!-- <li><a href='{{ backpack_url('structure') }}'><i class='fa fa-building'></i> <span>Structures</span></a></li>
<li><a href='{{ backpack_url('mission') }}'><i class='fa fa-list-ul'></i> <span>Missions</span></a></li>
<li><a href='{{ backpack_url('user') }}'><i class='fa fa-users'></i> <span>Users</span></a></li>
<li><a href='{{ backpack_url('profile') }}'><i class='fa fa-user-circle'></i> <span>Profiles</span></a></li> -->
<li><a href='{{ backpack_url('release') }}'><i class='fa fa-info-circle'></i> <span>Releases</span></a></li>
<li><a href='{{ url(config('backpack.base.route_prefix', 'admin').'/log') }}'><i class='fa fa-terminal'></i> <span>Logs</span></a></li>
<!-- <li><a href='{{ backpack_url('elfinder') }}'><i class='fa fa-files-o'></i> <span>File manager</span></a></li> -->
