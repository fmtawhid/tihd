<div class="logo-default">
    <a class="navbar-brand text-primary" href="{{route('user.login')}}" style="padding: 10px;">

        @php
            $logo=GetSettingValue('dark_logo') ??  asset(setting('dark_logo'));
        @endphp


        <img class="img-fluid logo atv" src="{{ $logo }}" alt="streamit">
    </a>
</div>
