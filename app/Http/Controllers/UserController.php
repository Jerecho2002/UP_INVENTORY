<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UserService;
use App\Transformers\UserTransformer;
use App\Mail\UserCreated;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    // Injected UserService for handling business logic
    protected $service;

    public function __construct(UserService $service) {
        $this->service = $service;
    }

    /**
     * Display a listing of users.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $users = $this->service->getPaginatedUsers($search);

        // Transform each item, but keep pagination
        $users->getCollection()->transform(function ($user) {
            return \App\Transformers\UserTransformer::transform($user);
        });

        return view('users.index', [
            'users' => $users,
            'search' => $search,
        ]);
    }

    /**
     * Show the form for creating a new user.
     */
    public function create() {
        return view('users.create');
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request) {
        // Validate incoming request data
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);

        // Create the user and store the result
        $user = $this->service->createUser($data);

        // Send welcome email
        Mail::to($user->email)->send(new \App\Mail\UserCreated($user));

        // Redirect to user list with success message
        return redirect()->route('users.index')->with('success', 'User created!');
    }

    /**
     * Display the specified user.
     */
    public function show($id) {
        // Retrieve and transform the user
        $user = $this->service->getUserById($id);
        return view('users.show', ['user' => UserTransformer::transform($user)]);
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit($id) {
        // Fetch user for editing
        $user = $this->service->getUserById($id);
        return view('users.edit', ['user' => $user]);
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, $id) {
        // Find the user
        $user = $this->service->getUserById($id);

        // Validate the updated data
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        // Update the user via service
        $this->service->updateUser($user, $data);

        // Redirect with success message
        return redirect()->route('users.index')->with('success', 'User updated!');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy($id) {
        // Fetch and delete the user
        $user = $this->service->getUserById($id);
        $this->service->deleteUser($user);

        // Redirect with success message
        return redirect()->route('users.index')->with('success', 'User deleted!');
    }
}
