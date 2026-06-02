<?php $this->load->view('partials/header', ['title' => 'Dashboard']); ?>

<div class="page-header">
    <div>
        <h1 class="page-title">Dashboard</h1>
        <p style="color: var(--text-muted); margin-top: 4px; font-size: 0.9rem;">
            Welcome back, <strong class="primary-text"><?php echo $this->session->userdata('username'); ?></strong>. Here's what's happening.
        </p>
    </div>
</div>

<?php if ($this->session->flashdata('success')): ?>
    <div style="background: #ecfd`f5; border: 1px solid #10b981; color: #065f46; padding: 0.75rem 1rem; border-radius: 6px; margin-bottom: 1.5rem; font-size: 0.875rem;">
        <?php echo $this->session->flashdata('success'); ?>
    </div>
<?php endif; ?>
<?php if ($this->session->flashdata('error')): ?>
    <div style="background: #fef2f2; border: 1px solid #ef4444; color: #991b1b; padding: 0.75rem 1rem; border-radius: 6px; margin-bottom: 1.5rem; font-size: 0.875rem;">
        <?php echo $this->session->flashdata('error'); ?>
    </div>
<?php endif; ?>

<div class="admin-modules" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 24px; margin-top: 24px;">
    
    <a href="<?php echo base_url('admin/documents'); ?>" class="card" style="background: var(--surface); padding: 24px; border-radius: var(--radius); border: 1px solid var(--border); box-shadow: var(--shadow-sm); text-decoration: none; color: inherit; transition: all 0.2s;">
        <div style="width: 48px; height: 48px; border-radius: 12px; background: #e0f2fe; color: #0284c7; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; margin-bottom: 16px;">
            <i class='bx bx-file'></i>
        </div>
        <?php if ($perm && ($perm->can_upload || $perm->can_edit || $perm->can_delete)): ?>
            <h3 style="margin: 0 0 8px 0; font-size: 1.1rem; color: var(--text);">Document Management</h3>
            <p style="margin: 0; color: var(--text-muted); font-size: 0.875rem;">Manage documents (upload, read, disable)</p>
        <?php else: ?>
            <h3 style="margin: 0 0 8px 0; font-size: 1.1rem; color: var(--text);">View Documents</h3>
            <p style="margin: 0; color: var(--text-muted); font-size: 0.875rem;">View and read archive documents</p>
        <?php endif; ?>
    </a>

    <?php if (in_array($this->session->userdata('role'), ['administrator', 'staff'])): ?>
        <a href="<?php echo base_url('admin/users'); ?>" class="card" style="background: var(--surface); padding: 24px; border-radius: var(--radius); border: 1px solid var(--border); box-shadow: var(--shadow-sm); text-decoration: none; color: inherit; transition: all 0.2s;">
            <div style="width: 48px; height: 48px; border-radius: 12px; background: #fce7f3; color: #be185d; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; margin-bottom: 16px;">
                <i class='bx bx-group'></i>
            </div>
            <h3 style="margin: 0 0 8px 0; font-size: 1.1rem; color: var(--text);">User Management</h3>
            <p style="margin: 0; color: var(--text-muted); font-size: 0.875rem;">Manage system users (create, read, update, disable)</p>
        </a>
        
        <a href="<?php echo base_url('admin/permissions'); ?>" class="card" style="background: var(--surface); padding: 24px; border-radius: var(--radius); border: 1px solid var(--border); box-shadow: var(--shadow-sm); text-decoration: none; color: inherit; transition: all 0.2s;">
            <div style="width: 48px; height: 48px; border-radius: 12px; background: #fef9c3; color: #a16207; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; margin-bottom: 16px;">
                <i class='bx bx-key'></i>
            </div>
            <h3 style="margin: 0 0 8px 0; font-size: 1.1rem; color: var(--text);">Access Rights</h3>
            <p style="margin: 0; color: var(--text-muted); font-size: 0.875rem;">Configure granular user permissions.</p>
        </a>
        
        <a href="<?php echo base_url('admin/logs'); ?>" class="card" style="background: var(--surface); padding: 24px; border-radius: var(--radius); border: 1px solid var(--border); box-shadow: var(--shadow-sm); text-decoration: none; color: inherit; transition: all 0.2s;">
            <div style="width: 48px; height: 48px; border-radius: 12px; background: #dcfce7; color: #15803d; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; margin-bottom: 16px;">
                <i class='bx bx-history'></i>
            </div>
            <h3 style="margin: 0 0 8px 0; font-size: 1.1rem; color: var(--text);">Audit Logs</h3>
            <p style="margin: 0; color: var(--text-muted); font-size: 0.875rem;">View system activity and history.</p>
        </a>
    <?php endif; ?>

</div>

<style>
    .card:hover {
        border-color: var(--primary) !important;
        transform: translateY(-2px);
    }
</style>

<?php $this->load->view('partials/footer'); ?>
