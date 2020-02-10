@extends('layouts.app')

@push('style')
.content {
text-align: center;
}
.title {
color: #066;
margin: 20px auto 0;
font-size: 94px;
line-height: 100px;
}
.subtitle {
color: #088;
margin: 0 auto 30px;
font-size: 54px;
line-height: 60px;
}
.links {
font-size: 32px;
}
.todo {
width: 320px;
font-size: 18px;
margin: 10px auto 30px auto;
}
.glow {
transition: font-size .3s;
color: #FEF;
text-shadow: 0 0 10px #fff, 0 0 20px #fff, 0 0 30px #e60073, 0 0 40px #e60073, 0 0 50px #e60073, 0 0 60px #e60073, 0 0 70px #e60073;
}
.glow:hover {
text-decoration: none;
color: #FFF;
font-size: 49px;
text-shadow: 0 0 20px #fff, 0 0 30px #ff4da6, 0 0 40px #ff4da6, 0 0 50px #ff4da6, 0 0 60px #ff4da6, 0 0 70px #ff4da6, 0 0 80px #ff4da6;
}
@endpush

@section('content')
    <div class="container content">
        <div class="title">
            Praxam.
        </div>
        <div class="subtitle">
            The Laravel Quiz Editor
        </div>
        <div class="todo">
            <b>Still to do:</b><br>Create a beautiful Welcome Page<br>that Will make You want to Register<br>and finally behold...
        </div>
        <div class="links">
            <a href="/home" class="glow">&nbsp;Your Home Page&nbsp;</a>
        </div>
    </div>
@endsection


