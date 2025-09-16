<?php
namespace App\Services;

use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserCreated;

class UserService
{
    protected $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAllUsers() {
        return $this->repository->all();
    }

    public function getPaginatedUsers($search = null)
    {
    return $this->repository->query()
        ->when($search, function ($query, $search) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
        })
        ->orderBy('name')
        ->paginate(10);
    }

    public function getUserById($id) {
        return $this->repository->find($id);
    }

    public function createUser(array $data) {
        $data['password'] = Hash::make($data['password']);
        $user = $this->repository->create($data);
        Mail::to($user->email)->send(new UserCreated($user));
        return $user;
    }

    public function updateUser($user, array $data) {
        return $this->repository->update($user, $data);
    }

    public function deleteUser($user) {
        return $this->repository->delete($user);
    }
}
