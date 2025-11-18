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
    }

    public function updatedPhoto()
    {
        $this->resetValidation('photo');

        try {
            $this->validate([
                'photo' => 'image|max:6024',
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
                'photo' => 'required|image|max:6024',
            ]);

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

        /* Responsive style */
        @media (max-width: 767.98px) {
            .user-profile-header-banner img {
                block-size: 150px;
            }
            .user-profile-header .user-profile-img {
                inline-size: 100px;
            }
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
                    <div
                        class="user-profile-header d-flex flex-column flex-sm-row text-sm-start text-center mb-0">
                        <div class="flex-shrink-0 mt-n2 mx-sm-0 mx-auto">
                            @if ($photo)
                                <img
                                    src="{{ $photo->temporaryUrl() }}"
                                    alt="Banner image"
                                    class="d-block h-auto ms-0 ms-sm-5 rounded-4 user-profile-img object-cover"
                                />
                            @else
                                @if (!auth()->user()->avatar_url)
                                    <div
                                        class="d-block h-auto ms-0 ms-sm-5 rounded-4 user-profile-img bg-label-primary fw-bold"
                                        style="font-size: 3rem; line-height: 150px; text-align: center; font-weight: bold;"
                                    >
                                        {{ auth()->user()->initials }}
                                    </div>
                                @else
                                    <img
                                        src="{{ auth()->user()->avatar_url }}"
                                        alt="user image"
                                        class="d-block h-auto ms-0 ms-sm-5 rounded-4 user-profile-img object-cover"
                                    />
                                @endif
                            @endif
                        </div>
                        <div class="flex-grow-1 mt-4 mt-sm-12">
                            <div
                                x-data="{ uploading: false, progress: 0 }"
                                x-on:livewire-upload-start="uploading = true"
                                x-on:livewire-upload-finish="uploading = false"
                                x-on:livewire-upload-cancel="uploading = false"
                                x-on:livewire-upload-error="uploading = false"
                                x-on:livewire-upload-progress="progress = $event.detail.progress"
                                class="button-wrapper ms-7"
                            >
                                @if($photoUploaded)
                                    <button wire:click="savePhoto" class="btn btn-success mb-2 me-3">
                                        Save Photo
                                    </button>
                                @endif
                                <label for="upload" class="btn btn-primary me-3 mb-2" tabindex="0">
                                    <span class="d-none d-sm-block">Upload new photo</span>
                                    <i class="icon-base ri ri-upload-2-line d-block d-sm-none"></i>

                                    <input
                                        type="file"
                                        id="upload"
                                        wire:model="photo"
                                        hidden
                                        accept="image/png, image/jpeg"
                                    />
                                </label>

                                <button type="button" class="btn btn-outline-danger mb-2"
                                    wire:click="resetPhoto"
                                >
                                    Reset
                                </button>

                                <div>Allowed JPG, JPEG, or PNG. Max size of 1 Mb</div>

                                @error('photo')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                                <!-- BOOTSTRAP PROGRESS BAR (tampil saat upload) -->
                                <template x-if="uploading">
                                    <div class="mt-2 w-50">
                                        <div class="progress bg-label-primary" style="height: 12px">
                                            <div class="progress-bar" role="progressbar"
                                                x-bind:style="`width: ${progress}%`"
                                            >
                                                <span x-text="progress + '%'"></span>
                                            </div>
                                        </div>
                                    </div>
                                </template>
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
                                        label="E-mail"
                                        placeholder="Enter your e-mail"
                                        model="form.email"
                                        modifier="model"
                                    />
                                </div>
                                <div class="col-md-6 form-control-validation">
                                    <x-ui.form.input
                                        label="Username"
                                        placeholder="Enter your username"
                                        model="form.username"
                                        modifier="model"
                                    />
                                </div>
                                <div class="col-md-12">
                                    <x-ui.form.input
                                        label="Fullname"
                                        placeholder="Enter your fullname"
                                        model="form.name"
                                        modifier="model"
                                    />
                                </div>
                                <div class="col-md-6">
                                    <x-ui.form.input
                                        label="Birthplace"
                                        placeholder="Birth place address of the user"
                                        model="form.birthplace"
                                        modifier="model"
                                    />
                                </div>
                                <div class="col-md-6">
                                    <x-ui.form.input
                                        label="Birthdate"
                                        type="date"
                                        model="form.birthdate"
                                        modifier="model"
                                    />
                                </div>
                                <div class="col-md-6">
                                    <x-ui.form.input
                                        label="Phone"
                                        placeholder="Phone of the user "
                                        model="form.phone"
                                        modifier="model"
                                    />
                                </div>
                                <div class="col-md-6">
                                    <div class="form-control">
                                        <div class="form-check form-check-inline mb-0">
                                            <input class="form-check-input" type="radio" name="gender" id="male" value="l" wire:model='form.gender'>
                                            <label class="form-check-label" for="male">Male</label>
                                        </div>
                                        <div class="form-check form-check-inline mb-0">
                                            <input class="form-check-input" type="radio" name="gender" id="female" value="p" wire:model='form.gender'>
                                            <label class="form-check-label" for="female">Female</label>
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
                                        label="Address"
                                        placeholder="Address of the user "
                                        model="form.address"
                                        modifier="model"
                                        class="h-px-120"
                                    />
                                </div>
                            </div>
                            <div class="mt-6 text-end">
                                <button
                                    type="reset"
                                    class="btn btn-outline-secondary"
                                >
                                    Reset
                                </button>
                                <button
                                    type="submit"
                                    class="btn btn-primary me-3"
                                >
                                    Save changes
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
