<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMemberRequest;
use App\Http\Requests\UpdateMemberRequest;
use App\Models\Member;
use App\Traits\ApiResponse;

class MemberController extends Controller
{
    use ApiResponse;

    public function index()
    {
        return $this->success(
            Member::paginate(10),
            'Members retrieved successfully.'
        );
    }

    public function store(StoreMemberRequest $request)
    {
        $member = Member::create($request->validated());

        return $this->success(
            $member,
            'Member created successfully.',
            201
        );
    }

    public function show($id)
    {
        $member = Member::findOrFail($id);

        return $this->success(
            $member,
            'Member retrieved successfully.'
        );
    }

    public function update(UpdateMemberRequest $request, $id)
    {
        $member = Member::findOrFail($id);

        $member->update($request->validated());

        return $this->success(
            $member,
            'Member updated successfully.'
        );
    }

    public function destroy($id)
    {
        $member = Member::findOrFail($id);

        $hasBorrowedBooks = $member->borrowings()
            ->whereNull('returned_at')
            ->exists();

        if ($hasBorrowedBooks) {
            return $this->error(
                'Cannot delete this member because they have borrowed books.',
                422
            );
        }

        $member->delete();

        return $this->success(
            null,
            'Member deleted successfully.'
        );
    }
}
