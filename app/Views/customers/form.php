<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<h1><?= esc($title) ?></h1>

<form action="<?= esc($action) ?>" method="post">
    <?= csrf_field() ?>

    <p>
        <label>Full Name<br>
        <input type="text" name="full_name" value="<?= esc($values['full_name'] ?? '') ?>"></label>
        <?php if (isset($errors['full_name'])): ?><br><small class="error"><?= esc($errors['full_name']) ?></small><?php endif; ?>
    </p>
    <p>
        <label>Email<br>
        <input type="text" name="email" value="<?= esc($values['email'] ?? '') ?>"></label>
        <?php if (isset($errors['email'])): ?><br><small class="error"><?= esc($errors['email']) ?></small><?php endif; ?>
    </p>
    <p>
        <label>Phone<br>
        <input type="text" name="phone" value="<?= esc($values['phone'] ?? '') ?>"></label>
        <?php if (isset($errors['phone'])): ?><br><small class="error"><?= esc($errors['phone']) ?></small><?php endif; ?>
    </p>

    <button type="submit">Save</button>
    <a href="<?= site_url('customers') ?>">Cancel</a>
</form>
<?= $this->endSection() ?>