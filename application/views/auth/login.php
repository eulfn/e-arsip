<!DOCTYPE html>
<html>
<head>
    <title>Login - E-Archive</title>
    <link rel="stylesheet" href="<?php echo base_url('assets/css/base.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/auth/auth.css'); ?>">
</head>
<body>
    <div class="auth-container">
        <div class="auth-card">
            <h1 class="text-center mb-4">Login</h1>
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
            <form action="<?php echo base_url('auth/login'); ?>" method="POST" novalidate>
                <div class="form-group">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary w-full mt-2">Login</button>
            </form>
            <p class="text-center mt-4 text-muted" style="font-size: 0.875rem;">
                Don't have an account? <a href="<?php echo base_url('auth/register'); ?>" style="color: var(--primary); text-decoration: none; font-weight: 500;">Register</a>
            </p>
        </div>
    </div>
</body>
</html>
