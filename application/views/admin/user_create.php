<?php $this->load->view('partials/header', ['title' => 'Add User']); ?>

<div class="page-header">
    <h1 class="page-title">Add New User</h1>
    <div class="page-actions">
        <a href="<?php echo base_url('admin/users'); ?>" class="btn btn-outline">
            <i class='bx bx-arrow-back'></i> Back to Users
        </a>
    </div>
</div>

<div class="data-container container-md p-4">
    <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-error">
            <?php echo $this->session->flashdata('error'); ?>
        </div>
    <?php endif; ?>

    <form action="<?php echo base_url('admin/users/save'); ?>" method="POST" enctype="multipart/form-data" novalidate>
        <div class="user-form-container">
            <label for="profile_pic_input" class="profile-avatar-clickable" style="width: 100px; height: 100px;">
                <div id="avatar-preview-container" class="user-avatar-preview">
                    <div class="avatar-initial bg-blue" style="width: 100%; height: 100%; font-size: 2rem;">
                        <i class='bx bx-user'></i>
                    </div>
                </div>
                <input type="file" name="profile_pic" id="profile_pic_input" style="display: none;">
            </label>
            <div class="user-info-section">
                <h4 class="user-info-title">User Avatar</h4>
                <p class="user-info-subtitle">Click the circle to upload a profile picture.</p>
            </div>
        </div>
        <div class="form-group">
            <label class="form-label">Username</label>
            <input type="text" name="username" class="form-control" required>
        </div>
        <div class="form-group">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <div class="form-group">
            <label class="form-label">Role</label>
            <select name="role" class="form-control">
                <option value="magang">Magang</option>
                <option value="staff">Staff</option>
                <option value="administrator">Administrator</option>
            </select>
        </div>
        <div class="form-group">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        
        <div class="mt-4 flex-gap-2">
            <button type="submit" class="btn btn-primary">Create User</button>
            <a href="<?php echo base_url('admin/users'); ?>" class="btn btn-outline">Cancel</a>
        </div>
    </form>
</div>
    </form>
</div>

<?php $this->load->view('partials/footer'); ?>
<script src="<?php echo base_url('assets/js/modules/avatar-preview.js?v=' . time()); ?>"></script>
<script>
    initAvatarPreview('profile_pic_input', 'avatar-preview', 'avatar-preview-container');
</script>
