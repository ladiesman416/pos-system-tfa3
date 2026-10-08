<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class Users extends BaseController
{
    public function index(): string
    {
        return view('users/index', [
            'title' => 'User Accounts',
            'users' => (new UserModel())->findAll(),
        ]);
    }

    public function new(): string
    {
        return $this->form('New User', site_url('users/create'), false);
    }

    public function create()
    {
        $rules = [
            'username'  => 'required|min_length[3]|max_length[50]|is_unique[users.username]',
            'full_name' => 'required|min_length[2]|max_length[100]',
        ];

        if (! $this->validate($rules)) {
            return $this->form('New User', site_url('users/create'), false,
                $this->request->getPost(), $this->validator->getErrors());
        }

        (new UserModel())->insert([
            'username'   => $this->request->getPost('username'),
            'full_name'  => $this->request->getPost('full_name'),
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('/users')->with('success', 'User added.');
    }

    public function edit(int $id): string
    {
        $user = (new UserModel())->find($id);
        if (! $user) {
            throw PageNotFoundException::forPageNotFound();
        }

        return $this->form('Edit User', site_url('users/update/' . $id), true, $user);
    }

    public function update(int $id)
    {
        $model = new UserModel();
        $user  = $model->find($id);
        if (! $user) {
            throw PageNotFoundException::forPageNotFound();
        }

        $rules = [
            'username'  => "required|min_length[3]|max_length[50]|is_unique[users.username,id,{$id}]",
            'full_name' => 'required|min_length[2]|max_length[100]',
        ];

        // Only validate the avatar when a file was actually chosen
        $file    = $this->request->getFile('avatar');
        $hasFile = $file !== null && $file->getError() !== UPLOAD_ERR_NO_FILE;

        if ($hasFile) {
            $rules['avatar'] = [
                'label'  => 'Profile picture',
                'rules'  => 'uploaded[avatar]|is_image[avatar]|mime_in[avatar,image/jpg,image/jpeg,image/png]|ext_in[avatar,jpg,jpeg,png]|max_size[avatar,2048]',
                'errors' => [
                    'max_size' => 'The profile picture must be 2MB or smaller.',
                    'mime_in'  => 'The profile picture must be a JPG or PNG image.',
                    'ext_in'   => 'The profile picture must be a JPG or PNG image.',
                    'is_image' => 'The profile picture must be a valid image.',
                ],
            ];
        }

        if (! $this->validate($rules)) {
            $values = $this->request->getPost() + ['avatar' => $user['avatar']];
            return $this->form('Edit User', site_url('users/update/' . $id), true,
                $values, $this->validator->getErrors());
        }

        $data = [
            'username'  => $this->request->getPost('username'),
            'full_name' => $this->request->getPost('full_name'),
        ];

        if ($hasFile) {
            $data['avatar'] = $this->saveAvatar($file, $user['avatar'] ?? null);
        }

        $model->update($id, $data);

        return redirect()->to('/users')->with('success', 'User updated.');
    }


    private function saveAvatar($file, ?string $oldName): string
    {
        $originalDir = WRITEPATH . 'uploads/avatars/';
        $thumbDir    = FCPATH . 'uploads/avatars/';

        foreach ([$originalDir, $thumbDir] as $dir) {
            if (! is_dir($dir)) {
                mkdir($dir, 0755, true);
            }
        }

        $name = $file->getRandomName();
        $file->move($originalDir, $name);

        service('image')
            ->withFile($originalDir . $name)
            ->fit(150, 150, 'center')
            ->save($thumbDir . $name);

        if ($oldName) {
            @unlink($originalDir . $oldName);
            @unlink($thumbDir . $oldName);
        }

        return $name;
    }

    private function form(string $title, string $action, bool $isEdit, array $values = [], array $errors = []): string
    {
        return view('users/form', compact('title', 'action', 'isEdit', 'values', 'errors'));
    }
}