@extends('layouts.app')

@section('content')
<div class="bg-white p-6 rounded shadow">
    <h1 class="text-xl font-semibold mb-4">Create User</h1>
    <form action="{{ route('users.store') }}" method="POST">
        @csrf
        @include('users._form')
        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">Save</button>
    </form>
</div>
@endsection
