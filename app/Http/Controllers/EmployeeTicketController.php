<?php

namespace App\Http\Controllers;
use App\Services\NotificationService;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

use App\Models\Ticket;
use App\Models\TicketCategory;
use App\Models\TicketPriority;
use App\Models\TicketAttachment;

use App\Services\ActivityLogger;
use App\Services\TicketHistoryLogger;
use App\Services\TicketCategoryClassifier;
use App\Services\TicketPriorityClassifier;

class EmployeeTicketController extends Controller
{

    public function create()
    {
        $categories = TicketCategory::all();
        $priorities = TicketPriority::all();

        return view(
            'tickets.create',
            compact('categories', 'priorities')
        );
    }


    // =========================================================
    // SHOW TICKET
    // =========================================================

    public function show($id)
    {
        $ticket = Ticket::with([
            'category',
            'priority',
            'status',
            'attachments',
            'comments.user.role'
        ])
        ->where('Id', $id)
        ->where('CreatedByUserId', Auth::id())
        ->firstOrFail();

        return view(
            'employee.ViewTicket',
            compact('ticket')
        );
    }


   

    public function store(
        Request $request,
        TicketCategoryClassifier $ticketCategoryClassifier,
        TicketPriorityClassifier $ticketPriorityClassifier
    )
    {
        $request->validate([
            'Title' => 'required|string|max:255',
            'CategoryId' => 'nullable|exists:TicketCategories,Id',
            'PriorityId' => 'nullable|exists:TicketPriorities,Id',
            'Description' => 'required|string',

            'attachments' => 'nullable|array',

            'attachments.*' =>
                'file|mimes:jpg,jpeg,png,pdf,doc,docx|max:10240',
        ]);


        // -----------------------------------------------------
        // CREATE TICKET
        // -----------------------------------------------------

        $ticket = new Ticket();

        $ticket->ReferenceNumber =
            'TKT-' . strtoupper(Str::random(8));

        $ticket->CreatedByUserId = Auth::id();

        // Preserve a manually selected category. Only ask AI to classify when
        // the employee leaves the Category dropdown empty.
        $categoryId = $request->input('CategoryId');

        if (blank($categoryId)) {
            $category = $ticketCategoryClassifier->classify(
                $request->input('Title'),
                $request->input('Description')
            );

            if ($category === null) {
                Log::info('AI ticket category classification unavailable and no category was selected.');

                return back()
                    ->withErrors([
                        'CategoryId' => 'Unable to determine a category automatically. Please select a category manually.',
                    ])
                    ->withInput();
            }

            $categoryId = $category->Id;

            Log::info('AI ticket category classification applied.', [
                'category_id' => $category->Id,
                'category_name' => $category->Name,
            ]);
        } else {
            Log::info('Manual ticket category selection preserved.', [
                'category_id' => $categoryId,
            ]);
        }

        $ticket->CategoryId = $categoryId;

        // Preserve a manually selected priority. Only ask AI to classify when
        // the employee leaves the Priority dropdown empty.
        $priorityId = $request->input('PriorityId');

        if (blank($priorityId)) {
            $priority = $ticketPriorityClassifier->classify(
                $request->input('Title'),
                $request->input('Description')
            );

            if ($priority === null) {
                Log::info('AI ticket priority classification unavailable and no priority was selected.');

                return back()
                    ->withErrors([
                        'PriorityId' => 'Unable to determine a priority automatically. Please select a priority manually.',
                    ])
                    ->withInput();
            }

            $priorityId = $priority->Id;

            Log::info('AI ticket priority classification applied.', [
                'priority_id' => $priority->Id,
                'priority_name' => $priority->Name,
            ]);
        } else {
            Log::info('Manual ticket priority selection preserved.', [
                'priority_id' => $priorityId,
            ]);
        }

        $ticket->PriorityId = $priorityId;

        $ticket->StatusId = 1;

        $ticket->Title =
            $request->Title;

        $ticket->Description =
            $request->Description;

        $ticket->CreatedAt = now();

        $ticket->UpdatedAt = now();

        $ticket->save();


        // -----------------------------------------------------
        // ACTIVITY LOG - TICKET CREATED
        // -----------------------------------------------------

        ActivityLogger::logTicketCreated(
            $ticket->Id
        );




        TicketHistoryLogger::log(
            $ticket->Id,
            'Ticket',
            null,
            'Ticket Created'
        );

        //send notifications

// Notify all Administrators
$administrators = User::whereHas('role', function ($query) {
    $query->where('Name', 'Administrator');
})->get();

foreach ($administrators as $admin) {

    NotificationService::send(

        $admin->Id,

        $ticket->Id,

        'ticket_created',

        'New Ticket Created',

        Auth::user()->Name .
        ' created ticket ' .
        $ticket->ReferenceNumber

    );
}


// Notify all IT Managers
$managers = User::whereHas('role', function ($query) {
    $query->where('Name', 'IT Manager');
})->get();

foreach ($managers as $manager) {

    NotificationService::send(

        $manager->Id,

        $ticket->Id,

        'ticket_created',

        'New Ticket Created',

        Auth::user()->Name .
        ' created ticket ' .
        $ticket->ReferenceNumber

    );
}




        if ($request->hasFile('attachments')) {

            foreach (
                $request->file('attachments') as $file
            ) {

                $storedFileName =
                    time()
                    . '_'
                    . uniqid()
                    . '_'
                    . $file->getClientOriginalName();


                $path = $file->storeAs(
                    'ticket-attachments',
                    $storedFileName,
                    'public'
                );


                $attachment =
                    new TicketAttachment();

                $attachment->TicketId =
                    $ticket->Id;

                $attachment->UploadedByUserId =
                    Auth::id();

                $attachment->OriginalFileName =
                    $file->getClientOriginalName();

                $attachment->StoredFileName =
                    $storedFileName;

                $attachment->FilePath =
                    $path;

                $attachment->MimeType =
                    $file->getMimeType();

                $attachment->FileSize =
                    $file->getSize();

                $attachment->CreatedAt =
                    now();

                $attachment->UpdatedAt =
                    now();

                $attachment->save();




                TicketHistoryLogger::log(
                    $ticket->Id,
                    'Attachment',
                    null,
                    $attachment->OriginalFileName
                );
            }
        }


        return redirect()
            ->route('CreateTicket')
            ->with(
                'success',
                'Ticket created successfully.'
            );
    }




