<!DOCTYPE html>
<html>
<head>
    <title>Register - E-Archive</title>
    <link rel="stylesheet" href="<?php echo base_url('assets/css/base.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/auth/auth.css'); ?>">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    <div class="auth-container">
        <div class="auth-card">
            <h1 class="text-center mb-4">Register</h1>
            <?php if ($this->session->flashdata('success')): ?>
                <div class="alert alert-success">
                    <?php echo $this->session->flashdata('success'); ?>
                </div>
            <?php endif; ?>
            <?php if ($this->session->flashdata('error')): ?>
                <div class="alert alert-error">
                    <?php echo $this->session->flashdata('error'); ?>
                </div>
            <?php endif; ?>
            <form action="<?php echo base_url('auth/register'); ?>" method="POST" enctype="multipart/form-data" novalidate>
                <div class="flex-center mb-4">
                    <label for="profile_pic_input" class="profile-avatar-clickable" style="width: 100px; height: 100px;">
                        <div id="avatar-preview-container" class="user-avatar-preview">
                            <div class="avatar-initial bg-blue" style="width: 100%; height: 100%; font-size: 2rem;">
                                <i class='bx bx-user'></i>
                            </div>
                        </div>
                        <input type="file" name="profile_pic" id="profile_pic_input" style="display: none;" accept="image/*">
                    </label>
                </div>
                <div class="form-group">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" value="<?php echo set_value('username'); ?>" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="<?php echo set_value('email'); ?>" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary w-full mt-2">Register</button>
            </form>
            <p class="text-center mt-4 text-muted" style="font-size: 0.875rem;">
                Already have an account? <a href="<?php echo base_url('auth/login'); ?>" style="color: var(--primary); text-decoration: none; font-weight: 500;">Login</a>
            </p>
        </div>
    </div>
    <script src="<?php echo base_url('assets/js/modules/avatar-preview.js?v=' . time()); ?>"></script>
    <script>
        initAvatarPreview('profile_pic_input', 'avatar-preview', 'avatar-preview-container');
    </script>
</body>
</html>
