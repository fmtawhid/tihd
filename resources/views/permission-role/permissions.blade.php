@extends('backend.layouts.app')

@section('title')
{{ __($module_action) }} {{ __($module_title) }}
@endsection

@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <div class="header-title">
                    <h4 class="card-title mb-0">{{ __('messages.permission_role') }}</h4>
                </div>
                <div>
                    <x-backend.section-header>
                        <div></div>
                        <x-slot name="toolbar">
                            <div class="input-group flex-nowrap"></div>
                        </x-slot>
                    </x-backend.section-header>
                </div>
            </div>
            <div class="card-body"> 
                @foreach ($roles as $role)
                    @if ($role->name !== 'admin')
                        {{ html()->form('post', route('backend.permission-role.store', $role->id))->open() }}

                        <div class="permission-collapse border rounded p-3 mb-3" id="permission_{{$role->id}}">
                            <div class="d-flex align-items-center justify-content-between">
                                <h6>{{ ucfirst($role->title) }}</h6>
                                <div class="toggle-btn-groups">
                                    @if ($role->is_fixed == 0)
                                        <button class="btn btn-danger" type="button" onclick="delete_role({{$role->id}})">
                                            Delete
                                        </button>
                                    @endif
                                    <button class="btn btn-primary ms-2" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseBox1_{{$role->id}}" aria-expanded="false">
                                        {{ __('messages.permission') }}
                                    </button>
                                </div>
                            </div>

                            <div class="collapse pt-3" id="collapseBox1_{{$role->id}}">
                                <div class="table-responsive">
                                    <table class="table table-condensed table-striped mb-0">
                                        <thead class="sticky-top">
                                            <tr>
                                                <th>{{ __('messages.modules') }}</th>
                                                <th>{{ __('messages.view') }}</th>
                                                <th>{{ __('messages.add') }}</th>
                                                <th>{{ __('messages.edit') }}</th>
                                                <th>{{ __('messages.delete') }}</th>
                                                <th>{{ __('messages.restore') }}</th>
                                                <th>{{ __('messages.force_delete') }}</th>
                                                <th class="text-end">
                                                    {{ html()->submit(__('messages.save'))->class('btn btn-md btn-secondary') }}
                                                </th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @foreach ($modules as $mKey => $module)
                                                @php
                                                    $moduleName = $module['module_name'] ?? 'unknown';
                                                    $slug = strtolower(str_replace(' ', '_', $moduleName));
                                                    $permissionsList = [
                                                        'view' => "view_{$slug}",
                                                        'add' => "add_{$slug}",
                                                        'edit' => "edit_{$slug}",
                                                        'delete' => "delete_{$slug}",
                                                        'restore' => "restore_{$slug}",
                                                        'force_delete' => "force_delete_{$slug}",
                                                    ];
                                                    $isCustom = $module['is_custom_permission'] ?? false;
                                                @endphp
                                                <tr>
                                                    <td>{{ ucwords($moduleName) }}</td>

                                                    @foreach (['view', 'add', 'edit', 'delete', 'restore', 'force_delete'] as $action)
                                                        @php
                                                            $permName = $permissionsList[$action];
                                                            $existsInDB = in_array($permName, $allAvailablePermissions);
                                                        @endphp
                                                        <td>
                                                            @if (!$isCustom && $existsInDB)
                                                                <input type="checkbox"
                                                                    name="permission[{{ $permName }}][]"
                                                                    value="{{ $role->name }}"
                                                                    class="form-check-input"
                                                                    {{ AuthHelper::checkRolePermission($role, $permName) ? 'checked' : '' }}>
                                                            @else
                                                                <span style="color:red; font-weight:bold;">X</span>
                                                            @endif
                                                        </td>
                                                    @endforeach

                                                    @if (isset($module['more_permission']) && is_array($module['more_permission']))
                                                        <td class="text-end">
                                                            <a data-bs-toggle="collapse" data-bs-target="#demo_{{$mKey}}" class="accordion-toggle btn btn-primary btn-xs">
                                                                <i class="fa-solid fa-chevron-down me-2"></i>{{ __('messages.more') }}
                                                            </a>
                                                        </td>
                                                    @else
                                                        <td></td>
                                                    @endif
                                                </tr>

                                                @if (isset($module['more_permission']) && is_array($module['more_permission']))
                                                    <tr>
                                                        <td colspan="12" class="hiddenRow">
                                                            <div class="accordian-body collapse" id="demo_{{$mKey}}">
                                                                <table class="table table-striped mb-0">
                                                                    <tbody>
                                                                        @foreach ($module['more_permission'] as $permission_data)
                                                                            @php
                                                                                $customPerm = strtolower(str_replace(' ', '_', $moduleName)) . '_' . strtolower(str_replace(' ', '_', $permission_data));
                                                                            @endphp
                                                                            <tr>
                                                                                <td class="d-flex justify-content-center">
                                                                                    {{ ucwords($moduleName) }} {{ ucwords(str_replace('_', ' ', $permission_data)) }}
                                                                                    <span class="ms-5">
                                                                                        <div class="form-check form-switch">
                                                                                            <input type="checkbox"
                                                                                                name="permission[{{ $customPerm }}][]"
                                                                                                value="{{ $role->name }}"
                                                                                                class="form-check-input"
                                                                                                {{ AuthHelper::checkRolePermission($role, $customPerm) ? 'checked' : '' }}>
                                                                                        </div>
                                                                                    </span>
                                                                                </td>
                                                                            </tr>
                                                                        @endforeach
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        {{ html()->form()->close() }}
                    @endif
                @endforeach
            </div>
        </div>

        <div data-render="app">
            <manage-role-form create-title="{{ __('messages.create') }} {{ __('page.lbl_role') }}"></manage-role-form>
        </div>
    </div>
</div>

<script>
function delete_role(role_id) {
    var url = "{{ route('backend.role.destroy', ['role' => ':role_id']) }}".replace(':role_id', role_id);
    $.ajax({
        url: url,
        type: 'DELETE',
        dataType: 'json',
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function(response) {
            $('#permission_' + role_id).hide();
            successSnackbar(response.message);
        },
        error: function() { alert('error'); }
    });
}
</script>

<style>
.permission-collapse table tr td.hiddenRow { padding: 0; }
.permission-collapse table tr td.hiddenRow table td { padding: 20px; }
.permission-collapse table tr td.hiddenRow table tr:last-child td { border: none; }
</style>
@endsection
