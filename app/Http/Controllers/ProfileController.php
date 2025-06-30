<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Post;
use App\Models\Comment;

class ProfileController extends Controller
{
    /**
     * Display the user's profile.
     *
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $user = Auth::user();
        $postsCount = Post::where('user_id', $user->id)->count();
        $commentsCount = Comment::where('user_id', $user->id)->count();
        
        return view('profiles.show', compact('user', 'postsCount', 'commentsCount'));
    }

    /**
     * Show the form for editing the user's profile.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        $user = Auth::user();
        return view('profiles.edit', compact('user'));
    }

    /**
     * Update the user's profile.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'bio' => 'nullable|string|max:1000',
            'current_password' => 'nullable|required_with:password',
            'password' => 'nullable|string|min:8|confirmed',
        ]);
        
        // Check current password if user is changing password
        if ($request->filled('current_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'The current password is incorrect.']);
            }
        }
        
        // Update user data
        $user->name = $request->name;
        $user->email = $request->email;
        $user->bio = $request->bio;
        
        // Update password if provided
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        
        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($user->avatar_path) {
                Storage::delete('public/' . $user->avatar_path);
            }
            
            // Store new avatar
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $user->avatar_path = $avatarPath;
        }
        
        $user->save();
        
        return redirect()->route('profile.show')->with('success', 'Profile updated successfully.');
    }

    /**
     * Show the user's posts.
     *
     * @return \Illuminate\Http\Response
     */
    public function posts()
    {
        $user = Auth::user();
        $posts = Post::where('user_id', $user->id)
            ->with('category')
            ->withCount('comments', 'likes')
            ->latest()
            ->paginate(10);
        
        return view('profiles.posts', compact('user', 'posts'));
    }

    /**
     * Show the user's comments.
     *
     * @return \Illuminate\Http\Response
     */
    public function comments()
    {
        $user = Auth::user();
        $comments = Comment::where('user_id', $user->id)
            ->with('post')
            ->latest()
            ->paginate(15);
        
        return view('profiles.comments', compact('user', 'comments'));
    }

    /**
     * Delete the user's account.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'password' => 'required',
        ]);
        
        // Check password
        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'The password is incorrect.']);
        }
        
        // Delete avatar if exists
        if ($user->avatar_path) {
            Storage::delete('public/' . $user->avatar_path);
        }
        
        // Logout and delete user
        Auth::logout();
        $user->delete();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/')->with('success', 'Your account has been deleted.');
    }
} 