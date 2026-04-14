<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use App\Http\Requests\Candidate\UpdateProfileRequest;
use App\Http\Requests\Candidate\UploadCvRequest;
use App\Http\Requests\Candidate\UploadProfileImageRequest;
use App\Services\CvStorageService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function __construct(
        private CvStorageService $cvStorageService
    ) {}

    public function edit(Request $request): View
    {
        $profile = $request->user()->candidateProfile;

        return view('candidate.profile', compact('profile'));
    }

    public function update(UpdateProfileRequest $request): RedirectResponse
    {
        $profile = $request->user()->candidateProfile;
        $profile->update($request->validated());

        return back()->with('success', 'Profile updated successfully.');
    }

    public function uploadCv(UploadCvRequest $request): RedirectResponse
    {
        $profile = $request->user()->candidateProfile;

        if ($profile->cv_path) {
            $this->cvStorageService->delete($profile->cv_path);
        }

        $path = $this->cvStorageService->upload(
            $request->file('cv'),
            $request->user()->id
        );

        $profile->update(['cv_path' => $path]);

        return back()->with('success', 'CV uploaded successfully.');
    }

    public function deleteCv(Request $request): RedirectResponse
    {
        $profile = $request->user()->candidateProfile;

        if (! $profile->cv_path) {
            return back()->with('error', 'No CV to delete.');
        }

        $this->cvStorageService->delete($profile->cv_path);
        $profile->update(['cv_path' => null]);

        return back()->with('success', 'CV deleted successfully.');
    }

    public function downloadCv(Request $request): RedirectResponse
    {
        $profile = $request->user()->candidateProfile;

        if (! $profile->cv_path) {
            return back()->with('error', 'No CV available.');
        }

        $url = $this->cvStorageService->getSignedUrl($profile->cv_path);

        return redirect()->away($url);
    }

    public function uploadProfileImage(UploadProfileImageRequest $request): RedirectResponse
    {
        $profile = $request->user()->candidateProfile;

        if ($profile->profile_image) {
            Storage::disk('public')->delete('profile-images/' . $profile->profile_image);
        }

        $file = $request->file('profile_image');
        $filename = 'profile_' . $request->user()->id . '_' . time() . '.' . $file->getClientOriginalExtension();
        $file->storeAs('profile-images', $filename, 'public');

        $profile->update(['profile_image' => $filename]);

        return back()->with('success', 'Profile image uploaded successfully.');
    }

    public function deleteProfileImage(Request $request): RedirectResponse
    {
        $profile = $request->user()->candidateProfile;

        if (! $profile->profile_image) {
            return back()->with('error', 'No profile image to delete.');
        }

        Storage::disk('public')->delete('profile-images/' . $profile->profile_image);
        $profile->update(['profile_image' => null]);

        return back()->with('success', 'Profile image deleted successfully.');
    }
}
