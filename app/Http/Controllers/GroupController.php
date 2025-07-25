<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\GroupInvitation;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $groups = Group::latest()
        ->with(['groupMembers', 'groupInvitations.user'])
        ->get();

        return view('groups.index', compact('groups'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $group = null;

        return view('groups.add_edit', compact('group'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'members' => 'nullable|array',
            'members.*' => 'exists:users,id',
        ]);

        $group = Group::create([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'created_by' => auth()->id(),
        ]);

        // merge creator to members
        $members[] = auth()->id();
        $group->groupMembers()->sync($members);

        // send invitations to other members if provided
        $this->sendInvitations($request->input('members', []), $group);

        return redirect()->route('groups.index')->withToastSuccess('Group created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $group = Group::findOrFail($id);

        if ($group->created_by !== auth()->id()) {
            abort(403, 'You do not have permission to edit this group.');
        }

        return view('groups.add_edit', compact('group'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'members' => 'nullable|array',
            'members.*' => 'exists:users,id',
        ]);

        $group = Group::findOrFail($id);

        if ($group->created_by !== auth()->id()) {
            abort(403, 'You do not have permission to update this group.');
        }

        $group->update([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
        ]);

        // Handle invitations for new members
        $this->sendInvitations($request->input('members', []), $group);


        return redirect()->route('groups.index')->withToastSuccess('Group updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $group = Group::findOrFail($id);

        if ($group->created_by !== auth()->id()) {
            abort(403, 'You do not have permission to delete this group.');
        }

        // Detach all members before deleting the group
        $group->groupMembers()->detach();

        $group->delete();

        return redirect()->route('groups.index')->withToastSuccess('Group deleted successfully.');
    }

    public function join(Group $group)
    {
        $group->groupMembers()->attach(auth()->id());

        return redirect()->route('groups.index')->withToastSuccess('You have joined the group successfully.');
    }

    public function leave(Group $group)
    {
        $group->groupMembers()->detach(auth()->id());

        return redirect()->route('groups.index')->withToastSuccess('You have left the group successfully.');
    }

    public function yourGroups()
    {
        $groups = Group::where(function ($query) {
            $query->where('created_by', auth()->id())
                ->orWhereHas('groupMembers', function ($q) {
                    $q->where('user_id', auth()->id());
                });
        })->latest()->get();

        return view('groups.your_groups', compact('groups'));
    }

    public function sendInvitations(array $userIds, Group $group)
    {
        foreach ($userIds as $userId) {
            // Ensure the user is not already a member of the group
            if ($group->groupMembers()->where('user_id', $userId)->exists()) {
                continue;
            }
            GroupInvitation::firstOrCreate([
                'group_id' => $group->id,
                'user_id' => $userId
            ]);
        }
    }
}
