<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Users
            </h2>
            <div class="flex items-center gap-2">
                <form method="GET" action="{{ route('users.index') }}" class="relative">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search for user name"
                        class="rounded-full border-gray-300 shadow-sm pl-4 pr-10 py-2 focus:ring-2 focus:ring-blue-400">

                </form>

            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm rounded-xl overflow-hidden">
                @if(session('success'))
                    <div class="px-6 py-4 text-green-600 font-medium">{{ session('success') }}</div>
                @endif
                <!-- Right-aligned Add Button -->
                <div class="px-6 py-4 flex justify-end">
                    <x-primary-button onclick="openCreateUserModal()">
                        + Add User
                    </x-primary-button>
                </div>
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50 text-xs font-semibold text-gray-500 uppercase">
                        <tr>
                            <th class="px-6 py-4">User Name</th>
                            <th class="px-6 py-4">Email ID</th>
                            <th class="px-6 py-4">Updated On</th>
                            <th class="px-6 py-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($users as $user)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 flex items-center gap-3">
                                    <div class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center text-sm font-bold uppercase text-gray-600">
                                        {{ strtoupper(substr($user['name'], 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="font-medium text-gray-900">{{ $user['name'] }}</div>
                                        <div class="text-xs text-gray-500">user_{{ substr(md5($user['email']), 0, 10) }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">{{ $user['email'] ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ \Carbon\Carbon::parse($user['updated_at'])->format('F jS Y, g:i a') }}</td>
                                <td class="px-6 py-4 text-right space-x-2">
                                    <div class="flex justify-end items-center gap-3">
                                        <x-primary-button
                                                class="p-2 bg-blue-500 hover:bg-blue-600"
                                                onclick="openPreviewModal('{{ e($user['name']) }}', '{{ e($user['email']) }}')"
                                            >
                                                <x-eye-icon class="w-4 h-4" /> &nbsp; View
                                        </x-primary-button>
                                        <x-secondary-button
                                            class="p-2 bg-yellow-500 hover:bg-yellow-600"
                                            onclick="openEditModal({{ $user['id'] }}, '{{ e($user['name']) }}', '{{ e($user['email']) }}')"
                                        >
                                            <x-pencil-icon class="w-4 h-4" /> &nbsp; Edit
                                        </x-secondary-button>
                                        <form action="{{ route('users.destroy', $user['id']) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <x-danger-button type="submit" onclick="return confirm('Delete this user?')" class="p-2 bg-red-500 hover:bg-red-600">
                                                <x-trash-icon/> &nbsp; Delete
                                            </x-danger-button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-gray-500 px-6 py-10">No users found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="px-6 py-4">
                    {{ $users->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>
    <!--Edit Modal-->
    <x-modal name="edit-user-modal" :show="false" focusable>
    <div class="p-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Edit User</h2>

        <form id="editUserForm" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="edit_user_name" class="block text-sm font-medium text-gray-700">Name</label>
                <input type="text" name="name" id="edit_user_name" class="w-full mt-1 border-gray-300 rounded-md shadow-sm">
            </div>

            <div class="mb-4">
                <label for="edit_user_email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" id="edit_user_email" class="w-full mt-1 border-gray-300 rounded-md shadow-sm">
            </div>

            <div class="mt-6 flex justify-end">
                <x-primary-button class="p-2 bg-yellow-500 hover:bg-yellow-600" type="submit">
                    Update
                </x-primary-button>
            </div>
        </form>
    </div>
</x-modal>
<!--Preview Modal-->
<x-modal name="preview-user-modal" :show="false" focusable>
    <div class="p-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">User Details</h2>

        <div class="mb-2"><strong>Name:</strong> <span id="preview_user_name" class="text-gray-700"></span></div>
        <div class="mb-4"><strong>Email:</strong> <span id="preview_user_email" class="text-gray-700"></span></div>

        <div class="mt-6 flex justify-end">
            <x-secondary-button onclick="closePreviewModal()" class="p-2 bg-gray-500 hover:bg-gray-600 text-white">
                Close
            </x-secondary-button>
        </div>
    </div>
</x-modal>
<!-- Delete Confirmation Modal -->
<x-modal name="delete-user-modal" :show="false" focusable>
    <div class="p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Delete User</h2>
        <p class="text-gray-700 mb-4">
            Are you sure you want to delete user <strong><span id="delete_user_name"></span></strong>? This action cannot be undone.
        </p>

        <form id="deleteUserForm" method="POST">
            @csrf
            @method('DELETE')
            <div class="mt-6 flex justify-end gap-2">
                <x-secondary-button type="button" onclick="closeDeleteModal()" class="bg-gray-500 hover:bg-gray-600 text-white p-2">
                    Cancel
                </x-secondary-button>

                <x-danger-button type="submit" class="bg-red-600 hover:bg-red-700 p-2">
                    Confirm Delete
                </x-danger-button>
            </div>
        </form>
    </div>
</x-modal>
<!-- add new user-->
<x-modal name="create-user-modal" :show="false" focusable>
    <div class="p-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Create User</h2>

        <form action="{{ route('users.store') }}" method="POST">
            @csrf
            <!-- Reuse your form partial -->
            @include('users._form')

            <div class="mt-6 flex justify-end">
                <x-primary-button class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
                    Save
                </x-primary-button>
            </div>
        </form>
    </div>
</x-modal>


<script>
    function openEditModal(id, name, email) {
        document.getElementById('edit_user_name').value = name;
        document.getElementById('edit_user_email').value = email;

        const form = document.getElementById('editUserForm');
        form.action = `/users/${id}`;

        window.dispatchEvent(new CustomEvent('open-modal', { detail: 'edit-user-modal' }));
    }
     function openPreviewModal(name, email) {
        document.getElementById('preview_user_name').textContent = name;
        document.getElementById('preview_user_email').textContent = email;

        window.dispatchEvent(new CustomEvent('open-modal', { detail: 'preview-user-modal' }));
    }

    function closePreviewModal() {
        window.dispatchEvent(new CustomEvent('close-modal', { detail: 'preview-user-modal' }));
    }
     function openDeleteModal(userId, userName) {
        document.getElementById('delete_user_name').textContent = userName;
        const form = document.getElementById('deleteUserForm');
        form.action = `/users/${userId}`;
        window.dispatchEvent(new CustomEvent('open-modal', { detail: 'delete-user-modal' }));
    }

    function closeDeleteModal() {
        window.dispatchEvent(new CustomEvent('close-modal', { detail: 'delete-user-modal' }));
    }
     function openCreateUserModal() {
        window.dispatchEvent(new CustomEvent('open-modal', { detail: 'create-user-modal' }));
    }
</script>

</x-app-layout>
