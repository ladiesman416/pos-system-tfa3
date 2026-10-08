<?php

namespace App\Controllers;

use App\Models\CustomerModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class Customers extends BaseController
{
    private array $rules = [
        'full_name' => 'required|min_length[2]|max_length[100]',
        'email'     => 'required|valid_email|max_length[100]',
        'phone'     => 'permit_empty|max_length[20]',
    ];

    public function index(): string
    {
        return view('customers/index', [
            'title'     => 'Customer Accounts',
            'customers' => (new CustomerModel())->findAll(),
        ]);
    }

    public function new(): string
    {
        return $this->form('New Customer', site_url('customers/create'));
    }

    public function create()
    {
        if (! $this->validate($this->rules)) {
            return $this->form('New Customer', site_url('customers/create'),
                $this->request->getPost(), $this->validator->getErrors());
        }

        (new CustomerModel())->insert([
            'full_name'  => $this->request->getPost('full_name'),
            'email'      => $this->request->getPost('email'),
            'phone'      => $this->request->getPost('phone'),
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('/customers')->with('success', 'Customer added.');
    }

    public function edit(int $id): string
    {
        $customer = (new CustomerModel())->find($id);
        if (! $customer) {
            throw PageNotFoundException::forPageNotFound();
        }

        return $this->form('Edit Customer', site_url('customers/update/' . $id), $customer);
    }

    public function update(int $id)
    {
        $model = new CustomerModel();
        if (! $model->find($id)) {
            throw PageNotFoundException::forPageNotFound();
        }

        if (! $this->validate($this->rules)) {
            return $this->form('Edit Customer', site_url('customers/update/' . $id),
                $this->request->getPost(), $this->validator->getErrors());
        }

        $model->update($id, [
            'full_name' => $this->request->getPost('full_name'),
            'email'     => $this->request->getPost('email'),
            'phone'     => $this->request->getPost('phone'),
        ]);

        return redirect()->to('/customers')->with('success', 'Customer updated.');
    }

    private function form(string $title, string $action, array $values = [], array $errors = []): string
    {
        return view('customers/form', compact('title', 'action', 'values', 'errors'));
    }
}