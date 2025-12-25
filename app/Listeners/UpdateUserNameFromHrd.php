<?php

namespace App\Listeners;

use App\Models\User;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UpdateUserNameFromHrd
{
    /**
     * Handle the event.
     */
    public function handle(Login $event): void
    {
        $user = $event->user;

        // Only update if user has an idcard (from LDAP sync)
        if (!$user->idcard) {
            return;
        }

        try {
            // Query HRD database for Thai name
            $hrdPerson = DB::connection('hrd')
                ->table('v_hrd1_person')
                ->where('person_id', $user->idcard)
                ->first(['title_name_th', 'fname_th', 'lname_th']);

            if ($hrdPerson) {
                $thaiName = trim(
                    ($hrdPerson->title_name_th ?? '') . 
                    ($hrdPerson->fname_th ?? '') . ' ' . 
                    ($hrdPerson->lname_th ?? '')
                );

                // Update user name if we got data from HRD
                if (!empty($thaiName) && $thaiName !== ' ') {
                    $user->name = $thaiName;
                    $user->save();
                }
            }
        } catch (\Exception $e) {
            // Silently fail - don't break login if HRD is unavailable
            \Log::warning('Failed to update user name from HRD: ' . $e->getMessage());
        }
    }
}
