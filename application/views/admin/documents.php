<?php $this->load->view('partials/header', ['title' => 'Documents']); ?>

<div class="page-header">
    <h1 class="page-title">Documents</h1>
    <div class="page-actions">
        <?php if ($perm && $perm->can_upload): ?>
            <a href="<?php echo base_url('admin/documents/upload'); ?>" class="btn btn-primary">
                <i class='bx bx-upload'></i> Upload Document
            </a>
        <?php endif; ?>
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
    <table id="docTable" class="dataTable js-datatable">
        <thead>
            <tr>
                <th class="no-sort" style="width: 40px;"><input type="checkbox"></th>
                <th>Title</th>
                <th>Category</th>
                <th>Uploaded By</th>
                <th>Status</th>
                <th class="no-sort" style="width: 80px;"></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($documents as $doc): ?>
            <tr>
                <td><input type="checkbox"></td>
                <td>
                    <div class="avatar-group">
                        <?php 
                            $ext = pathinfo($doc->filename, PATHINFO_EXTENSION);
                            $icon = 'bx-file-blank';
                            if ($ext == 'pdf') $icon = 'bxs-file-pdf';
                            else if ($ext == 'zip') $icon = 'bxs-file-archive';
                            else if (in_array($ext, ['doc', 'docx'])) $icon = 'bxs-file-doc';
                            
                            $bg_colors = ['bg-purple', 'bg-blue', 'bg-green', 'bg-red', 'bg-yellow', 'bg-pink'];
                            $color_class = $bg_colors[crc32($doc->title) % count($bg_colors)];
                        ?>
                        <div class="avatar-initial <?php echo $color_class; ?>">
                            <i class='bx <?php echo $icon; ?>'></i>
                        </div>
                        <a href="<?php echo base_url('admin/documents/download/' . $doc->id); ?>" target="_blank" class="primary-text" style="text-decoration: none;">
                            <?php echo $doc->title; ?>
                        </a>
                    </div>
                </td>
                <td><?php echo $doc->category_name; ?></td>
                <td><?php echo $doc->username; ?></td>
                <td>
                    <span class="status-<?php echo $doc->status; ?>">
                        <?php echo ucfirst($doc->status); ?>
                    </span>
                </td>
                <td>
                    <div style="display: flex; gap: 8px;">
                        <a href="<?php echo base_url('admin/documents/force_download/' . $doc->id); ?>" class="btn-icon" title="Download" style="color: var(--text-muted);">
                            <i class='bx bx-download'></i>
                        </a>
                        <?php if ($perm && $perm->can_edit): ?>
                            <a href="<?php echo base_url('admin/documents/edit/' . $doc->id); ?>" class="btn-icon" style="color: var(--text-muted);"><i class='bx bx-edit-alt'></i></a>
                            <a href="<?php echo base_url('admin/documents/toggle/' . $doc->id); ?>" class="btn-icon" style="color: var(--text-muted);" onclick="return confirm('Are you sure you want to <?php echo ($doc->status == 'active') ? 'disable' : 'enable'; ?> this document?');">
                                <i class='bx <?php echo ($doc->status == 'active') ? 'bx-block' : 'bx-check-circle'; ?>'></i>
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