    public function edit($id)
    {
        $ticket = Ticket::where('Id', $id)
            ->where(
                'CreatedByUserId',
                Auth::id()
            )
            ->firstOrFail();


        $categories =
            TicketCategory::all();

        $priorities =
            TicketPriority::all();


        return view(
            'employee.editTicket',
            compact(
                'ticket',
                'categories',
                'priorities'
            )
        );
    }




    public function update(Request $request, $id)
    {


        $ticket = Ticket::with([
            'category',
            'priority'
        ])
        ->where('Id', $id)
        ->where(
            'CreatedByUserId',
            Auth::id()
        )
        ->firstOrFail();



        $request->validate([

            'Title' =>
                'required|string|max:255',

            'CategoryId' =>
                'required|exists:TicketCategories,Id',

            'PriorityId' =>
                'required|exists:TicketPriorities,Id',

            'Description' =>
                'required|string',

            'Attachment' =>
                'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:10240',

        ]);




        $oldTitle =
            $ticket->Title;

        $oldDescription =
            $ticket->Description;

        $oldCategoryId =
            $ticket->CategoryId;

        $oldCategoryName =
            $ticket->category->Name;

        $oldPriorityId =
            $ticket->PriorityId;

        $oldPriorityName =
            $ticket->priority->Name;



        $newCategory =
            TicketCategory::findOrFail(
                $request->CategoryId
            );

        $newPriority =
            TicketPriority::findOrFail(
                $request->PriorityId
            );




        $ticket->Title =
            $request->Title;

        $ticket->CategoryId =
            $request->CategoryId;

        // Preserve a manually selected priority. Only ask AI to classify when
        // the employee leaves the Priority dropdown empty.
        $priorityId = $request->input('PriorityId');

        if (blank($priorityId)) {
            $priority = $ticketPriorityClassifier->classify(
                $request->input('Title'),
                $request->input('Description')
            );

            if ($priority === null) {
                Log::info('AI ticket priority classification unavailable and no priority was selected.');

                return back()
                    ->withErrors([
                        'PriorityId' => 'Unable to determine a priority automatically. Please select a priority manually.',
                    ])
                    ->withInput();
            }

            $priorityId = $priority->Id;

            Log::info('AI ticket priority classification applied.', [
                'priority_id' => $priority->Id,
                'priority_name' => $priority->Name,
            ]);
        } else {
            Log::info('Manual ticket priority selection preserved.', [
                'priority_id' => $priorityId,
            ]);
        }

        $ticket->PriorityId = $priorityId;

        $ticket->Description =
            $request->Description;

        $ticket->UpdatedAt =
            now();

        $ticket->save();



        if ($oldTitle != $request->Title) {

            TicketHistoryLogger::log(
                $ticket->Id,
                'Title',
                $oldTitle,
                $request->Title
            );
        }




        if (
            $oldCategoryId
            !=
            $request->CategoryId
        ) {

            TicketHistoryLogger::log(
                $ticket->Id,
                'Category',
                $oldCategoryName,
                $newCategory->Name
            );
        }




        if (
            $oldPriorityId
            !=
            $request->PriorityId
        ) {

            TicketHistoryLogger::log(
                $ticket->Id,
                'Priority',
                $oldPriorityName,
                $newPriority->Name
            );
        }




        if (
            $oldDescription
            !=
            $request->Description
        ) {

            TicketHistoryLogger::log(
                $ticket->Id,
                'Description',
                $oldDescription,
                $request->Description
            );
        }


        // =====================================================
        // ADD NEW ATTACHMENT
        // =====================================================

        if (
            $request->hasFile(
                'Attachment'
            )
        ) {

            $file =
                $request->file(
                    'Attachment'
                );


            $storedFileName =
                time()
                . '_'
                . uniqid()
                . '_'
                . $file->getClientOriginalName();


            $path = $file->storeAs(
                'ticket-attachments',
                $storedFileName,
                'public'
            );


            $attachment =
                new TicketAttachment();


            $attachment->TicketId =
                $ticket->Id;

            $attachment->UploadedByUserId =
                Auth::id();

            $attachment->OriginalFileName =
                $file->getClientOriginalName();

            $attachment->StoredFileName =
                $storedFileName;

            $attachment->FilePath =
                $path;

            $attachment->MimeType =
                $file->getMimeType();

            $attachment->FileSize =
                $file->getSize();

            $attachment->CreatedAt =
                now();

            $attachment->UpdatedAt =
                now();

            $attachment->save();


            // -------------------------------------------------
            // HISTORY - ATTACHMENT ADDED
            // -------------------------------------------------

            TicketHistoryLogger::log(
                $ticket->Id,
                'Attachment',
                null,
                $attachment->OriginalFileName
            );
        }


        return redirect()
            ->route(
                'employee.tickets.edit',
                [
                    'id' =>
                        $ticket->Id
                ]
            )
            ->with(
                'success',
                'Ticket updated successfully.'
            );
    }


