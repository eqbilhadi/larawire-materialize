<?php

use App\Models\SysUser;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

new
    #[Layout('components.layouts.app.base', ['title' => 'My Profile'])]
class extends Component {
    use WithFileUploads;

    public SysUser $sysUser;

    public array $form = [
        'username' => '',
        'email' => '',
        'name' => '',
        'birthplace' => '',
        'birthdate' => '',
        'gender' => '',
        'phone' => '',
        'address' => '',
    ];

    public $photo;
    public bool $photoUploaded = false;
    public array $predefinedAvatars = [];

    public function mount()
    {
        $this->sysUser = \Illuminate\Support\Facades\Auth::user();

        $this->form['username'] = $this->sysUser->username;
        $this->form['email'] = $this->sysUser->email;
        $this->form['name'] = $this->sysUser->name;
        $this->form['birthplace'] = $this->sysUser->birthplace;
        $this->form['birthdate'] = $this->sysUser->birthdate;
        $this->form['gender'] = $this->sysUser->gender;
        $this->form['phone'] = $this->sysUser->phone;
        $this->form['address'] = $this->sysUser->address;

        $path = public_path('assets/img/avatars');
        if (File::exists($path)) {
            $files = File::files($path);
            foreach ($files as $file) {
                $this->predefinedAvatars[] = $file->getFilename();
            }
            sort($this->predefinedAvatars, SORT_NATURAL);
        }
    }

    public function updatedPhoto()
    {
        $this->resetValidation('photo');

        try {
            $this->validate([
                'photo' => 'image|max:1024',
            ]);

            $this->photoUploaded = true;

        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->photoUploaded = false;
            $this->photo = null;
            throw $e;
        }
    }

    public function resetPhoto()
    {
        if ($this->photo == null && !$this->photoUploaded) {
            if ($this->sysUser->avatar && Storage::disk('public')->exists($this->sysUser->avatar)) {
                Storage::disk('public')->delete($this->sysUser->avatar);
            }

            $this->sysUser->update(['avatar' => null]);
            return redirect()->route('my-profile');
        } else {
            $this->photo = null;
            $this->photoUploaded = false;
        }
    }

    public function savePhoto()
    {
        try {
            $this->validate([
                'photo' => 'required|image|max:1024',
            ]);

            if ($this->sysUser->avatar && Storage::disk('public')->exists($this->sysUser->avatar)) {
                Storage::disk('public')->delete($this->sysUser->avatar);
            }

            $path = $this->photo->store('profiles', 'public');

            $this->sysUser->update([
                'avatar' => $path,
            ]);

            flash()->success('Profile photo updated successfully');

            $this->photoUploaded = false;
            $this->photo = null;
            return redirect()->route('my-profile');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            flash()->error('Failed to update profile photo.');
        }
    }

    public function selectAvatar($filename)
    {
        $avatarPath = 'assets/img/avatars/' . $filename;

        if ($this->sysUser->avatar && Storage::disk('public')->exists($this->sysUser->avatar)) {
            Storage::disk('public')->delete($this->sysUser->avatar);
        }
        $this->sysUser->update(['avatar' => $avatarPath]);

        $this->photo = null;
        $this->photoUploaded = false;

        flash()->success('Avatar updated successfully');
        return redirect()->route('my-profile');
    }

    public function saveProfiles()
    {
        $this->validate([
                'form.username' => ['required', 'string', 'max:255', 'lowercase', Rule::unique(SysUser::class, 'username')->ignore($this->sysUser->id)],
                'form.email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(SysUser::class, 'email')->ignore($this->sysUser->id)],
                'form.name' => ['required', 'string', 'max:255'],
                'form.gender' => ['required', 'in:l,p'],
                'form.birthplace' => ['required', 'string', 'max:255'],
                'form.birthdate' => ['required', 'date'],
                'form.phone' => [ 'required', 'regex:/^[0-9]{10,15}$/'],
                'form.address' => ['required', 'string', 'max:255'],
            ], [], [
                'form.username'   => 'username',
                'form.email'      => 'email address',
                'form.name'       => 'full name',
                'form.gender'     => 'gender',
                'form.birthplace' => 'place of birth',
                'form.birthdate'  => 'date of birth',
                'form.phone'      => 'phone number',
                'form.address'    => 'address',
            ]
        );
        try {

            $this->sysUser->update($this->form);
            flash()->success('Your profile updated successfully');
        } catch (\Exception $e) {
            flash()->error('Something went wrong, try again later!');
            Log::error($e->getMessage());
        }

    }
}; ?>

