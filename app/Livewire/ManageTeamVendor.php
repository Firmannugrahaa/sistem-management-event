<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ManageTeamVendor extends Component
{
    use WithPagination;

    // Public properties for the component
    public $activeTab = 'team'; // 'team' or 'vendor'
    public $viewMode = 'table'; // 'table' or 'grid'
    public $search = '';
    public $roleFilter = '';
    public $approvalStatusFilter = '';
    public $showCreateModal = false;
    public $showEditModal = false;
    public $showDeleteModal = false;
    public $userIdToDelete = null;

    // Form fields
    public $userId;
    public $name;
    public $email;
    public $phone;
    public $username;
    public $position;
    public $department;
    public $role;
    public $type = 'team_member';
    public $status = 'active';
    public $approval_status = 'pending';

    // Pagination
    protected $paginationTheme = 'tailwind';

    // Validation rules
    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignore($this->userId)
            ],
            'phone' => 'nullable|string|max:20',
            'username' => [
                'required',
                'string',
                'max:255',
                Rule::unique('users')->ignore($this->userId)
            ],
            'position' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
            'role' => 'required|string',
            'type' => 'required|in:team_member,vendor',
            'status' => 'required|in:active,inactive',
            'approval_status' => 'required|in:pending,approved,rejected',
        ];
    }

    // Validation messages
    protected function messages()
    {
        return [
            'name.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan.',
            'username.required' => 'Username wajib diisi.',
            'username.unique' => 'Username sudah digunakan.',
            'role.required' => 'Role wajib diisi.',
            'type.required' => 'Tipe pengguna wajib diisi.',
            'status.required' => 'Status wajib diisi.',
            'approval_status.required' => 'Status approval wajib diisi.',
        ];
    }

    public function mount()
    {
        $this->authorizeAccess();
    }

    public function render()
    {
        $this->authorizeAccess();

        // Get users based on active tab
        $query = User::query();

        // Apply tab filter (team vs vendor)
        if ($this->activeTab === 'team') {
            $query->where('type', 'team_member');
        } elseif ($this->activeTab === 'vendor') {
            $query->where('type', 'vendor');
        }

        // Apply search filter
        if ($this->search) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%')
                  ->orWhere('username', 'like', '%' . $this->search . '%')
                  ->orWhere('phone', 'like', '%' . $this->search . '%');
            });
        }

        // Apply role filter
        if ($this->roleFilter) {
            $query->whereHas('roles', function($q) {
                $q->where('name', $this->roleFilter);
            });
        }

        // Apply approval status filter
        if ($this->approvalStatusFilter) {
            $query->where('approval_status', $this->approvalStatusFilter);
        }

        $users = $query->with('roles')->orderBy('created_at', 'desc')->paginate(10);

        // Get all roles for filter dropdown
        $roles = \Spatie\Permission\Models\Role::all();

        return view('livewire.manage-team-vendor', [
            'users' => $users,
            'roles' => $roles,
        ]);
    }

    public function updatedViewMode()
    {
        // Reset pagination when changing view mode
        $this->resetPage();
    }

    public function updatedActiveTab()
    {
        // Reset pagination when changing tab
        $this->resetPage();
        $this->reset(['search', 'roleFilter', 'approvalStatusFilter']);
    }

    public function changeTab($tab)
    {
        $this->activeTab = $tab;
        $this->resetPage();
        $this->reset(['search', 'roleFilter', 'approvalStatusFilter']);
    }

    public function toggleViewMode($mode)
    {
        $this->viewMode = $mode;
        $this->resetPage();
    }

    public function closeModal()
    {
        $this->showCreateModal = false;
        $this->showEditModal = false;
        $this->resetForm();
    }

    public function closeDeleteModal()
    {
        $this->showDeleteModal = false;
        $this->userIdToDelete = null;
    }

    public function create()
    {
        $this->resetForm();
        $this->type = $this->activeTab === 'team' ? 'team_member' : 'vendor';
        $this->showCreateModal = true;
    }

    public function edit($userId)
    {
        $this->resetForm();

        $user = User::findOrFail($userId);
        $this->userId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->phone = $user->phone;
        $this->username = $user->username;
        $this->position = $user->position;
        $this->department = $user->department;
        $this->type = $user->type;
        $this->status = $user->status;
        $this->approval_status = $user->approval_status;

        // Get the user's role
        $role = $user->roles->first();
        $this->role = $role ? $role->name : '';

        $this->showEditModal = true;
    }

    public function delete($userId)
    {
        $this->userIdToDelete = $userId;
        $this->showDeleteModal = true;
    }

    public function confirmDelete()
    {
        $this->authorize('user.delete');

        $user = User::findOrFail($this->userIdToDelete);
        $user->delete();

        $this->showDeleteModal = false;
        $this->userIdToDelete = null;

        session()->flash('message', 'Data berhasil dihapus.');
    }

    public function save()
    {
        $this->validate();

        if ($this->userId) {
            // Update existing user
            $this->authorize('user.update');

            $user = User::findOrFail($this->userId);
            $user->update([
                'name' => $this->name,
                'email' => $this->email,
                'phone' => $this->phone,
                'username' => $this->username,
                'position' => $this->position,
                'department' => $this->department,
                'type' => $this->type,
                'status' => $this->status,
                'approval_status' => $this->approval_status,
            ]);

            // Sync role
            $user->syncRoles([$this->role]);

            session()->flash('message', 'Data berhasil diperbarui.');
        } else {
            // Create new user
            $this->authorize('user.create');

            $user = User::create([
                'name' => $this->name,
                'email' => $this->email,
                'phone' => $this->phone,
                'username' => $this->username,
                'position' => $this->position,
                'department' => $this->department,
                'type' => $this->type,
                'status' => $this->status,
                'approval_status' => $this->approval_status,
                'password' => bcrypt(Str::random(12)), // Generate random password
            ]);

            // Assign role
            $user->assignRole($this->role);

            session()->flash('message', 'Data berhasil ditambahkan.');
        }

        $this->showCreateModal = false;
        $this->showEditModal = false;
        $this->resetForm();
    }

    public function approve($userId)
    {
        $this->authorize('team_member.approve', 'vendor.approve');

        $user = User::findOrFail($userId);

        // Determine which permission to check based on user type
        if ($user->type === 'team_member') {
            $this->authorize('team_member.approve');
        } else {
            $this->authorize('vendor.approve');
        }

        $user->update(['approval_status' => 'approved']);

        session()->flash('message', 'Pengguna berhasil disetujui.');
    }

    public function reject($userId)
    {
        $this->authorize('team_member.approve', 'vendor.approve');

        $user = User::findOrFail($userId);

        // Determine which permission to check based on user type
        if ($user->type === 'team_member') {
            $this->authorize('team_member.approve');
        } else {
            $this->authorize('vendor.approve');
        }

        $user->update(['approval_status' => 'rejected']);

        session()->flash('message', 'Pengguna berhasil ditolak.');
    }

    private function resetForm()
    {
        $this->reset([
            'userId', 'name', 'email', 'phone', 'username',
            'position', 'department', 'role', 'type', 'status', 'approval_status'
        ]);
    }

    private function authorizeAccess()
    {
        // Check if user has permission to access team or vendor management
        $hasTeamPermission = Gate::allows('team_member.read') || Gate::allows('team_member.create') ||
                            Gate::allows('team_member.update') || Gate::allows('team_member.delete') ||
                            Gate::allows('team_member.approve');

        $hasVendorPermission = Gate::allows('vendor.read') || Gate::allows('vendor.create') ||
                              Gate::allows('vendor.update') || Gate::allows('vendor.delete') ||
                              Gate::allows('vendor.approve');

        if (!$hasTeamPermission && !$hasVendorPermission) {
            abort(403, 'Anda tidak memiliki izin untuk mengakses halaman ini.');
        }
    }
}
