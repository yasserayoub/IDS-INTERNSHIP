<?php

namespace App\Http\Controllers;
use App\Models\Ticket;
use App\Models\TicketAssignment;
use App\Models\User;
use App\Models\Role;

use Illuminate\Http\Request;

class AdminUserController extends Controller
{
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
    public function usermanagmentpage()
    {
        $users = User::all();
        return view('admin.users.usermanagment', compact('users'));
    }
    public function create()
    {
        $roles=Role::all();
        return view('admin.users.create', compact('roles'));
    }
   public function store(Request $request)
{
    $request->validate([
        'Name'       => 'required|string|max:255',
        'Email'      => 'required|email|unique:Users,Email',
        'Department' => 'required|string|max:255',
        'Password'   => 'required|string|min:8|confirmed',
        'RoleId'     => 'required|exists:Roles,Id',
        'IsActive'   => 'required|boolean',
    ]);

    $user = new User();

    $user->Name = $request->Name;
    $user->Email = $request->Email;
    $user->Password = bcrypt($request->Password);//we hash the pass
    $user->Department = $request->Department;
    $user->RoleId = $request->RoleId;
    $user->IsActive = $request->IsActive;

    $user->save();

    return redirect()->route('adminCreateUser')
                     ->with('success', 'User created successfully.');
}

}
