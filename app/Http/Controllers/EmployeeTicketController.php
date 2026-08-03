<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

use App\Models\Ticket;
use App\Models\TicketCategory;
use App\Models\TicketPriority;
use App\Models\TicketAttachment;

use App\Services\ActivityLogger;
use App\Services\TicketHistoryLogger;

class EmployeeTicketController extends Controller
{
    // =========================================================
    // CREATE TICKET PAGE
    // =========================================================

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


    // =========================================================
    // CREATE NEW TICKET
    // =========================================================

    public function store(Request $request)
    {
        $request->validate([
            'Title' => 'required|string|max:255',
            'CategoryId' => 'required|exists:TicketCategories,Id',
            'PriorityId' => 'required|exists:TicketPriorities,Id',
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

        $ticket->CategoryId =
            $request->CategoryId;

        $ticket->PriorityId =
            $request->PriorityId;

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


        // -----------------------------------------------------
        // TICKET HISTORY - TICKET CREATED
        // -----------------------------------------------------

        TicketHistoryLogger::log(
            $ticket->Id,
            'Ticket',
            null,
            'Ticket Created'
        );


        // -----------------------------------------------------
        // SAVE ATTACHMENTS
        // -----------------------------------------------------

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


                // ---------------------------------------------
                // HISTORY - ATTACHMENT ADDED
                // ---------------------------------------------

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


    // =========================================================
    // EDIT TICKET PAGE
    // =========================================================

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


    // =========================================================
    // UPDATE TICKET
    // =========================================================

    public function update(Request $request, $id)
    {
        // -----------------------------------------------------
        // GET CURRENT TICKET
        // -----------------------------------------------------

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


        // -----------------------------------------------------
        // VALIDATE
        // -----------------------------------------------------

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


        // =====================================================
        // SAVE OLD VALUES
        // =====================================================

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


        // =====================================================
        // GET NEW CATEGORY / PRIORITY
        // =====================================================

        $newCategory =
            TicketCategory::findOrFail(
                $request->CategoryId
            );

        $newPriority =
            TicketPriority::findOrFail(
                $request->PriorityId
            );


        // =====================================================
        // UPDATE TICKET
        // =====================================================

        $ticket->Title =
            $request->Title;

        $ticket->CategoryId =
            $request->CategoryId;

        $ticket->PriorityId =
            $request->PriorityId;

        $ticket->Description =
            $request->Description;

        $ticket->UpdatedAt =
            now();

        $ticket->save();


        // =====================================================
        // HISTORY - TITLE
        // =====================================================

        if ($oldTitle != $request->Title) {

            TicketHistoryLogger::log(
                $ticket->Id,
                'Title',
                $oldTitle,
                $request->Title
            );
        }


        // =====================================================
        // HISTORY - CATEGORY
        // =====================================================

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


        // =====================================================
        // HISTORY - PRIORITY
        // =====================================================

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


        // =====================================================
        // HISTORY - DESCRIPTION
        // =====================================================

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


    // =========================================================
    // DELETE TICKET
    // =========================================================

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


        // -----------------------------------------------------
        // DELETE RELATED RECORDS
        // -----------------------------------------------------

        $ticket->attachments()->delete();

        $ticket->comments()->delete();

        $ticket->assignments()->delete();

        $ticket->escalations()->delete();


        /*
         * IMPORTANT:
         *
         * We are NOT deleting TicketHistories here anymore.
         *
         * Previously you had:
         *
         * $ticket->histories()->delete();
         *
         * That would destroy the audit/history information.
         */


        // -----------------------------------------------------
        // SOFT DELETE TICKET
        // -----------------------------------------------------

        $ticket->delete();


        return redirect()
            ->route('employee.dashboard')
            ->with(
                'success',
                'Ticket deleted successfully.'
            );
    }


    // =========================================================
    // DELETE ATTACHMENT
    // =========================================================

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


        // -----------------------------------------------------
        // DELETE FILE FROM STORAGE
        // -----------------------------------------------------

        Storage::disk('public')
            ->delete(
                $attachment->FilePath
            );


        // -----------------------------------------------------
        // DELETE DATABASE RECORD
        // -----------------------------------------------------

        $attachment->delete();


        // -----------------------------------------------------
        // HISTORY - ATTACHMENT DELETED
        // -----------------------------------------------------

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
