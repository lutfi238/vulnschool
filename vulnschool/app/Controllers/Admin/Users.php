<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;

/**
 * Controller: Admin/Users
 * Manajemen user lengkap (Admin feature)
 */
class Users extends BaseController
{
    protected UserModel $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index()
    {
        $filterRole = $this->request->getGet('role');
        $filterStatus = $this->request->getGet('status');

        $builder = $this->userModel;

        if (!empty($filterRole)) {
            $builder = $builder->where('role', $filterRole);
        }
        
        if ($filterStatus !== null && $filterStatus !== '') {
            $builder = $builder->where('is_active', $filterStatus);
        }

        $users = $builder->orderBy('created_at', 'DESC')->findAll();

        return view('admin/users/index', [
            'title'        => 'Kelola User',
            'users'        => $users,
            'filterRole'   => $filterRole,
            'filterStatus' => $filterStatus,
        ]);
    }

    public function toggleStatus($id)
    {
        $user = $this->userModel->find($id);
        if (!$user) {
            return redirect()->back()->with('error', 'User tidak ditemukan');
        }

        if ($user['id'] == session()->get('user_id')) {
            return redirect()->back()->with('error', 'Tidak bisa menonaktifkan akun sendiri');
        }

        $newStatus = $user['is_active'] ? 0 : 1;
        $this->userModel->update($id, ['is_active' => $newStatus]);

        return redirect()->back()->with('success', 'Status user berhasil diperbarui');
    }

    public function delete($id)
    {
        $user = $this->userModel->find($id);
        if (!$user) {
            return redirect()->back()->with('error', 'User tidak ditemukan');
        }

        if ($user['id'] == session()->get('user_id')) {
            return redirect()->back()->with('error', 'Tidak bisa menghapus akun sendiri');
        }

        $this->userModel->delete($id);
        return redirect()->back()->with('success', 'User berhasil dihapus');
    }
}
