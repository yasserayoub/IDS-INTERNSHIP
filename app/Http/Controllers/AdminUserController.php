<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\TicketAssignment;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Admin Dashboard
    |--------------------------------------------------------------------------
    */

    public function dashboard()
    {
        // Total tickets
        $totalTickets = Ticket::count();

        // Open tickets
        $openTickets = Ticket::whereHas('status', function ($query) {
            $query->where('Name', 'Open');
        })->count();

        // Unassigned tickets
        $unassignedTickets = Ticket::whereDoesntHave('currentAssignment')
            ->count();

        // Critical tickets
        $criticalTickets = Ticket::whereHas('priority', function ($query) {
            $query->where('Name', 'Critical');
        })->count();

        // Latest 5 tickets
        $recentTickets = Ticket::with([
            'priority',
            'status',
            'currentAssignment.assignedTo'
        ])
            ->orderBy('CreatedAt', 'desc')
            ->take(5)
            ->get();

        // IT Support agents + number of active assigned tickets
        $agents = User::whereHas('role', function ($query) {
            $query->where('Name', 'IT Support');
        })
            ->withCount([
                'ticketAssignments as active_tickets_count' => function ($query) {
                    $query->where('IsCurrent', true);
                }
            ])
            ->orderByDesc('active_tickets_count')
            ->get();

        return view('admin.dashboard', compact(
            'totalTickets',
            'openTickets',
            'unassignedTickets',
            'criticalTickets',
            'recentTickets',
            'agents'
        ));
    }


    /*
    |--------------------------------------------------------------------------
    | User Management
    |--------------------------------------------------------------------------
    */

    public function usermanagmentpage()
    {
        /*
        |--------------------------------------------------------------------------
        | Get users
        |--------------------------------------------------------------------------
        |
        | Load each user's role at the same time.
        |
        */

        $users = User::with('role')
            ->orderBy('CreatedAt', 'desc')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | User Statistics
        |--------------------------------------------------------------------------
        */

        // Total users
        $totalUsers = $users->count();


        // Total employees
        $employeeCount = $users->filter(function ($user) {

            return $user->role
                && $user->role->Name === 'Employee';

        })->count();


        // Total IT Support agents
        $supportCount = $users->filter(function ($user) {

            return $user->role
                && $user->role->Name === 'IT Support';

        })->count();


        // Total inactive accounts
        $inactiveCount = $users->where('IsActive', false)->count();


        /*
        |--------------------------------------------------------------------------
        | Send data to Blade
        |--------------------------------------------------------------------------
        */

        return view(
            'admin.users.usermanagment',
            compact(
                'users',
                'totalUsers',
                'employeeCount',
                'supportCount',
                'inactiveCount'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Create User Page
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        $roles = Role::all();

        return view(
            'admin.users.create',
            compact('roles')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Store New User
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $request->validate([

            'Name' => 'required|string|max:255',

            'Email' => 'required|email|unique:Users,Email',

            'Department' => 'required|string|max:255',

            'Password' => 'required|string|min:8|confirmed',

            'RoleId' => 'required|exists:Roles,Id',

            'IsActive' => 'required|boolean',

        ]);


        /*
        |--------------------------------------------------------------------------
        | Create User
        |--------------------------------------------------------------------------
        */

        $user = new User();

        $user->Name = $request->Name;

        $user->Email = $request->Email;

        // Hash password before saving
        $user->Password = bcrypt($request->Password);

        $user->Department = $request->Department;

        $user->RoleId = $request->RoleId;

        $user->IsActive = $request->IsActive;

        $user->save();


        /*
        |--------------------------------------------------------------------------
        | Redirect
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('adminCreateUser')
            ->with(
                'success',
                'User created successfully.'
            );
    }
    public function edit($id)
{
    $user = User::with('role')->findOrFail($id);

    $roles = Role::all();

    return view('admin.users.edit', compact('user', 'roles'));
}


public function update(Request $request, $id)
{
    $user = User::findOrFail($id);

    $request->validate([
        'Name' => 'required|string|max:255',

        'Email' => 'required|email|unique:Users,Email,' . $id . ',Id',

        'Department' => 'required|string|max:255',

        'RoleId' => 'required|exists:Roles,Id',

        'Password' => 'nullable|string|min:8|confirmed',
    ]);


    $user->Name = $request->Name;
    $user->Email = $request->Email;
    $user->Department = $request->Department;
    $user->RoleId = $request->RoleId;


    // Only change the password if one was entered
    if ($request->filled('Password')) {
        $user->Password = bcrypt($request->Password);
    }


    $user->save();


    return redirect()
        ->route('UserManagementpage')
        ->with('success', 'User updated successfully.');
}


public function toggleStatus($id)
{
    $user = User::findOrFail($id);

    $user->IsActive = !$user->IsActive;

    $user->save();


    return redirect()
        ->route('UserManagementpage')
        ->with(
            'success',
            $user->IsActive
                ? 'User activated successfully.'
                : 'User deactivated successfully.'
        );
}
}
