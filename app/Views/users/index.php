   <?= $this->extend('layout') ?>
   <?= $this->section('content') ?>
   <h1>User Accounts</h1>
<?php if (session()->getFlashdata('success')): ?>
    <p class="success"><?= esc(session()->getFlashdata('success')) ?></p>
<?php endif; ?>

<p><a href="<?= site_url('users/new') ?>">+ New User</a></p>

<table border="1" cellpadding="6">
    <tr><th>Avatar</th><th>Username</th><th>Full Name</th><th>Action</th></tr>
    <?php foreach ($users as $u): ?>
        <tr>
            <td>
                <img class="avatar"
                     src="<?= ! empty($u['avatar']) ? base_url('uploads/avatars/' . $u['avatar']) : base_url('images/placeholder.svg') ?>"
                     alt="Avatar of <?= esc($u['username']) ?>">
            </td>
            <td><?= esc($u['username']) ?></td>
            <td><?= esc($u['full_name']) ?></td>
            <td><a href="<?= site_url('users/edit/' . $u['id']) ?>">Edit</a></td>
        </tr>
    <?php endforeach; ?>
</table>
<?= $this->endSection() ?>