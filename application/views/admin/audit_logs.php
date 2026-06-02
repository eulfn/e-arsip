<?php $this->load->view('partials/header', ['title' => 'Audit Logs']); ?>

<div class="page-header">
    <div>
        <h1 class="page-title">Audit Logs</h1>
        <p style="color: var(--text-muted); margin-top: 4px; font-size: 0.9rem;">Track all system activities and record changes.</p>
    </div>
    <div class="page-actions">
    </div>
</div>


<div class="data-container">
    <table id="auditTable" class="dataTable js-datatable">
        <thead>
            <tr>
                <th class="no-sort" style="width: 40px;"><input type="checkbox"></th>
                <th>Timestamp</th>
                <th>User</th>
                <th>Action</th>
                <th>Reference</th>
                <th>Item ID</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($logs as $log): ?>
            <tr>
                <td><input type="checkbox"></td>
                <td style="color: var(--text); font-weight: 500; font-size: 0.85rem;"><?php echo $log->created_at; ?></td>
                <td>
                    <div class="avatar-group">
                        <?php 
                            $bg_colors = ['bg-purple', 'bg-blue', 'bg-green', 'bg-red', 'bg-yellow', 'bg-pink'];
                            $color_class = $bg_colors[crc32($log->username) % count($bg_colors)];
                        ?>
                        <div class="avatar-initial <?php echo $color_class; ?> shadow-sm">
                            <?php echo strtoupper(substr($log->username, 0, 2)); ?>
                        </div>
                        <span class="primary-text"><?php echo $log->username; ?></span>
                    </div>
                </td>
                <td><?php echo ucfirst($log->action); ?></td>
                <td>
                    <span style="background: #f3f4f6; color: #4b5563; padding: 2px 8px; border-radius: 4px; font-size: 0.75rem; font-weight: 600;">
                        <?php echo strtoupper($log->module); ?>
                    </span>
                </td>
                <td style="font-family: monospace; font-size: 0.85rem;"><?php echo $log->documents_id ? '#' . $log->documents_id : '-'; ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        $('#auditTable').DataTable({
            order: [[1, 'desc']]
        });
    });
</script>

<?php $this->load->view('partials/footer'); ?>
