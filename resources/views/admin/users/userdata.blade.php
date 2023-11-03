@extends('admin.layouts.app')
@section('title')
<title>User Detail | Marriage</title>
@endsection
@section('content')
<div class="row">
    <div class="col-xl-12">
        <div class="profile-cover"></div>
        <div class="profile-header">
            <div class="profile-img">
                @if (is_null($user->profile_pic))
                    <img src="{{ url('assets/images/avatars/profile-image.png') }}" alt="{{ $user->name }}">
                @else
                    <img src="{{ url('images/profile_picture_folder/').'/'.$user->profile_pic }}" alt="{{ $user->name }}">
                @endif
            </div>
            <div class="profile-name">
                <h3>{{ $user['name'] ?? '' }}</h3>
            </div>
            <div class="profile-header-menu">
                <ul class="list-unstyled">
                    <li><a href="{{ !is_null($user['socialAccount'])?$user['socialAccount']['facebook']:'' }}">Facebook</a></li>
                    <li><a href="{{ !is_null($user['socialAccount'])?$user['socialAccount']['google']:'' }}">google</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12 col-lg-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Contact Info</h5>
                <ul class="list-unstyled profile-about-list">
                    <li><i class="far fa-envelope m-r-xxs"></i><span>{{ $user['email'] ?? '' }}</span></li>
                    <li><i class="far fa-compass m-r-xxs"></i><span>Lives in <a href="#">{{ $user['location'] ?? 'No Where' }}</a></span></li>
                    <li><i class="far fa-address-book m-r-xxs"></i><span>{{ !is_null($user['socialAccount'])?$user['socialAccount']['phone_number']:'' }}</span></li>
                </ul>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">About</h5>
                <p>{{ $user['about_me'] ?? '' }}</p>
                <ul class="list-unstyled profile-about-list">
                    <li><i class="far fa-user m-r-xxs"></i><span>{{ $user['gender']?'Male':'Female' }}</span></li>
                    <li><i class="far fa-calendar m-r-xxs"></i><span>{{ $user['dob'] ?? '' }}</span></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-md-12 col-lg-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Blocked</h5>
                <div class="story-list">
                    @foreach($user->blockedUsers as $blocked)
                        <div class="story">
                            <a href="#">
                                @if (is_null($blocked->user->profile_pic))
                                    <img src="{{ url('assets/images/avatars/profile-image.png') }}" alt="">
                                @else
                                    <img src="{{ url('images/profile_picture_folder/').'/'.$blocked->user->profile_pic }}" alt="">
                                @endif
                            </a>
                            <div class="story-info">
                                <a href="#"><span class="story-author">{{  $blocked->user->name ?? ''}}</span></a>
                                <span class="story-time">{{  $blocked->user->email ?? ''}}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12 col-lg-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Liked</h5>
                <div class="story-list">
                    @foreach($user->likes as $like)
                        <div class="story">
                            <a href="#">
                                @if (is_null($like->user->profile_pic))
                                    <img src="{{ url('assets/images/avatars/profile-image.png') }}" alt="">
                                @else
                                    <img src="{{ url('images/profile_picture_folder/').'/'.$like->user->profile_pic }}" alt="">
                                @endif
                            </a>
                            <div class="story-info">
                                <a href="#"><span class="story-author">{{  $like->user->name ?? ''}}</span></a>
                                <span class="story-time">{{  $like->user->email ?? ''}}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
