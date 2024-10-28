<?php

namespace App\Controllers;

use App\Models\UserModel;

class ChangePassword extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function update()
    {
        $userId = session()->get('user_id');
        if (!$userId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Anda harus login terlebih dahulu.'
            ]);
        }

        $user = $this->userModel->find($userId);
        if (!$user) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Data pengguna tidak ditemukan.'
            ]);
        }

        $oldPassword = $this->request->getPost('old_password');
        $newPassword = $this->request->getPost('new_password');
        $confirmPassword = $this->request->getPost('confirm_password');

        // Validasi password lama
        if (!password_verify($oldPassword, $user['password'])) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Password lama salah.'
            ]);
        }

        // Validasi password baru dan konfirmasi
        if ($newPassword !== $confirmPassword) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Password baru dan konfirmasi tidak cocok.'
            ]);
        }

        try {
            // Update password
            $result = $this->userModel->update($userId, [
                'password' => password_hash($newPassword, PASSWORD_DEFAULT)
            ]);

            if ($result) {
                session()->setFlashdata('success', 'Password berhasil diubah');
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Password berhasil diubah'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Gagal mengubah password.'
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'Error saat mengubah password: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem saat mengubah password.'
            ]);
        }
    }
}
