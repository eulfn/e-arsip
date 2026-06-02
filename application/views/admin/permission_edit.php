<?php $this->load->view('partials/header', ['title' => 'Edit Permissions']); ?>

<div class="page-header">
    <h1 class="page-title">Edit User Permissions</h1>
    <div class="page-actions">
        <a href="<?php echo base_url('admin/permissions'); ?>" class="btn btn-outline">
            <i class='bx bx-arrow-back'></i> Back to Permissions
        </a>
    </div>
</div>

<div class="data-container" style="max-width: 600px; padding: 24px;">
    <p style="color: var(--text-muted); margin-top: 0; margin-bottom: 24px; font-size: 0.95rem;">Managing permissions for: <strong class="primary-text"><?php echo $user->username; ?></strong></p>

    <form action="<?php echo base_url('admin/permissions/save/' . $user->id); ?>" method="POST">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 24px;">
            <div style="display: flex; align-items: center; gap: 10px; padding: 12px; border: 1px solid var(--border); border-radius: 6px; background: #fafafa;">
                <input type="checkbox" name="can_upload" id="can_upload" <?php echo($perm && $perm->can_upload) ? 'checked' : ''; ?> style="width: 18px; height: 18px; cursor: pointer;">
                <label for="can_upload" style="margin-bottom: 0; cursor: pointer; font-weight: 500; font-size: 0.9rem;">Can Upload</label>
            </div>
            <div style="display: flex; align-items: center; gap: 10px; padding: 12px; border: 1px solid var(--border); border-radius: 6px; background: #fafafa;">
                <input type="checkbox" name="can_edit" id="can_edit" <?php echo($perm && $perm->can_edit) ? 'checked' : ''; ?> style="width: 18px; height: 18px; cursor: pointer;">
                <label for="can_edit" style="margin-bottom: 0; cursor: pointer; font-weight: 500; font-size: 0.9rem;">Can Edit</label>
            </div>
            <div style="display: flex; align-items: center; gap: 10px; padding: 12px; border: 1px solid var(--border); border-radius: 6px; background: #fafafa;">
                <input type="checkbox" name="can_delete" id="can_delete" <?php echo($perm && $perm->can_delete) ? 'checked' : ''; ?> style="width: 18px; height: 18px; cursor: pointer;">
                <label for="can_delete" style="margin-bottom: 0; cursor: pointer; font-weight: 500; font-size: 0.9rem;">Can Delete</label>
            </div>
            <div style="display: flex; align-items: center; gap: 10px; padding: 12px; border: 1px solid var(--border); border-radius: 6px; background: #fafafa;">
                <input type="checkbox" name="can_view_audit" id="can_view_audit" <?php echo($perm && $perm->can_view_audit) ? 'checked' : ''; ?> style="width: 18px; height: 18px; cursor: pointer;">
                <label for="can_view_audit" style="margin-bottom: 0; cursor: pointer; font-weight: 500; font-size: 0.9rem;">Can View Audit</label>
            </div>
        </div>
        
        <div style="display: flex; gap: 12px;">
            <button type="submit" class="btn btn-primary">Update Permissions</button>
            <a href="<?php echo base_url('admin/permissions'); ?>" class="btn btn-outline">Cancel</a>
        </div>
    </form>
</div>

<?php $this->load->view('partials/footer'); ?>
