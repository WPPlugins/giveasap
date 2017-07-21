<?php

if( ! defined( 'ABSPATH' ) ) {
    return;
}

/**
 * Add Metabox to display user information
 * @return void
 */
function giveasap_add_metabox_for_users() {
    add_meta_box( 'giveasap_users', __( 'Users', 'giveasap' ), 'giveasap_metabox_users', array( 'giveasap' ), 'side', 'high' );
    add_meta_box( 'giveasap_ideas', __( 'Have an Idea?', 'giveasap' ), 'giveasap_metabox_ideas', array( 'giveasap' ), 'side', 'low' );
}
add_action( 'add_meta_boxes', 'giveasap_add_metabox_for_users' );

/**
 * Rendering users and user actions in metabox
 * @param  object $post Post object
 * @return void       
 */
function giveasap_metabox_users( $post ){
    $post_id = $post->ID;
    $registered_users = giveasap_get_entries_for( $post_id );
    $count_users = 0;
    if( $registered_users && is_array( $registered_users ) ){
        $count_users = count( $registered_users );
    }
    ?>
    <p>
        <?php _e( 'Registered users:', 'giveasap' ); ?> <strong><?php echo $count_users; ?></strong>
        <?php if( $count_users > 0 ) { ?>
            <button type="button" name="giveasap_users" id="giveasap_expand_users" class="button  button-small right"><?php _e( 'Expand Users', 'giveasap' ); ?></button>
        <?php } ?>
    </p>
    <?php if( $count_users > 0 ) { ?>
        <div id="giveasap_users_container" class="giveasap_users hidden">
             <table class="wp-list-table widefat fixed striped">
                <tbody>
                <?php
                    foreach ( $registered_users as $user ) {
                        if( $user->email == '' ) {
                            continue;
                        }
                        echo '<tr><td>' . $user->email . '</td></tr>';
                    }
                ?>
                </tbody>
            </table>
            <br/>
        </div>
    <?php 
    }
    
    $winners = giveasap_get_winners( $post_id );
    $all_winners_notified = true;
    if( $winners ) {
        ?>
        <div class="giveasap_winners">
            <strong><?php _e( 'Winners', 'giveasap' ); ?></strong>
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th><?php _e( 'Email', 'giveasap' ); ?></th>
                        <th style="width: 60px;"><?php _e( 'Notified', 'giveasap' ); ?></th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    foreach ( $winners as $email => $emailed ) {
                        if( $emailed == 'no' ) {
                            $all_winners_notified = false;
                        }
                        echo '<tr><td>' . $email . '</td><td>' . strtoupper( $emailed ) . '</td></tr>';
                    }
                ?>
                </tbody>
            </table>
        </div>
        <br/>
        <?php
    } 

    if( 'giveasap_ended' == $post->post_status && $count_users > 0 ) {
        ?>
        <button type="submit" name="giveasap_select" value="1" id="notify" class="button button-primary button-large"><?php _e( 'Select Winner(s)', 'giveasap' ); ?></button>
        <?php
    }

    if( 'giveasap_winners' == $post->post_status && $count_users > 0 ) {
        ?>
       
        <button type="submit" name="giveasap_notify" id="notify" class="button button-primary button-large"><?php _e( 'Notify Winner(s)', 'giveasap' ); ?></button>
        <?php
    }

    if( 'giveasap_notified' == $post->post_status && ! $all_winners_notified ) {
        ?>
        <button type="submit" name="giveasap_notify" id="notify" class="button button-primary button-large"><?php _e( 'Notify Winner(s)', 'giveasap' ); ?></button>
        <?php
    }
    ?>

    <?php if( $count_users > 0 ) { ?>
        <button type="submit" name="giveasap_export" id="export" class="button  button-large"><?php _e( 'Export CSV', 'giveasap' ); ?></button>
    <?php 
    }   
}

add_action( 'admin_init', 'giveasap_metabox_users_select_winner', 99 );

/**
 * Select winners
 * @return void 
 */
function giveasap_metabox_users_select_winner(){
    
    if( isset( $_POST['giveasap_select'] ) ) {
 
        $post_id = $_POST['post_ID'];
        if( $post_id == 0 ) {
            return false;
        }
        if( isset( $_GET['action'] ) ) {
            unset( $_GET['action'] );
        }
        if( isset( $_GET['message'] ) ) {
            unset( $_GET['message'] );
        }
        
        $selected = giveasap_select_winner( $post_id );

        if( $selected ) {

            $set_status = giveasap_set_status( $post_id, 'giveasap', 'giveasap_winners', 'giveasap_ended' );
             
            wp_redirect( admin_url( 'post.php?post=' . $post_id .'&action=edit' ), '301' );
            exit();
        }
        
    }
}

add_action( 'admin_init', 'giveasap_metabox_users_notify_winners', 99 );
function giveasap_metabox_users_notify_winners(){
    if( isset( $_POST['giveasap_notify'] ) ) {
        $post_id = $_POST['post_ID'];
        if( $post_id == 0 ) {
            return false;
        }
        if( isset( $_GET['action'] ) ) {
            unset( $_GET['action'] );
        }
        if( isset( $_GET['message'] ) ) {
            unset( $_GET['message'] );
        }

        $winners = giveasap_get_winners( $post_id );

        giveasap_notify_winners( $winners, $post_id );

        giveasap_set_status( $post_id, 'giveasap', 'giveasap_notified', 'giveasap_winners' );
        wp_redirect( admin_url( 'post.php?post=' . $post_id .'&action=edit' ), '301' );
        exit();
    }
}

add_action( 'admin_init', 'giveasap_metabox_users_export', 99 );
function giveasap_metabox_users_export(){
    if( isset( $_POST['giveasap_export'] ) ) {
        $post_id = $_POST['post_ID'];

        if( $post_id == 0 ) {
            return false;
        }
        if( isset( $_GET['action'] ) ) {
            unset( $_GET['action'] );
        }
        if( isset( $_GET['message'] ) ) {
            unset( $_GET['message'] );
        }

        $users = giveasap_get_entries_for( $post_id );

        if( is_array( $users ) && count( $users ) > 0 ) {
            $post = get_post( $post_id );
            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename=giveasap_' . $post->post_name . '_users.csv');

            // create a file pointer connected to the output stream
            $output = fopen('php://output', 'w');

            // output the column headings
            fputcsv($output, array( 'Email Address' ) );
            foreach ( $users as $user ) {
                if( $user->email == '' ) {
                    continue;
                }
                fputcsv( $output, array( $user->email ) );
            }
            exit();
        }
    }
}

/**
 * Display The Giveaway Idea metabox
 * @param  WP_Post $post 
 * @return void       
 */
function giveasap_metabox_ideas( $post ) {
    ?>
    <p>If you have an idea on a feature you would like to see, feel free to write that idea down on our site.</p>
    <a href="http://www.wpsimplegiveaways.com/ideas" target="_blank" class="button button-secondary"><strong>Share your Idea</strong></a>
    <?php
}