<div class="wrap">
    <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
    <!-- Abas -->
    <?php 
        // Resgate o valor da variavél "tab" da URL
        $active_tab = isset( $_GET['tab'] ) ? $_GET['tab'] : 'main_options';
    ?>
    <h2 class="nav-tab-wrapper">
        <a 
            class="nav-tab <?php echo $active_tab == 'main_options' ? 'nav-tab-active' : '' ?>" 
            href="?page=exemplo_settings_admin&tab=main_options"
        >
            Conf. 01
        </a>
        <a 
            class="nav-tab <?php echo $active_tab == 'admin_options' ? 'nav-tab-active' : '' ?>" 
            href="?page=exemplo_settings_admin&tab=admin_options"
        >
            Conf. 02
        </a>
    </h2>
    <!-- options.php pertence ao proprio WP e facilita o envio do form -->
    <form action="options.php" method="post" style="height: 80vh">
        <?php 

            switch ( $active_tab ) {
                case 'main_options':
                    settings_fields( 'exemplo_settings_group' );
                    do_settings_sections( 'exemplo_admin_options_page_01' );
                    break;
                case 'admin_options':
                    settings_fields( 'exemplo_settings_group' );
                    do_settings_sections( 'exemplo_admin_options_page_02' );
                    break;
            }
            submit_button( 'Salvar' );
        ?>
    </form>
</div>

