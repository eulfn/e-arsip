<?php $this->load->view('partials/header', ['title' => 'My Profile']); ?>

<div class="page-header">
    <h1 class="page-title">My Profile</h1>
    <div class="page-actions">
        <a href="<?php echo base_url('admin'); ?>" class="btn btn-outline">
            <i class='bx bx-grid-alt'></i> Dashboard
        </a>
    </div>
</div>

<div class="data-container container-md p-4">
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

    <form action="<?php echo base_url('admin/profile/update'); ?>" method="POST" enctype="multipart/form-data">
        <div class="profile-form-container">
            <div class="profile-avatar-section">
                <label for="profile_pic_input" class="profile-avatar-clickable">
                    <div id="avatar-preview-container" class="avatar-preview-container">
                        <?php if ($user->profile_pic): ?>
                            <img id="avatar-preview" src="<?php echo base_url('uploads/profile/' . $user->profile_pic); ?>" class="avatar-image-preview">
                        <?php else: ?>
                            <div id="avatar-initials" class="avatar-initial bg-blue" style="width: 100%; height: 100%; font-size: 2.5rem;">
                                <?php echo strtoupper(substr($user->username, 0, 2)); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <input type="file" name="profile_pic" id="profile_pic_input" style="display: none;" accept="image/*">
                </label>
            </div>

            <div style="flex: 1;">
                <div class="form-group">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" value="<?php echo $user->username; ?>" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" class="form-control" value="<?php echo $user->email; ?>" required>
                </div>
                <div class="form-group">
                    <label class="form-label">New Password <span class="text-muted" style="font-weight: normal;">(Leave blank to keep current)</span></label>
                    <input type="password" name="password" class="form-control">
                </div>
                <div class="form-group">
                    <label class="form-label">Role</label>
                    <div class="role-badge-container">
                        <span class="status-<?php echo $user->role; ?>">
                            <i class='bx <?php echo ($user->role == 'administrator') ? 'bx-shield-quarter' : (($user->role == 'staff') ? 'bx-briefcase' : 'bx-user'); ?>' style="vertical-align: middle; margin-right: 4px;"></i>
                            <?php echo ucfirst($user->role); ?>
                        </span>
                        <?php if ($user->role == 'administrator'): ?>
                            <span class="admin-verified-badge">
                                <i class='bx bxs-check-circle'></i> Full System Access
                            </span>
                        <?php endif; ?>
                    </div>
                    <?php if ($user->role !== 'administrator'): ?>
                        <small class="text-muted" style="display: block; margin-top: 8px;">Role can only be changed by an administrator.</small>
                    <?php else: ?>
                        <small class="text-muted" style="display: block; margin-top: 8px;">Your administrative role provides full access to all system features.</small>
                    <?php endif; ?>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </div>
        </div>
    </form>
</div>

<?php $this->load->view('partials/footer'); ?>
<script src="<?php echo base_url('assets/js/modules/avatar-preview.js?v=' . time()); ?>"></script>
<script>
    initAvatarPreview('profile_pic_input', 'avatar-preview', 'avatar-preview-container');
</script>
