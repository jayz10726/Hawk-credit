<?php
namespace App\Policies;
use App\Models\{User, CreditRequest};

class CreditRequestPolicy
{
    public function view(User $user, CreditRequest $req): bool
    {
        if ($user->hasRole('super_admin')) return true;
        if ($user->hasRole('org_admin'))   return $user->organization_id === $req->organization_id;
        return $user->id === $req->user_id;  // owner only
    }

    public function approve(User $user, CreditRequest $req): bool
    {
        return ($user->hasRole('super_admin') || $user->hasRole('org_admin'))
            && $user->organization_id === $req->organization_id;
    }

    public function reject(User $user, CreditRequest $req): bool
    { return $this->approve($user, $req); }

    public function create(User $user): bool
    { return $user->hasRole('user'); }
}
