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
            <a href="ip-adapter" class="nav-link {{ request()->segment(3) === 'ip-adapter' ? 'active' : '' }}" type="button" role="tab" aria-selected="false" tabindex="-1"><i class="material-icons-two-tone text-warning pc-icon-check" style="font-size: 15px;">star</i> &nbsp;{{__('ip-adapter-change')}} </a>
        </li>
        <li class="nav-item" role="presentation">
            <a href="mail" class="nav-link {{ request()->segment(3) === 'mail' ? 'active' : '' }}" type="button" role="tab" aria-selected="false" tabindex="-1"><i class="material-icons-two-tone text-warning pc-icon-check" style="font-size: 15px;">star</i> &nbsp;{{__('mail-setting-title')}} </a>
        </li>
        <li class="nav-item" role="presentation">
            <a href="license" class="nav-link {{ request()->segment(3) === 'license' ? 'active' : '' }}" type="button" role="tab" aria-selected="false" tabindex="-1"><i class="material-icons-two-tone text-warning pc-icon-check" style="font-size: 15px;">star</i> &nbsp;{{__('premium')}} </a>
        </li>
        <li class="nav-item" role="presentation">
            <a href="cronjob" class="nav-link {{ request()->segment(3) === 'cronjob' ? 'active' : '' }}" type="button" role="tab" aria-selected="false" tabindex="-1"><i class="ti ti-recharging"></i>&nbsp;{{__('setting-crontab-title')}}</a>
        </li>
    </ul>
</div>