<div>
    <style>
        .user-profile-header-banner img {
            block-size: 250px;
            inline-size: 100%;
            object-fit: cover;
        }

        .user-profile-header {
            margin-block-start: -2rem;
        }
        .user-profile-header .user-profile-img {
            border: 5px solid;
            border-color: var(--bs-paper-bg);
            inline-size: 150px;
        }

        .user-profile-img {
            width: 150px !important;
            height: 150px !important;
            object-fit: cover; /* Biar gambar crop otomatis */
            object-position: center;
            border-radius: 12px;
        }

        .avatar-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(80px, 1fr));
            gap: 15px;
        }
        .avatar-option {
            cursor: pointer;
            transition: transform 0.2s, border-color 0.2s;
            border: 2px solid transparent;
            border-radius: 50%;
            overflow: hidden;
        }
        .avatar-option:hover {
            transform: scale(1.1);
            border-color: #696cff; /* Warna primary template */
        }
        .avatar-option img {
            width: 100%;
            height: auto;
        }

        @media (max-width: 767.98px) {
            .user-profile-header-banner img { block-size: 150px; }
            .user-profile-header .user-profile-img { inline-size: 100px; }
        }
    </style>
    <div class="container-fluid flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-md-12">
                <div class="card mb-6">
                    <div class="user-profile-header-banner">
                        <img
                            src="../../assets/img/pages/profile-banner.png"
                            alt="Banner image"
                            class="rounded-top"
                        />
                    </div>
                    <div class="user-profile-header d-flex flex-column flex-sm-row text-sm-start text-center mb-0">
                        <div class="flex-shrink-0 mt-n2 mx-sm-0 mx-auto">
                            @if ($photo)
                                <img src="{{ $photo->temporaryUrl() }}" alt="user image" class="d-block h-auto ms-0 ms-sm-5 rounded-4 user-profile-img object-cover" />
                            @else
                                @php
                                    $currentAvatar = auth()->user()->avatar;
                                    // Cek apakah avatar adalah file assets (bawaan) atau file storage (upload)
                                    $isAsset = Str::startsWith($currentAvatar, 'assets/');
                                    $imageUrl = $isAsset ? asset($currentAvatar) : (auth()->user()->avatar_url ?? null);
                                @endphp

                                @if (!$imageUrl)
                                    <div class="d-block h-auto ms-0 ms-sm-5 rounded-4 user-profile-img bg-label-primary fw-bold"
                                         style="font-size: 3rem; line-height: 150px; text-align: center;">
                                        {{ auth()->user()->initials }}
                                    </div>
                                @else
                                    <img src="{{ $imageUrl }}" alt="user image" class="d-block h-auto ms-0 ms-sm-5 rounded-4 user-profile-img object-cover" />
                                @endif
                            @endif
                        </div>
                        <div class="flex-grow-1 mt-4 mt-sm-12">
                            <div x-data="{ uploading: false, progress: 0 }"
                                 x-on:livewire-upload-start="uploading = true"
                                 x-on:livewire-upload-finish="uploading = false; progress = 0;"
                                 x-on:livewire-upload-cancel="uploading = false; progress = 0;"
                                 x-on:livewire-upload-error="uploading = false; progress = 0;"
                                 x-on:livewire-upload-progress="progress = $event.detail.progress"
                                 class="button-wrapper ms-7">

                                @if($photoUploaded)
                                    <button wire:click="savePhoto" class="btn btn-success mb-2 me-3">
                                        <span wire:loading.remove wire:target="savePhoto">@lang('button.save_photo')</span>
                                        <span wire:loading wire:target="savePhoto">
                                            <span class="spinner-grow flex-shrink-0" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </span>
                                            <span class="flex-grow-1 ms-2">@lang('button.loading')</span>
                                        </span>
                                    </button>
                                @endif

                                <label for="upload" class="btn btn-primary me-3 mb-2" tabindex="0">
                                    <span class="d-none d-sm-block">@lang('button.upload_photo')</span>
                                    <i class="icon-base ri ri-upload-2-line d-block d-sm-none"></i>
                                    <input type="file" id="upload" wire:model="photo" hidden accept="image/png, image/jpeg" />
                                </label>

                                <button type="button" class="btn btn-info me-3 mb-2" data-bs-toggle="modal" data-bs-target="#avatarModal">
                                    <i class="ri-user-smile-line me-1"></i> Select Avatar
                                </button>

                                <button type="button" class="btn btn-outline-danger mb-2" wire:click="resetPhoto">
                                    @lang('button.reset')
                                </button>

                                <div>@lang('labels.message.allowed_format')</div>

                                @error('photo')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror

                                <template x-if="uploading">
                                    <div class="mt-2 w-50">
                                        <div class="progress bg-label-primary" style="height: 12px">
                                            <div class="progress-bar" role="progressbar" x-bind:style="`width: ${progress}%`">
                                                <span x-text="progress + '%'"></span>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="avatarModal" tabindex="-1" aria-hidden="true" wire:ignore.self>
                        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Choose an Avatar</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="avatar-grid">
                                        @foreach($predefinedAvatars as $avatarFile)
                                            <div class="avatar-option"
                                                 wire:click="selectAvatar('{{ $avatarFile }}')"
                                                 data-bs-dismiss="modal"
                                                 title="{{ $avatarFile }}">
                                                <img src="{{ asset('assets/img/avatars/' . $avatarFile) }}" alt="Avatar {{ $avatarFile }}">
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <form
                            wire:submit.prevent="saveProfiles"
                        >
                            <div class="row mt-1 g-5">
                                <div class="col-md-6 form-control-validation">
                                    <x-ui.form.input
                                        :label="__('rbac.user.form.lb.email')"
                                        :placeholder="__('rbac.user.form.ph.email')"
                                        model="form.email"
                                        modifier="model"
                                    />
                                </div>
                                <div class="col-md-6 form-control-validation">
                                    <x-ui.form.input
                                        :label="__('rbac.user.form.lb.username')"
                                        :placeholder="__('rbac.user.form.ph.username')"
                                        model="form.username"
                                        modifier="model"
                                    />
                                </div>
                                <div class="col-md-12">
                                    <x-ui.form.input
                                        :label="__('rbac.user.form.lb.name')"
                                        :placeholder="__('rbac.user.form.ph.name')"
                                        model="form.name"
                                        modifier="model"
                                    />
                                </div>
                                <div class="col-md-6">
                                    <x-ui.form.input
                                        :label="__('rbac.user.form.lb.birthplace')"
                                        :placeholder="__('rbac.user.form.ph.birthplace')"
                                        model="form.birthplace"
                                        modifier="model"
                                    />
                                </div>
                                <div class="col-md-6">
                                    <x-ui.form.input
                                        :label="__('rbac.user.form.lb.birthdate')"
                                        type="date"
                                        model="form.birthdate"
                                        modifier="model"
                                    />
                                </div>
                                <div class="col-md-6">
                                    <x-ui.form.input
                                        :label="__('rbac.user.form.lb.phone')"
                                        :placeholder="__('rbac.user.form.ph.phone')"
                                        model="form.phone"
                                        modifier="model"
                                    />
                                </div>
                                <div class="col-md-6">
                                    <div class="form-control">
                                        <div class="form-check form-check-inline mb-0">
                                            <input class="form-check-input" type="radio" name="gender" id="male" value="l" wire:model='form.gender'>
                                            <label class="form-check-label" for="male">@lang('labels.gender.male')</label>
                                        </div>
                                        <div class="form-check form-check-inline mb-0">
                                            <input class="form-check-input" type="radio" name="gender" id="female" value="p" wire:model='form.gender'>
                                            <label class="form-check-label" for="female">@lang('labels.gender.female')</label>
                                        </div>
                                    </div>
                                    @error('form.gender')
                                        <div class="invalid-feedback d-block">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-md-12">
                                    <x-ui.form.textarea
                                        :label="__('rbac.user.form.lb.address')"
                                        :placeholder="__('rbac.user.form.ph.address')"
                                        model="form.address"
                                        modifier="model"
                                        class="h-px-120"
                                    />
                                </div>
                            </div>
                            <div class="mt-6 text-end">
                                <button
                                    type="submit"
                                    class="btn btn-primary mt-3"
                                    wire:loading.attr="disabled"
                                    wire:target="saveProfiles"
                                >
                                    <span wire:loading.remove wire:target="saveProfiles">
                                        <i class="fa-regular fa-floppy-disk me-1"></i>
                                        @lang('button.save_changes')
                                    </span>
                                    <span wire:loading wire:target="saveProfiles">
                                        <span class="spinner-grow flex-shrink-0" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </span>
                                        <span class="flex-grow-1 ms-2">
                                            @lang('button.loading')
                                        </span>
                                    </span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- <div class="card">
                    <h5 class="card-header mb-1">Delete Account</h5>
                    <div class="card-body">
                        <div class="mb-6 col-12">
                            <div class="alert alert-warning">
                                <h6 class="alert-heading mb-1"
                                    >Are you sure you want to delete your
                                    account?</h6
                                >
                                <p class="mb-0"
                                    >Once you delete your account, there is no going
                                    back. Please be certain.</p
                                >
                            </div>
                        </div>
                        <form id="formAccountDeactivation" onsubmit="return false">
                            <div class="form-check mb-6">
                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    name="accountActivation"
                                    id="accountActivation" />
                                <label
                                    class="form-check-label"
                                    for="accountActivation"
                                    >I confirm my account deactivation</label
                                >
                            </div>
                            <button
                                type="submit"
                                class="btn btn-danger deactivate-account"
                                disabled="disabled">
                                Deactivate Account
                            </button>
                        </form>
                    </div>
                </div> -->
            </div>
        </div>
    </div>

</div>
