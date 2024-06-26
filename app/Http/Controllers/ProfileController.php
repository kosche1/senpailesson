<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\View\View;
use App\Models\AuditTrail;
use Illuminate\Http\Request;
use Spatie\Backup\Helpers\Format;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\ProfileUpdateRequest;


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
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

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
        Auth::logout();
        
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return Redirect::to('/');
    }
    public function delete(Request $request): RedirectResponse
    {
        AuditTrail::create([
            'user_id'=> auth()->user()->id,
            'description' => auth()->user()->name . ' has delete a user account ',
        ]);
        // dd($request->all());
        $user = \App\Models\User::find($request->id);
        if ($user) {
            $user->delete();
            return redirect()->back()->with('status', 'user-deleted');
        }
        
    }

    public function adduser()
    {
        return view('AddUser');
    }

    public function addpost(Request $request)
    {
        AuditTrail::create([
            'user_id'=> auth()->user()->id,
            'description' => auth()->user()->name . ' has added a new user. ',
        ]);

        // dd($request->all());
        $user = \App\Models\User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);
        if ($user) {
            return redirect()->route('dashboard')->with('status', 'user-added');
        }
    }

    public function EditUser(Request $request)
    {
        AuditTrail::create([
            'user_id'=> auth()->user()->id,
            'description' => auth()->user()->name . ' has modify a user account ',
        ]);
        $id = $request->id;
        $name = $request->name;
        $email = $request->email;

        $user = User::find($id);

        $user->name = $name;
        $user->email = $email;
        $user->save();
        return Redirect::route('dashboard');
    }

    public function AuditTrail()
    {
        return view('AuditTrail');
    }
    public function BackUp()
    {
        $disk = Storage::disk(config('backup.backup.destination.disks')[0]);
        $files = $disk->files(config('backup.backup.name'));
        $backups = [];
        // make an array of backup files, with their filesize and creation date
        foreach ($files as $k => $f) {
            // only take the zip files into account
            if (substr($f, -4) == '.zip' && $disk->exists($f)) {
                $backups[] = [
                    'file_path' => $f,
                    'file_name' => str_replace(config('backup.backup.name') . '/', '', $f),
                    'file_size' => Format::humanReadableSize($disk->size($f)),
                    'last_modified' => Carbon::createFromTimestamp($disk->lastModified($f)),
                ];
            }
        }
        // reverse the backups, so the newest one would be on top
        $backups = array_reverse($backups);
        //  dd($backups);
        return view('BackUp')->with(compact('backups'));
    }

    public function runBackup()
    {   
        AuditTrail::create([
            'user_id'=> auth()->user()->id,
            'description' => auth()->user()->name . ' Backup has been created ',
        ]);
        Artisan::call('backup:run', ['--no-interaction' => true]);
        Log::info('backup has runed');
        return redirect()->back()->with('success', 'Backup executed successfully!');
    }
    public function restoreBackup()
    {   
        AuditTrail::create([
            'user_id'=> auth()->user()->id,
            'description' => auth()->user()->name . ' Backup has been restored ',
        ]);
        Artisan::call('backup:restore', ['--no-interaction' => true, '--reset'=> true]);
        Log::info('backup has runed');
        return redirect()->back()->with('success', 'Backup executed successfully!');
    }

    public function download($file_name)
    {   
        AuditTrail::create([
            'user_id'=> auth()->user()->id,
            'description' => auth()->user()->name . ' Backup has been downloaded ',
        ]);
        $file = config('backup.backup.name') . '/' . $file_name;
        $disk = Storage::disk(config('backup.backup.destination.disks')[0]);
    
        if ($disk->exists($file)) {
            $stream = $disk->readStream($file);
            return \Response::stream(function () use ($stream) {
                fpassthru($stream);
            }, 200, [
                "Content-Type" => $disk->mimeType($file),
                "Content-Length" => $disk->size($file),
                "Content-disposition" => "attachment; filename=\"" . basename($file) . "\"",
            ]);
        } else {
            abort(404, "The backup file doesn't exist.");
        }
    }

    public function backup_delete($file_name)
    {
        // dd($file_name);
        AuditTrail::create([
            'user_id'=> auth()->user()->id,
            'description' => auth()->user()->name . ' Backup has been deleted ',
        ]);
        $disk = Storage::disk(config('backup.backup.destination.disks')[0]);
        if ($disk->exists(config('backup.backup.name') . '/' . $file_name)) {
            $disk->delete(config('backup.backup.name') . '/' . $file_name);
            return redirect()->back();
        } else {
            abort(404, "The backup file doesn't exist.");
        }
    }

    public function search()
    {   
        $ideas = Idea::orderBy(' created_at','DESC');

        if(request()->has ('search')){
            $ideas = $ideas->where('content','like', '%' . request()->get('search','') . '%' );
        }

        return view(' dashboard' , [
            'ideas' => $ideas->paginate(5)
        ]);
    }
}
