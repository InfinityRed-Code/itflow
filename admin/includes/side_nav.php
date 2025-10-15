<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-<?php echo nullable_htmlentities($config_theme); ?> d-print-none">
    <a class="brand-link pb-1 mt-1" href="/agent/<?php echo $config_start_page ?>">
        <p class="h6">
            <i class="nav-icon fas fa-arrow-left ml-3 mr-2"></i>
            <span class="brand-text">
                <?php _e('Back'); ?> | <strong><?php _e('Administration'); ?></strong>
            </span>
        </p>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav>
            <ul class="nav nav-pills nav-sidebar flex-column mt-2" data-widget="treeview" data-accordion="false">
                <!-- ACCESS Section -->
                <li class="nav-item">
                    <a href="users.php" class="nav-link <?php if (basename($_SERVER["PHP_SELF"]) == "users.php") {echo "active";} ?>">
                        <i class="nav-icon fas fa-users"></i>
                        <p><?php _e('Users'); ?></p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="roles.php" class="nav-link <?php if (basename($_SERVER["PHP_SELF"]) == "roles.php") {echo "active";} ?>">
                        <i class="nav-icon fas fa-user-shield"></i>
                        <p><?php _e('Roles'); ?></p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="api_keys.php" class="nav-link <?php if (basename($_SERVER["PHP_SELF"]) == "api_keys.php") {echo "active";} ?>">
                        <i class="nav-icon fas fa-key"></i>
                        <p><?php _e('API Keys'); ?></p>
                    </a>
                </li>
                <li class="nav-header"><?php _e('TAGS & CATEGORIES'); ?></li>

                <li class="nav-item">
                    <a href="tag.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'tag.php' ? 'active' : ''); ?>">
                        <i class="nav-icon fas fa-tags"></i>
                        <p><?php _e('Tags'); ?></p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="category.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'category.php' ? 'active' : ''); ?>">
                        <i class="nav-icon fas fa-list-ul"></i>
                        <p><?php _e('Categories'); ?></p>
                    </a>
                </li>
                <?php if ($config_module_enable_accounting) { ?>
                    <li class="nav-item">
                        <a href="tax.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'tax.php' ? 'active' : ''); ?>">
                            <i class="nav-icon fas fa-balance-scale"></i>
                            <p><?php _e('Taxes'); ?></p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="payment_method.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'payment_method.php' ? 'active' : ''); ?>">
                            <i class="nav-icon fas fa-hand-holding-usd"></i>
                            <p><?php _e('Payment Methods'); ?></p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="payment_provider.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'payment_provider.php' ? 'active' : ''); ?>">
                            <i class="nav-icon far fa-credit-card"></i>
                            <p><?php _e('Payment Providers'); ?></p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="saved_payment_method.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'saved_payment_method.php' ? 'active' : ''); ?>">
                            <i class="nav-icon far fa-credit-card"></i>
                            <p><?php _e('Saved Payments'); ?></p>
                        </a>
                    </li>
                <?php } ?>
                    <li class="nav-item">
                        <a href="ai_provider.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'ai_provider.php' ? 'active' : ''); ?>">
                            <i class="nav-icon fas fa-robot"></i>
                            <p><?php _e('AI Providers'); ?></p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="ai_model.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'ai_model.php' ? 'active' : ''); ?>">
                            <i class="nav-icon fas fa-robot"></i>
                            <p><?php _e('AI Models'); ?></p>
                        </a>
                    </li>
                
                <?php if ($config_module_enable_ticketing) { ?>
                    <li class="nav-item">
                        <a href="ticket_status.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'ticket_status.php' ? 'active' : ''); ?>">
                            <i class="nav-icon fas fa-info-circle"></i>
                            <p><?php _e('Ticket Statuses'); ?></p>
                        </a>
                    </li>
                <?php } ?>
                <li class="nav-item">
                    <a href="custom_link.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'custom_link.php' ? 'active' : ''); ?>">
                        <i class="nav-icon fas fa-external-link-alt"></i>
                        <p><?php _e('Custom Links'); ?></p>
                    </a>
                </li>

                <?php if ($config_module_enable_itdoc) { ?>
                    <li class="nav-header"><?php _e('TEMPLATES'); ?></li>

                    <li class="nav-item">
                        <a href="project_template.php" class="nav-link <?php echo (in_array(basename($_SERVER['PHP_SELF']), ['project_template.php', 'project_template_details.php']) ? 'active' : ''); ?>">
                            <i class="nav-icon fas fa-project-diagram"></i>
                            <p><?php _e('Project Templates'); ?></p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="ticket_template.php" class="nav-link <?php echo (in_array(basename($_SERVER['PHP_SELF']), ['ticket_template.php', 'ticket_template_details.php']) ? 'active' : ''); ?>">
                            <i class="nav-icon fas fa-life-ring"></i>
                            <p><?php _e('Ticket Templates'); ?></p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="vendor_template.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'vendor_template.php' ? 'active' : ''); ?>">
                            <i class="nav-icon fas fa-building"></i>
                            <p><?php _e('Vendor Templates'); ?></p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="software_template.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'software_template.php' ? 'active' : ''); ?>">
                            <i class="nav-icon fas fa-rocket"></i>
                            <p><?php _e('License Templates'); ?></p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="document_template.php" class="nav-link <?php echo (in_array(basename($_SERVER['PHP_SELF']), ['document_template.php', 'document_template_details.php']) ? 'active' : ''); ?>">
                            <i class="nav-icon fas fa-file"></i>
                            <p><?php _e('Document Templates'); ?></p>
                        </a>
                    </li>
                <?php } ?>

                <li class="nav-header"><?php _e('MAINTENANCE'); ?></li>

                <li class="nav-item">
                    <a href="mail_queue.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'mail_queue.php' ? 'active' : ''); ?>">
                        <i class="nav-icon fas fa-mail-bulk"></i>
                        <p><?php _e('Mail Queue'); ?></p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="audit_log.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'audit_log.php' ? 'active' : ''); ?>">
                        <i class="nav-icon fas fa-history"></i>
                        <p><?php _e('Audit Logs'); ?></p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="app_log.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'app_log.php' ? 'active' : ''); ?>">
                        <i class="nav-icon fas fa-history"></i>
                        <p><?php _e('App Logs'); ?></p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="backup.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'backup.php' ? 'active' : ''); ?>">
                        <i class="nav-icon fas fa-cloud-upload-alt"></i>
                        <p><?php _e('Backup'); ?></p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="debug.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'debug.php' ? 'active' : ''); ?>">
                        <i class="nav-icon fas fa-bug"></i>
                        <p><?php _e('Debug'); ?></p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="update.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'update.php' ? 'active' : ''); ?>">
                        <i class="nav-icon fas fa-download"></i>
                        <p><?php _e('Update'); ?></p>
                    </a>
                </li>

                <!-- SETTINGS Section -->
                <li class="nav-item has-treeview mt-2 <?php echo (in_array(basename($_SERVER['PHP_SELF']), ['settings_company.php', 'settings_localization.php', 'settings_theme.php', 'settings_security.php', 'settings_mail.php', 'settings_notification.php', 'settings_default.php', 'settings_invoice.php', 'settings_quote.php', 'settings_online_payment.php', 'settings_online_payment_clients.php', 'settings_project.php', 'settings_ticket.php', 'settings_ai.php', 'identity_provider.php', 'settings_telemetry.php', 'settings_module.php']) ? 'menu-open' : ''); ?>">
                    <a href="#" class="nav-link">
                        <p>
                            <?php _e('SETTINGS'); ?>
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="settings_company.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'settings_company.php' ? 'active' : ''); ?>">
                                <i class="nav-icon fa fa-briefcase"></i>
                                <p><?php _e('Company Details'); ?></p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="settings_localization.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'settings_localization.php' ? 'active' : ''); ?>">
                                <i class="nav-icon fa fa-globe"></i>
                                <p><?php _e('Localization'); ?></p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="settings_theme.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'settings_theme.php' ? 'active' : ''); ?>">
                                <i class="nav-icon fa fa-paint-brush"></i>
                                <p><?php _e('Theme'); ?></p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="settings_security.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'settings_security.php' ? 'active' : ''); ?>">
                                <i class="nav-icon fas fa-shield-alt"></i>
                                <p><?php _e('Security'); ?></p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="settings_mail.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'settings_mail.php' ? 'active' : ''); ?>">
                                <i class="nav-icon far fa-envelope"></i>
                                <p><?php _e('Mail'); ?></p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="settings_notification.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'settings_notification.php' ? 'active' : ''); ?>">
                                <i class="nav-icon far fa-bell"></i>
                                <p><?php _e('Notofications'); ?></p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="settings_default.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'settings_default.php' ? 'active' : ''); ?>">
                                <i class="nav-icon fas fa-cogs"></i>
                                <p><?php _e('Defaults'); ?></p>
                            </a>
                        </li>
                        <?php if ($config_module_enable_accounting) { ?>
                            <li class="nav-item">
                                <a href="settings_invoice.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'settings_invoice.php' ? 'active' : ''); ?>">
                                    <i class="nav-icon fas fa-file-invoice"></i>
                                    <p><?php _e('Incoice'); ?></p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="settings_quote.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'settings_quote.php' ? 'active' : ''); ?>">
                                    <i class="nav-icon fas fa-comment-dollar"></i>
                                    <p><?php _e('Quote'); ?></p>
                                </a>
                            </li>
                        <?php } ?>
                        <?php if ($config_module_enable_ticketing) { ?>
                            <li class="nav-item">
                                <a href="settings_project.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'settings_project.php' ? 'active' : ''); ?>">
                                    <i class="nav-icon fas fa-project-diagram"></i>
                                    <p><?php _e('Project'); ?></p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="settings_ticket.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'settings_ticket.php' ? 'active' : ''); ?>">
                                    <i class="nav-icon fas fa-life-ring"></i>
                                    <p><?php _e('Ticket'); ?></p>
                                </a>
                            </li>
                        <?php } ?>
                        <!-- Currently the only integration is the client portal SSO -->
                        <?php if ($config_client_portal_enable) { ?>
                            <li class="nav-item">
                                <a href="identity_provider.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'identity_provider.php' ? 'active' : ''); ?>">
                                    <i class="nav-icon fas fa-fingerprint"></i>
                                    <p><?php _e('Identity Provider'); ?></p>
                                </a>
                            </li>
                        <?php } ?>
                        <li class="nav-item">
                            <a href="settings_telemetry.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'settings_telemetry.php' ? 'active' : ''); ?>">
                                <i class="nav-icon fas fa-satellite-dish"></i>
                                <p><?php _e('Telemetry'); ?></p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="settings_module.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'settings_module.php' ? 'active' : ''); ?>">
                                <i class="nav-icon fas fa-cube"></i>
                                <p><?php _e('Modules'); ?></p>
                            </a>
                        </li>
                    </ul>
                </li>

                <?php
                $sql_custom_links = mysqli_query($mysqli, "SELECT * FROM custom_links 
                    WHERE custom_link_location = 4 AND custom_link_archived_at IS NULL
                    ORDER BY custom_link_order ASC, custom_link_name ASC"
                );

                while ($row = mysqli_fetch_array($sql_custom_links)) {
                    $custom_link_name = nullable_htmlentities($row['custom_link_name']);
                    $custom_link_uri = sanitize_url($row['custom_link_uri']);
                    $custom_link_icon = nullable_htmlentities($row['custom_link_icon']);
                    $custom_link_new_tab = intval($row['custom_link_new_tab']);
                    if ($custom_link_new_tab == 1) {
                        $target = "target='_blank' rel='noopener noreferrer'";
                    } else {
                        $target = "";
                    }

                    ?>

                <li class="nav-item">
                    <a href="<?php echo $custom_link_uri; ?>" <?php echo $target; ?> class="nav-link <?php if (basename($_SERVER["PHP_SELF"]) == basename($custom_link_uri)) { echo "active"; } ?>">
                        <i class="fas fa-<?php echo $custom_link_icon; ?> nav-icon"></i>
                        <p><?php echo $custom_link_name; ?></p>
                        <i class="fas fa-angle-right nav-icon float-right"></i>
                    </a>
                </li>

                <?php } ?>

            </ul>
        </nav>
        <!-- /.sidebar-menu -->
        <div class="mb-3"></div>
    </div>
    <!-- /.sidebar -->
</aside>
