<?php
namespace App\Controllers;

use App\Models\UserModel;

class ProfileController extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->session = \Config\Services::session();
        helper(['form', 'url']);
    }

    public function profile()
    {
        $userId = session()->get('user_id');
        if (empty($userId)) {
            session()->setFlashdata('error', 'Anda harus login terlebih dahulu.');
            return redirect()->to('/login');
        }

        $user = $this->userModel->find($userId);
        if (!$user) {
            session()->setFlashdata('error', 'Data pengguna tidak ditemukan.');
            return redirect()->to('/login');
        }

        // Debug info
        log_message('debug', 'Profile photo from database: ' . ($user['profile_photo'] ?? 'null'));
        log_message('debug', 'Profile photo from session: ' . (session()->get('profile_photo') ?? 'null'));

        $data = [
            'title' => 'Profil Pengguna',
            'nama' => $user['nama'],
            'profile_photo' => $user['profile_photo'] ?? 'profile.jpg',
            'bio' => $user['bio'] ?? 'Belum ada bio.',
            'user_id' => $userId,
            'timestamp' => time() // For cache busting
        ];

        return view('profile', $data);
    }

    public function updateProfilePhoto()
    {
        $userId = session()->get('user_id');
        if (!$userId) {
            session()->setFlashdata('error', 'Silakan login kembali.');
            return redirect()->to('/login');
        }

        $validationRules = [
            'profile_photo' => [
                'rules' => 'uploaded[profile_photo]|is_image[profile_photo]|mime_in[profile_photo,image/jpg,image/jpeg,image/png]|max_size[profile_photo,2048]',
                'errors' => [
                    'uploaded' => 'Pilih file terlebih dahulu',
                    'is_image' => 'File harus berupa gambar',
                    'mime_in' => 'File harus berupa gambar (JPG/PNG)',
                    'max_size' => 'Ukuran file terlalu besar (max 2MB)'
                ]
            ]
        ];

        if (!$this->validate($validationRules)) {
            return redirect()->to('/profile')
                           ->withInput()
                           ->with('error', $this->validator->getError('profile_photo'));
        }

        $file = $this->request->getFile('profile_photo');
        
        if ($file->isValid() && !$file->hasMoved()) {
            try {
                // Delete old profile photo
                $user = $this->userModel->find($userId);
                if ($user['profile_photo'] && $user['profile_photo'] != 'default-profile.jpg') {
                    $oldFilePath = FCPATH . 'uploads/' . $user['profile_photo'];
                    if (file_exists($oldFilePath)) {
                        unlink($oldFilePath);
                    }
                }

                // Generate new filename with timestamp
                $newName = time() . '_' . $file->getRandomName();
                
                // Ensure uploads directory exists
                $uploadPath = FCPATH . 'uploads';
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0777, true);
                }
                
                // Move file
                $file->move($uploadPath, $newName);

                // Update database
                $result = $this->userModel->update($userId, ['profile_photo' => $newName]);
                
                if ($result) {
                    // Update session data
                    $userData = session()->get();
                    $userData['profile_photo'] = $newName;
                    session()->set($userData);
                    
                    session()->setFlashdata('success', 'Foto profil berhasil diubah!');
                    log_message('info', 'Profile photo updated successfully for user ' . $userId . ' with filename ' . $newName);
                } else {
                    throw new \Exception('Gagal update database');
                }
                
            } catch (\Exception $e) {
                log_message('error', 'Error updating profile photo: ' . $e->getMessage());
                session()->setFlashdata('error', 'Terjadi kesalahan saat mengunggah foto.');
                return redirect()->to('/profile');
            }
        } else {
            session()->setFlashdata('error', 'Gagal mengunggah foto.');
            return redirect()->to('/profile');
        }

        return redirect()->to('/profile?v=' . time());
    }

    public function showImage($filename)
    {
        $path = FCPATH . 'uploads/' . $filename;
        
        if (!file_exists($path)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        
        $file = new \CodeIgniter\Files\File($path);
        
        header('Content-Type: ' . $file->getMimeType());
        header('Content-Disposition: inline; filename="' . $filename . '"');
        header('Cache-Control: no-cache, private');
        header('Pragma: no-cache');
        header('Expires: 0');
        
        readfile($path);
        exit;
    }

    public function updateBio()
    {
        $userId = session()->get('user_id');
        if (!$userId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Silakan login kembali.'
            ]);
        }
    
        $bio = $this->request->getPost('bio');
        // Jika bio hanya berisi whitespace, simpan sebagai string kosong
        $bio = trim($bio) === '' ? '' : $bio;
    
        try {
            $result = $this->userModel->update($userId, ['bio' => $bio]);
            
            if ($result) {
                // Generate new CSRF token
                $csrf = csrf_hash();
                
                // Set the new CSRF token in response header
                $this->response->setHeader('X-CSRF-TOKEN', $csrf);
                
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Bio berhasil diperbarui!'
                ]);
            } else {
                throw new \Exception('Gagal memperbarui bio');
            }
        } catch (\Exception $e) {
            log_message('error', 'Error updating bio: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui bio.'
            ]);
        }
    }
}