    // =========================================================
    // DOWNLOAD ATTACHMENT
    // =========================================================

    public function download($id)
    {
        $attachment =
            TicketAttachment::where(
                'Id',
                $id
            )
            ->where(
                'UploadedByUserId',
                Auth::id()
            )
            ->firstOrFail();


        return Storage::disk('public')
            ->download(
                $attachment->FilePath,
                $attachment->OriginalFileName
            );
    }




    public function destroy($id)
    {
        $ticket = Ticket::where(
            'Id',
            $id
        )
        ->where(
            'CreatedByUserId',
            Auth::id()
        )
        ->firstOrFail();


        // Only Open and Resolved tickets can be deleted
        if (
            !in_array(
                $ticket->status->Name,
                [
                    'Open',
                    'Resolved'
                ]
            )
        ) {

            return redirect()
                ->back()
                ->with(
                    'error',
                    'Only Open or Resolved tickets can be deleted.'
                );
        }




        $ticket->attachments()->delete();

        $ticket->comments()->delete();

        $ticket->assignments()->delete();

        $ticket->escalations()->delete();



        $ticket->delete();


        return redirect()
            ->route('employee.dashboard')
            ->with(
                'success',
                'Ticket deleted successfully.'
            );
    }


    public function deleteAttachment($id)
    {
        $attachment =
            TicketAttachment::where(
                'Id',
                $id
            )
            ->where(
                'UploadedByUserId',
                Auth::id()
            )
            ->firstOrFail();


        // Save information BEFORE deleting it
        $ticketId =
            $attachment->TicketId;

        $fileName =
            $attachment->OriginalFileName;

        Storage::disk('public')
            ->delete(
                $attachment->FilePath
            );


        $attachment->delete();




        TicketHistoryLogger::log(
            $ticketId,
            'Attachment',
            $fileName,
            'Deleted'
        );


        return back()
            ->with(
                'success',
                'Attachment deleted successfully.'
            );
    }
}
