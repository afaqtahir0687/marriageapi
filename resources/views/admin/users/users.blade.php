@extends('admin.layouts.app')
@section('title')
<title>User List | Marriage</title>
@endsection
@section('css')
    <link href="{{ url('assets/plugins/DataTables/datatables.min.css') }}" rel="stylesheet">
    <style>
        table
        {
            display: block;
            overflow-x: auto;
            white-space: nowrap;
        }
        
    </style>
@endsection
@section('content')
<div class="row">
    <div class="col">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Users List</h5>
                <table id="zero-conf" class="display table table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>Profile</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Gender</th>
                            <th>DOB</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user )
                        <tr>
                            <td>
                                @if (is_null($user->profile_pic))
                                    <img  src="{{ url('assets/images/avatars/profile-image.png') }}" style="width: 100%;" alt="{{ $user->name }}">
                                @else
                                    <img src="{{ url('images/profile_picture_folder/').'/'.$user->profile_pic }}" style="width: 100%;" alt="{{ $user->name }}">
                                @endif
                            </td>
                            <td>{{ $user['name'] ?? '' }}</td>
                            <td>{{ $user['email'] ?? '' }}</td>
                            <td>{{ !is_null($user['socialAccount'])?$user['socialAccount']['phone_number']:'' }}
                            <td>{{ $user['gender'] ?? '' }}</td>
                            <td>{{ $user['dob'] ?? '' }}</td>
                            <td>
                            <div class="btn-group" role="group" aria-label="Basic outlined example">
                                <a href="{{ url('/users')}}/{{ $user->id }}" class="btn btn-outline-primary">Detail</a>
                                <a href="{{ url('/users/block')}}/{{ $user->id }}/{{ $user->status == 1 ? '0' : '1' }}" class="btn btn-outline-primary" data-toggle="modal" data-target="#ban-modal-{{ $user->id }}">
                                    {{ $user->status == 1 ? 'Block' : 'Unblock' }}
                                </a>
                                <a href="{{ url('/users/hide')}}/{{ $user->id }}/{{ $user->hide == 1 ? '0' : '1' }}" class="btn btn-outline-primary">{{ $user->hide == 0 ? 'Unhide' : 'Hide' }}</a>
                               
                            </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
    <script src="{{ url('assets/plugins/DataTables/datatables.min.js') }}"></script>
    <script src="{{ url('assets/js/pages/datatables.js') }}"></script>
@endsection
