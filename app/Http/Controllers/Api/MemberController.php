<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMemberRequest;
use App\Http\Requests\UpdateMemberRequest;
use App\Models\Member;
use App\Traits\ApiResponse;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

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

    /**
     * Store a newly created member in storage.
     *
     * @param  \App\Http\Requests\StoreMemberRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
public function store(StoreMemberRequest $request)
{
    DB::beginTransaction();

    try {
        $data = $request->validated();

        // Create user with a random password
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make(Str::random(32)),
            'role' => 'member',
        ]);

        // Create member linked to the user
        $member = Member::create([
            'user_id' => $user->id,
            'name' => $data['name'],
            'email' => $data['email'],
        ]);

        // Send password setup email
        $status = Password::sendResetLink([
            'email' => $user->email,
        ]);

        if ($status !== Password::RESET_LINK_SENT) {
            throw new \Exception(
                'Member was created, but password setup email could not be sent.'
            );
        }

        DB::commit();

        return $this->success(
            [
                'member' => $member,
            ],
            'Member created successfully. Password setup link sent to the member email.',
            201
        );

    } catch (\Throwable $e) {

        DB::rollBack();

        \Log::error('Member creation failed.', [
            'message' => $e->getMessage(),
        ]);

        throw $e;
    }
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
