<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<h1><?= esc($title) ?></h1>

<form action="<?= esc($action) ?>" method="post" enctype="multipart/form-data">
    <?= csrf_field() ?>

    <p>
        <label>Username<br>
        <input type="text" name="username" value="<?= esc($values['username'] ?? '') ?>"></label>
        <?php if (isset($errors['username'])): ?><br><small class="error"><?= esc($errors['username']) ?></small><?php endif; ?>
    </p>
    <p>
        <label>Full Name<br>
        <input type="text" name="full_name" value="<?= esc($values['full_name'] ?? '') ?>"></label>
        <?php if (isset($errors['full_name'])): ?><br><small class="error"><?= esc($errors['full_name']) ?></small><?php endif; ?>
    </p>

    <?php if ($isEdit): ?>
    <p>
        <?php $current = $values['avatar'] ?? null; ?>
        <img class="avatar" src="<?= $current ? base_url('uploads/avatars/' . $current) : base_url('images/placeholder.svg') ?>" alt="Current avatar">
    </p>
    <p>
        <label>Profile Picture (JPG or PNG, max 2MB)<br>
        <input type="file" name="avatar" accept=".jpg,.jpeg,.png,image/jpeg,image/png"></label>
        <?php if (isset($errors['avatar'])): ?><br><small class="error"><?= esc($errors['avatar']) ?></small><?php endif; ?>
    </p>
    <?php endif; ?>

    <button type="submit">Save</button>
    <a href="<?= site_url('users') ?>">Cancel</a>
</form>
<?= $this->endSection() ?>