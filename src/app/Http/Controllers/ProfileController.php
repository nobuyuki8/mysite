<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        // Breeze標準（名前・メールなど）
        $user->fill($request->validated());
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        // ---- 追加項目のバリデーション ----
        $validated = $request->validate([
            'area' => 'nullable|string|max:50',
            'introduction' => 'nullable|string',
            'gardening_experience' => 'nullable|string|max:100',
            'favorite_crop' => 'nullable|string|max:100',
            'profile_image' => 'nullable|image|max:2048',
        ]);

        // ---- プロフィール画像のアップロード処理 ----
        if ($request->hasFile('profile_image')) {

            // 古い画像の削除
            if ($user->profile_image) {
                Storage::disk('public')->delete($user->profile_image);
            }

            // 新しい画像を保存
            $path = $request->file('profile_image')->store('profiles', 'public');
            $user->profile_image = $path;
        }

        // ---- その他の追加項目保存 ----
        $user->area = $validated['area'] ?? $user->area;
        $user->introduction = $validated['introduction'] ?? $user->introduction;
        $user->gardening_experience = $validated['gardening_experience'] ?? $user->gardening_experience;
        $user->favorite_crop = $validated['favorite_crop'] ?? $user->favorite_crop;

        // 保存
        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
