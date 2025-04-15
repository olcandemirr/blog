<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'selected_ids' => 'required|array',
            'selected_ids.*' => 'exists:users,id',
        ]);

        $deletedCount = 0;
        $deletedUserNames = []; // Silinen kullanıcı adlarını tutalım

        foreach ($request->selected_ids as $id) {
            // Admin kendi hesabını silemesin
            if ($id == auth()->id()) {
                continue;
            }
            
            $user = User::find($id);
            if ($user) {
                $deletedUserNames[] = $user->name . ' (ID: ' . $user->id . ')'; // Log için ismi kaydet
                // Avatar silinmesi
                if ($user->avatar) {
                    Storage::disk('public')->delete($user->avatar);
                }
                $user->delete(); // User silinince ilişkili postlar, yorumlar, likelar da silinir (cascade)
                $deletedCount++;
            }
        }

        if ($deletedCount > 0) {
            log_activity(
                'Admin bulk deleted users', 
                null, // Toplu işlem olduğu için belirli bir subject yok
                ['count' => $deletedCount, 'deleted_users' => $deletedUserNames, 'admin_id' => auth()->id()] 
            );
            return redirect()->route('admin.users.index')
                ->with('success', $deletedCount . ' user(s) deleted successfully.');
        } else {
            return redirect()->route('admin.users.index')
                ->with('error', 'No users were deleted. You might have tried to delete your own account.');
        }
    }

    public function update(Request $request, User $user)
    {
        // ... validation ...
        
        $oldData = $user->getOriginal(); // Değişiklik öncesi veriyi al (opsiyonel)
        
        // ... update logic ...
        
        $user->update($updateData);
        
        log_activity(
            'Admin updated user profile', 
            $user, 
            ['old' => $oldData, 'new' => $updateData, 'admin_id' => auth()->id()] // Değişiklikleri logla (opsiyonel)
        );
        
        return redirect()->route('admin.users.index')->with('success', 'User updated successfully');
    }
} 