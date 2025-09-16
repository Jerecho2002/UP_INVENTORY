@extends('layouts.app')

@section('content')
<div class="bg-white p-6 rounded shadow">
    <h1 class="text-xl font-semibold mb-4">User Details</h1>
    <div class="mb-2"><strong>Name:</strong> {{ $user['name'] }}</div>
    <div class="mb-4"><strong>Email:</strong> {{ $user['email'] }}</div>
    <a href="{{ route('users.index') }}" class="text-blue-500 hover:underline">Back to list</a>
</div>
@endsection
