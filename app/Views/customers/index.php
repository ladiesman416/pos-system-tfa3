<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<h1>Customer Accounts</h1>
<?php if (session()->getFlashdata('success')): ?>
    <p class="success"><?= esc(session()->getFlashdata('success')) ?></p>
<?php endif; ?>

<p><a href="<?= site_url('customers/new') ?>">+ New Customer</a></p>

<table border="1" cellpadding="6">
    <tr><th>Full Name</th><th>Email</th><th>Phone</th><th>Action</th></tr>
    <?php foreach ($customers as $c): ?>
        <tr>
            <td><?= esc($c['full_name']) ?></td>
            <td><?= esc($c['email']) ?></td>
            <td><?= esc($c['phone']) ?></td>
            <td><a href="<?= site_url('customers/edit/' . $c['id']) ?>">Edit</a></td>
        </tr>
    <?php endforeach; ?>
</table>
<?= $this->endSection() ?>