@extends('theme::layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="bg-bg-main py-12">
        <div class="container mx-auto px-4">
            <h1 class="text-3xl font-bold mb-6">Xin chào, {{ Auth::user()->name }}!</h1>
            <div class="glass-card p-8">
                <p>Đây là trang tổng quan tài khoản của bạn.</p>
            </div>
        </div>
    </div>
@endsection
