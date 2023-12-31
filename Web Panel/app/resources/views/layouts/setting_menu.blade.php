<div class="card-body border-bottom pb-0">
    <ul class="nav nav-tabs analytics-tab" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
            <a href="general" class="nav-link {{ request()->segment(3) === 'general' ? 'active' : '' }}" type="button" role="tab" aria-selected="false" tabindex="-1"><i class="ti ti-settings"></i>&nbsp;&nbsp;{{__('setting-general-menu')}}</a>
        </li>
        <li class="nav-item" role="presentation">
            <a href="https://github.com/Alirezad07/Xcs-Multi-Management-XPanel" target="_blank" class="nav-link" type="button" role="tab" aria-selected="false" tabindex="-1"><i class="ti ti-box-multiple"></i>&nbsp;&nbsp;{{__('setting-multiserver-menu')}}</a>
        </li>
        <li class="nav-item" role="presentation">
            <a href="backup" class="nav-link {{ request()->segment(3) === 'backup' ? 'active' : '' }}" type="button" role="tab" aria-selected="false" tabindex="-1"><i class="ti ti-database"></i>&nbsp;&nbsp;{{__('setting-backup-menu')}}</a>
        </li>
        <li class="nav-item" role="presentation">
            <a href="api" class="nav-link {{ request()->segment(3) === 'api' ? 'active' : '' }}" type="button" role="tab" aria-selected="false" tabindex="-1"><i class="ti ti-puzzle"></i>&nbsp;&nbsp;{{__('setting-api-menu')}}</a>
        </li>
        <li class="nav-item" role="presentation">
            <a href="block" class="nav-link {{ request()->segment(3) === 'block' ? 'active' : '' }}" type="button" role="tab" aria-selected="false" tabindex="-1"><i class="ti ti-hand-stop"></i>&nbsp;&nbsp;{{__('setting-blockip-menu')}}</a>
        </li>
        <li class="nav-item" role="presentation">
            <a href="fakeaddress" class="nav-link {{ request()->segment(3) === 'fakeaddress' ? 'active' : '' }}" type="button" role="tab" aria-selected="false" tabindex="-1"><i class="ti ti-planet"></i>&nbsp;&nbsp;{{__('setting-fake-menu')}}</a>
        </li>

        <li class="nav-item" role="presentation">
            <a href="wordpress" class="nav-link {{ request()->segment(3) === 'wordpress' ? 'active' : '' }}" type="button" role="tab" aria-selected="false" tabindex="-1"><i class="fab fa-wpexplorer"></i>&nbsp;&nbsp;{{__('setting-wordpress-menu')}}</a>
        </li>
        <li class="nav-item" role="presentation">
            <a href="xguard" class="nav-link {{ request()->segment(3) === 'xguard' ? 'active' : '' }}" type="button" role="tab" aria-selected="false" tabindex="-1"><i class="ti ti-shield"></i>&nbsp;&nbsp;{{__('settings-xguard-title')}}</a>
        </li>
    </ul>
</div>
