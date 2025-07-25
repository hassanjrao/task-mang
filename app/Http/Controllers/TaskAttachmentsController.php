<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskAttachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TaskAttachmentsController extends Controller
{
    public function upload(Request $request)
    {
        if (!$request->hasFile('file')) {
            return response()->json(['message' => 'No file uploaded'], 422);
        }

        $file = $request->file('file');

        if (!$file->isValid()) {
            return response()->json(['message' => 'Invalid file upload'], 422);
        }

        $attachment = new TaskAttachment();
        $attachment->file_path = $file->store('uploads/tasks');
        $attachment->original_name = $request->input('original_name', $file->getClientOriginalName());
        $attachment->file_size = $request->input('file_size', $file->getSize());

        // Optionally associate with task/user here
        $attachment->save();


        return response($attachment->id, 200); // just return the path as plain text
    }


    public function revert(Request $request)
    {
        $path = $request->input('path');

        if (Storage::exists($path)) {
            Storage::delete($path);
        }

        return response('', 200);
    }

    public function remove(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:task_attachments,id',
        ]);

        $attachmentId = $request->input('id');

        if (!$attachmentId) {
            return response()->json(['message' => 'Attachment ID is required'], 422);
        }

        $attachment = TaskAttachment::find($attachmentId);

        if (!$attachment) {
            return response()->json(['message' => 'Attachment not found'], 404);
        }

        Storage::delete($attachment->file_path);
        $attachment->delete();

        return response()->json(['message' => 'Attachment removed successfully'], 200);
    }
}
