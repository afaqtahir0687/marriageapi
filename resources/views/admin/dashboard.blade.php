@extends('admin.layouts.app')
@section('title')
<title>Dashboard | Marriage</title>
@endsection
@section('content')
<div class="row">
    <div class="col-md-6 col-xl-4">
        <div class="card stat-widget">
            <div class="card-body">
                <h5 class="card-title">New Users</h5>
                    <h2>{{ $users ?? 00 }}</h2>
                    <p>From last week</p>
                    <div class="progress">
                    <div class="progress-bar bg-info progress-bar-striped" role="progressbar" style="width: {{ $users ?? 00 }}%" aria-valuenow="{{ $users ?? 00 }}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-4">
        <div class="card stat-widget">
            <div class="card-body">
                <h5 class="card-title">Online</h5>
                    <h2>{{ $online ?? 0 }}</h2>
                    <p>Currently Online</p>
                    <div class="progress">
                    <div class="progress-bar bg-success progress-bar-striped" role="progressbar" style="width: {{ $online ?? 0 }}%" aria-valuenow="{{ $online ?? 0 }}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-4">
        <div class="card stat-widget">
            <div class="card-body">
                <h5 class="card-title">Monthly User</h5>
                    <h2>{{ $m_users ?? 0 }}</h2>
                    <p>For last 30 days</p>
                    <div class="progress">
                    <div class="progress-bar bg-danger progress-bar-striped" role="progressbar" style="width: {{ $m_users ?? 0 }}%" aria-valuenow="{{ $m_users ?? 0 }}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
            </div>
        </div>
    </div>
</div>
@endsection
