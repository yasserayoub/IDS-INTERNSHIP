<?php

namespace App\Http\Controllers;
use App\Models\Ticket;
use App\Models\TicketCategory;
use App\Models\TicketPriority;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use App\Models\TicketAttachment;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;



use Illuminate\Http\Request;

class EmployeeTicketController extends Controller
{


  public function create()  //create page
{
    $categories = TicketCategory::all();
    $priorities = TicketPriority::all();

    return view('tickets.create', compact('categories', 'priorities'));
}

    public function show($id){  //view ticket details

        $ticket = Ticket::where('Id', $id)
            ->where('CreatedByUserId', Auth::user()->Id)
            ->firstOrFail();

        return view('employee.ViewTicket', compact('ticket'));

    }
 public function store(Request $request)
{
    $request->validate([
        'Title' => 'required|string|max:255',
        'CategoryId' => 'required|exists:TicketCategories,Id',
        'PriorityId' => 'required|exists:TicketPriorities,Id',
        'Description' => 'required|string',

        'attachments' => 'nullable|array',
        'attachments.*' => 'file|mimes:jpg,jpeg,png,pdf,doc,docx|max:10240',
    ]);

    $ticket = new Ticket();

    $ticket->ReferenceNumber = 'TKT-' . strtoupper(Str::random(8));
    $ticket->CreatedByUserId = Auth::user()->Id;
    $ticket->CategoryId = $request->CategoryId;
    $ticket->PriorityId = $request->PriorityId;
    $ticket->StatusId = 1;
    $ticket->Title = $request->Title;
    $ticket->Description = $request->Description;
    $ticket->CreatedAt = now();
    $ticket->UpdatedAt = now();

    $ticket->save();

    if ($request->hasFile('attachments')) {

        foreach ($request->file('attachments') as $file) {

            $storedFileName = time() . '_' . uniqid() . '_' . $file->getClientOriginalName();

            $path = $file->storeAs(
                'ticket-attachments',
                $storedFileName,
                'public'
            );

            $attachment = new TicketAttachment();

            $attachment->TicketId = $ticket->Id;
            $attachment->UploadedByUserId = Auth::user()->Id;
            $attachment->OriginalFileName = $file->getClientOriginalName();
            $attachment->StoredFileName = $storedFileName;
            $attachment->FilePath = $path;
            $attachment->MimeType = $file->getMimeType();
            $attachment->FileSize = $file->getSize();
            $attachment->CreatedAt = now();
            $attachment->UpdatedAt = now();

            $attachment->save();
        }
    }

    return redirect()
        ->route('CreateTicket')
        ->with('success', 'Ticket created successfully.');
}


    public function edit($id){

       $ticket = Ticket::where('Id', $id)  //lezm ykon id mtl le b3tne
        ->where('CreatedByUserId', Auth::user()->Id)
        ->firstOrFail();
         $categories = TicketCategory::all();
    $priorities = TicketPriority::all();

    return view('employee.editTicket', compact(
        'ticket',
        'categories',
        'priorities'
    ));
}
    public function update(Request $request, $id)
    {
        $ticket = Ticket::where('Id', $id)
            ->where('CreatedByUserId', Auth::user()->Id)
            ->firstOrFail();

        $request->validate([
            'Title' => 'required|string|max:255',
            'CategoryId' => 'required|exists:TicketCategories,Id',
            'PriorityId' => 'required|exists:TicketPriorities,Id',
            'Description' => 'required|string',
            'Attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:10240',
        ]);

        $ticket->Title = $request->Title;
        $ticket->CategoryId = $request->CategoryId;
        $ticket->PriorityId = $request->PriorityId;
        $ticket->Description = $request->Description;
        $ticket->UpdatedAt = now();

        $ticket->save();
   if ($request->hasFile('Attachment')) {

    $file = $request->file('Attachment');

    $storedFileName = time() . '_' . $file->getClientOriginalName();

    $path = $file->storeAs(
        'ticket-attachments',
        $storedFileName,
        'public'
    );

    $attachment = new TicketAttachment();

    $attachment->TicketId = $ticket->Id;
    $attachment->UploadedByUserId = Auth::user()->Id;
    $attachment->OriginalFileName = $file->getClientOriginalName();
    $attachment->StoredFileName = $storedFileName;
    $attachment->FilePath = $path;
    $attachment->MimeType = $file->getMimeType();
    $attachment->FileSize = $file->getSize();
    $attachment->CreatedAt = now();
    $attachment->UpdatedAt = now();

    $attachment->save();
}


        return redirect()
            ->route('employee.tickets.edit', ['id' => $ticket->Id])
            ->with('success', 'Ticket updated successfully.');
    }
public function download($id)
{
    $attachment = TicketAttachment::where('Id', $id)
        ->where('UploadedByUserId', Auth::user()->Id)
        ->firstOrFail();

    return Storage::disk('public')->download(
        $attachment->FilePath,
        $attachment->OriginalFileName
    );
}
    public function destroy($id)
    {
        $ticket = Ticket::where('Id', $id)
            ->where('CreatedByUserId', Auth::user()->Id)
            ->firstOrFail();

        $ticket->delete();

        return redirect()
            ->route('employee.dashboard')
            ->with('success', 'Ticket deleted successfully.');
    }
    public function deleteAttachment($id)
{
    $attachment = TicketAttachment::where('Id', $id)
        ->where('UploadedByUserId', Auth::user()->Id)
        ->firstOrFail();

    Storage::disk('public')->delete($attachment->FilePath);

    $attachment->delete();

    return back()->with('success', 'Attachment deleted successfully.');
}
}
