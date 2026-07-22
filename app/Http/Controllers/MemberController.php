<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function index()
    {
        $members = Member::all();

        return view('members.index', compact('members'));
    }

    public function create()
    {
        return view('members.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:members,email',
        ]);

        Member::create($request->only('name', 'email'));

        return redirect('/members');
    }

    public function edit(Member $member)
    {
        return view('members.edit', compact('member'));
    }

    public function update(Request $request, Member $member)
    {
        $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:members,email,' . $member->id,
        ]);

        $member->update($request->only('name', 'email'));

        return redirect('/members');
    }

    public function destroy(Member $member)
    {
        $member->delete();

        return redirect('/members');
    }
}