<?php

namespace App\Http\Controllers;

use App\Models\GroupInvitation;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class GroupInvitationController extends Controller
{
    public function respond($id, $action)
    {
        $invitation = GroupInvitation::findOrFail($id);

        if ($invitation->user_id !== auth()->id()) {
            abort(403);
        }

        if ($action === 'accept') {
            // Move user to group_members
            $group = $invitation->group;
            $group->groupMembers()->syncWithoutDetaching([$invitation->user_id]);
            $invitation->status = 'accepted';
        } elseif ($action === 'decline') {
            $invitation->status = 'declined';
        }

        $invitation->save();

        // remove session pending_invite_group
        session()->forget('pending_invite_group');


        return redirect()->route('dashboard.index')->withToastSuccess('Invitation response saved successfully.');
    }
}
