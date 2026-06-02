<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? $title : 'E-Archive'; ?></title>
    <link rel="stylesheet" href="<?php echo base_url('assets/css/base.css?v=' . time()); ?>">
    
    <?php 
        $segment1 = $this->uri->segment(1);
        $segment2 = $this->uri->segment(2);
        
        // Load Auth CSS
        if ($segment1 == 'auth' || empty($segment1)) {
            echo '<link rel="stylesheet" href="' . base_url('assets/css/auth/auth.css?v=' . time()) . '">';
        }
        
        // Load Admin CSS
        if ($segment1 == 'admin') {
            echo '<link rel="stylesheet" href="' . base_url('assets/css/admin/admin.css?v=' . time()) . '">';
            
            // Specific Admin Modules
            if ($segment2 == 'profile') {
                echo '<link rel="stylesheet" href="' . base_url('assets/css/admin/profile.css?v=' . time()) . '">';
            }
            if ($segment2 == 'users') {
                echo '<link rel="stylesheet" href="' . base_url('assets/css/admin/users.css?v=' . time()) . '">';
            }
        }
    ?>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <!-- Boxicons for Modern Icons -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    <div class="app-layout">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <div class="sidebar-logo">
                    <i class='bx bx-archive'></i>
                </div>
                <div class="sidebar-title">E-Archive</div>
            </div>
            
            <ul class="sidebar-menu">
                <div class="sidebar-category">Main Menu</div>
                <li>
                    <a href="<?php echo base_url('admin'); ?>" class="sidebar-item <?php echo ($this->uri->segment(1) == 'admin' && $this->uri->segment(2) == '') ? 'active' : ''; ?>">
                        <i class='bx bx-grid-alt'></i> Dashboard
                    </a>
                </li>
                <li>
                    <a href="<?php echo base_url('admin/profile'); ?>" class="sidebar-item <?php echo ($this->uri->segment(2) == 'profile') ? 'active' : ''; ?>">
                        <i class='bx bx-user-circle'></i> My Profile
                    </a>
                </li>
                <li>
                    <a href="<?php echo base_url('admin/documents'); ?>" class="sidebar-item <?php echo ($this->uri->segment(2) == 'documents') ? 'active' : ''; ?>">
                        <i class='bx bx-file-blank'></i> Documents
                    </a>
                </li>
                
                <?php if(in_array($this->session->userdata('role'), ['administrator', 'staff'])): ?>
                    <div class="sidebar-category">Administration</div>
                    <li>
                        <a href="<?php echo base_url('admin/categories'); ?>" class="sidebar-item <?php echo ($this->uri->segment(2) == 'categories') ? 'active' : ''; ?>">
                            <i class='bx bx-category'></i> Categories
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo base_url('admin/users'); ?>" class="sidebar-item <?php echo ($this->uri->segment(2) == 'users') ? 'active' : ''; ?>">
                            <i class='bx bx-group'></i> Users
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo base_url('admin/permissions'); ?>" class="sidebar-item <?php echo ($this->uri->segment(2) == 'permissions') ? 'active' : ''; ?>">
                            <i class='bx bx-key'></i> Permissions
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo base_url('admin/logs'); ?>" class="sidebar-item <?php echo ($this->uri->segment(2) == 'logs') ? 'active' : ''; ?>">
                            <i class='bx bx-history'></i> Audit Logs
                        </a>
                    </li>
                <?php endif; ?>
            </ul>

            <div class="sidebar-footer">
                <a href="<?php echo base_url('admin/profile'); ?>" class="sidebar-item <?php echo ($this->uri->segment(2) == 'profile') ? 'active' : ''; ?>">
                    <i class='bx bx-user'></i> My Profile
                </a>
                <a href="<?php echo base_url('auth/logout'); ?>" class="sidebar-item" style="color: #ef4444;">
                    <i class='bx bx-log-out'></i> Logout
                </a>
            </div>
        </aside>

        <!-- Main Wrapper -->
        <main class="main-wrapper">
            <!-- Topbar -->
            <header class="topbar">
                <div class="breadcrumbs">
                    <i class='bx bx-home-alt'></i>
                    <span>/</span>
                    <span class="current"><?php echo isset($title) ? $title : ucfirst($this->uri->segment(2) ?? 'Dashboard'); ?></span>
                </div>
                
                <div class="topbar-actions">
                    <a href="<?php echo base_url('admin/profile'); ?>" class="user-profile" style="text-decoration: none; color: inherit;">
                        <div class="avatar-container" style="width: 32px; height: 32px; border-radius: 50%; overflow: hidden; background: #f3f4f6; display: flex; align-items: center; justify-content: center;">
                            <?php if ($this->session->userdata('profile_pic')): ?>
                                <img src="<?php echo base_url('uploads/profile/' . $this->session->userdata('profile_pic')); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                            <?php else: ?>
                                <div class="avatar-initial bg-blue" style="width: 100%; height: 100%; font-size: 0.85rem; display: flex; align-items: center; justify-content: center;">
                                    <?php echo strtoupper(substr($this->session->userdata('username'), 0, 2)); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <span class="user-name"><?php echo $this->session->userdata('username'); ?></span>
                    </a>
                </div>
            </header>

            <!-- Content Area starts -->
            <div class="content-area">
