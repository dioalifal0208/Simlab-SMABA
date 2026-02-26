@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            <h1 class="text-2xl font-bold mb-4">Translation Test</h1>
            <p><strong>Locale:</strong> {{ app()->getLocale() }}</p>
            <p><strong>welcome.hero.title:</strong> {{ __('welcome.hero.title') }}</p>
            <p><strong>welcome.auth.login_btn:</strong> {{ __('welcome.auth.login_btn') }}</p>
        </div>
    </div>
</div>
@endsection
