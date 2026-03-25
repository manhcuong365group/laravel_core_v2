@extends('theme::layouts.app')

@section('title', 'Hồ sơ')

@section('content')
    <div class="bg-bg-main py-12">
        <div class="container mx-auto px-4">
            <h1 class="text-3xl font-bold mb-6">Hồ sơ cá nhân</h1>
            <div class="glass-card p-8">
                <p><strong>Tên:</strong> {{ $user->name }}</p>
                <p><strong>Email:</strong> {{ $user->email }}</p>
                <!-- Add edit form here later -->
            </div>
        </div>
    </div>
@endsection
