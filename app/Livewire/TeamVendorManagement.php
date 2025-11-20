<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\Vendor;
use Livewire\Component;
use Livewire\WithPagination;
use App\Notifications\UserApprovedNotification;
use App\Notifications\UserRejectedNotification;
use App\Notifications\VendorApprovedNotification;
use App\Notifications\VendorRejectedNotification;

class TeamVendorManagement extends Component
{
    use WithPagination;

    public $activeTab = 'team'; // 'team' or 'vendor'
    public $viewMode = 'list'; // 'list' or 'grid'
    public $search = '';
    public $filterRole = ''; // For team members
    public $filterStatus = ''; // For both team members and vendors

    public $userToDeleteId;
    public $vendorToDeleteId;

    protected $listeners = [
        'userUpdated' => '$refresh',
        'userCreated' => '$refresh',
        'vendorUpdated' => '$refresh',
        'vendorCreated' => '$refresh',
        'deleteConfirmed' => 'deleteItem'
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterRole()
    {
        $this->resetPage();
    }

    public function updatingFilterStatus()
    {
        $this->resetPage();
    }

    public function switchTab($tab)
    {
        $this->activeTab = $tab;
        $this->resetPage();
        $this->search = '';
        $this->filterRole = '';
        $this->filterStatus = '';
    }

    public function toggleViewMode()
    {
        $this->viewMode = $this->viewMode === 'list' ? 'grid' : 'list';
    }

    public function openCreateUserModal()
    {
        $this->dispatch('openCreateUserModal');
    }

    public function openEditUserModal(User $user)
    {
        $this->dispatch('openEditModal', user: $user);
    }

    public function openCreateVendorModal()
    {
        $this->dispatch('openCreateVendorModal');
    }

    public function openEditVendorModal(Vendor $vendor)
    {
        $this->dispatch('openEditVendorModal', vendor: $vendor);
    }

    public function confirmDeleteItem($type, $id, $name, $email)
    {
        if ($type === 'user') {
            $this->userToDeleteId = $id;
            $this->vendorToDeleteId = null;
            $title = 'Delete User';
            $message = 'Apakah Anda yakin ingin menghapus user {name} ({email})? Tindakan ini tidak dapat dibatalkan.';
        } elseif ($type === 'vendor') {
            $this->vendorToDeleteId = $id;
            $this->userToDeleteId = null;
            $title = 'Delete Vendor';
            $message = 'Apakah Anda yakin ingin menghapus vendor {name} ({email})? Tindakan ini tidak dapat dibatalkan.';
        } else {
            return;
        }

        $this->dispatch('open-confirmation-data', [
            'formId' => null,
            'title' => $title,
            'templateMessage' => $message,
            'data' => ['name' => $name, 'email' => $email],
            'type' => 'danger',
            'livewireEvent' => 'deleteConfirmed'
        ]);
    }

    public function deleteItem()
    {
        if ($this->userToDeleteId) {
            $user = User::find($this->userToDeleteId);
            if ($user) {
                if ($user->hasRole('SuperUser') || $user->id === auth()->id()) {
                    session()->flash('error', 'Cannot delete SuperUser or currently logged in user.');
                    $this->userToDeleteId = null;
                    return;
                }
                try {
                    $user->delete();
                    session()->flash('message', 'User deleted successfully.');
                    $this->dispatch('pg:eventRefresh');
                } catch (\Exception $e) {
                    session()->flash('error', 'Error deleting user: ' . $e->getMessage());
                }
            }
            $this->userToDeleteId = null;
        } elseif ($this->vendorToDeleteId) {
            $vendor = Vendor::find($this->vendorToDeleteId);
            if ($vendor) {
                try {
                    $vendor->delete();
                    session()->flash('message', 'Vendor deleted successfully.');
                    $this->dispatch('pg:eventRefresh');
                } catch (\Exception $e) {
                    session()->flash('error', 'Error deleting vendor: ' . $e->getMessage());
                }
            }
            $this->vendorToDeleteId = null;
        }
    }

    public function approveUser($userId)
    {
        $user = User::find($userId);
        if ($user) {
            $user->status = 'approved';
            $user->save();
            $user->notify(new UserApprovedNotification($user, auth()->user()));
            session()->flash('message', 'User approved successfully.');
            $this->dispatch('userUpdated');
        }
    }

    public function rejectUser($userId)
    {
        $user = User::find($userId);
        if ($user) {
            $user->status = 'rejected';
            $user->save();
            $user->notify(new UserRejectedNotification($user, auth()->user()));
            session()->flash('message', 'User rejected successfully.');
            $this->dispatch('userUpdated');
        }
    }

    public function approveVendor($vendorId)
    {
        $vendor = Vendor::find($vendorId);
        if ($vendor) {
            $vendor->status = 'approved';
            $vendor->save();
            $vendor->user->notify(new VendorApprovedNotification($vendor, auth()->user()));
            session()->flash('message', 'Vendor approved successfully.');
            $this->dispatch('vendorUpdated');
        }
    }

    public function rejectVendor($vendorId)
    {
        $vendor = Vendor::find($vendorId);
        if ($vendor) {
            $vendor->status = 'rejected';
            $vendor->save();
            $vendor->user->notify(new VendorRejectedNotification($vendor, auth()->user()));
            session()->flash('message', 'Vendor rejected successfully.');
            $this->dispatch('vendorUpdated');
        }
    }

    public function render()
    {
        $users = collect();
        $vendors = collect();
        $roles = []; // To be populated with available roles for filtering

        if ($this->activeTab === 'team') {
            $query = User::with('roles');

            if (auth()->user()->hasRole('Owner')) {
                $query->where('owner_id', auth()->id());
            } elseif (auth()->user()->hasRole('Admin')) {
                $query->whereHas('roles', function($q) {
                    $q->whereIn('name', ['Admin', 'Staff', 'Vendor']);
                });
            }

            if ($this->search) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%');
                });
            }

            if ($this->filterRole) {
                $query->whereHas('roles', function ($q) {
                    $q->where('name', $this->filterRole);
                });
            }

            if ($this->filterStatus) {
                $query->where('status', $this->filterStatus);
            }

            $users = $query->paginate(10, ['*'], 'userPage');
            $roles = \Spatie\Permission\Models\Role::where('name', '!=', 'SuperUser')->pluck('name', 'name');

        } elseif ($this->activeTab === 'vendor') {
            $query = Vendor::with(['user', 'serviceType']);

            if (auth()->user()->hasRole('Owner')) {
                // Assuming owner_id exists on Vendor or through its user relationship
                // This might need adjustment based on actual schema
                $query->whereHas('user', function($q) {
                    $q->where('owner_id', auth()->id());
                });
            }

            if ($this->search) {
                $query->where(function ($q) {
                    $q->where('company_name', 'like', '%' . $this->search . '%')
                      ->orWhereHas('user', function ($userQuery) {
                          $userQuery->where('name', 'like', '%' . $this->search . '%')
                                    ->orWhere('email', 'like', '%' . $this->search . '%');
                      });
                });
            }

            if ($this->filterStatus) {
                $query->where('status', $this->filterStatus);
            }

            $vendors = $query->paginate(10, ['*'], 'vendorPage');
        }

        return view('livewire.team-vendor-management', [
            'users' => $users,
            'vendors' => $vendors,
            'roles' => $roles,
            'statuses' => ['pending', 'approved', 'rejected'], // Common statuses for filtering
        ]);
    }
}
