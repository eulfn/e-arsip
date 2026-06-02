<?php $this->load->view('partials/header', ['title' => 'Users']); ?>

<div class="page-header">
    <h1 class="page-title">Users</h1>
    <div class="page-actions">
        <a href="<?php echo base_url('admin/users/create'); ?>" class="btn btn-primary">
            <i class='bx bx-plus'></i> Add User
        </a>
    </div>
</div>

<?php if ($this->session->flashdata('success')): ?>
    <div style="background: #ecfdf5; border: 1px solid #10b981; color: #065f46; padding: 0.75rem 1rem; border-radius: 6px; margin-bottom: 1.5rem; font-size: 0.875rem;">
        <?php echo $this->session->flashdata('success'); ?>
    </div>
<?php endif; ?>
<?php if ($this->session->flashdata('error')): ?>
    <div style="background: #fef2f2; border: 1px solid #ef4444; color: #991b1b; padding: 0.75rem 1rem; border-radius: 6px; margin-bottom: 1.5rem; font-size: 0.875rem;">
        <?php echo $this->session->flashdata('error'); ?>
    </div>
<?php endif; ?>


<div class="data-container">
    <table id="userTable" class="dataTable js-datatable">
        <thead>
            <tr>
                <th class="no-sort" style="width: 40px;"><input type="checkbox"></th>
                <th>User</th>
                <th>Email</th>
                <th>Role</th>
                <th>Status</th>
                <th class="no-sort" style="width: 80px;"></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
            <tr>
                <td><input type="checkbox"></td>
                <td>
                    <div class="avatar-group">
                        <?php if ($user->profile_pic): ?>
                            <div class="avatar-image">
                                <img src="<?php echo base_url('uploads/profile/' . $user->profile_pic); ?>" style="width: 32px; height: 32px; border-radius: 8px; object-fit: cover;">
                            </div>
                        <?php else: ?>
                            <?php 
                                $bg_colors = ['bg-purple', 'bg-blue', 'bg-green', 'bg-red', 'bg-yellow', 'bg-pink'];
                                $color_class = $bg_colors[crc32($user->username) % count($bg_colors)];
                            ?>
                            <div class="avatar-initial <?php echo $color_class; ?>">
                                <?php echo strtoupper(substr($user->username, 0, 2)); ?>
                            </div>
                        <?php endif; ?>
                        <span class="primary-text"><?php echo $user->username; ?></span>
                    </div>
                </td>
                <td><?php echo $user->email; ?></td>
                <td><?php echo ucfirst($user->role); ?></td>
                <td>
                    <span class="status-<?php echo $user->status; ?>">
                        <?php echo ucfirst($user->status); ?>
                    </span>
                </td>
                <td>
                    <div style="display: flex; gap: 8px;">
                        <?php if ($user->id != $this->session->userdata('id')): ?>
                            <a href="<?php echo base_url('admin/users/edit/' . $user->id); ?>" class="btn-icon" style="color: var(--text-muted);"><i class='bx bx-edit-alt'></i></a>
                            <a href="<?php echo base_url('admin/users/toggle/' . $user->id); ?>" class="btn-icon" style="color: var(--text-muted);" onclick="return confirm('Are you sure you want to <?php echo ($user->status == 'active') ? 'disable' : 'enable'; ?> this user?');">
                                <i class='bx <?php echo ($user->status == 'active') ? 'bx-lock-alt' : 'bx-lock-open-alt'; ?>'></i>
                            </a>
                        <?php endif; ?>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>


<?php $this->load->view('partials/footer'); ?>
