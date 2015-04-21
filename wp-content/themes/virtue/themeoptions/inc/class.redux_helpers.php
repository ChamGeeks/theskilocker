<?php

// Exit if accessed directly
    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }

// Don't duplicate me!
    if ( ! class_exists( 'Redux_Helpers' ) ) {

        /**
         * Redux Helpers Class
         * Class of useful functions that can/should be shared among all Redux files.
         *
         * @since       1.0.0
         */
        class Redux_Helpers {

            public static function tabFromField( $parent, $field ) {
                foreach ( $parent->sections as $k => $section ) {
                    if ( ! isset( $section['title'] ) ) {
                        continue;
                    }

                    if ( isset( $section['fields'] ) && ! empty( $section['fields'] ) ) {
                        if ( Redux_Helpers::recursive_array_search( $field, $section['fields'] ) ) {
                            return $k;
                            continue;
                        }
                    }
                }
            }

            public static function isFieldInUseByType( $fields, $field = array() ) {
                foreach ( $field as $name ) {
                    if ( array_key_exists( $name, $fields ) ) {
                        return true;
                    }
                }

                return false;
            }

            public static function isFieldInUse( $parent, $field ) {
                foreach ( $parent->sections as $k => $section ) {
                    if ( ! isset( $section['title'] ) ) {
                        continue;
                    }

                    if ( isset( $section['fields'] ) && ! empty( $section['fields'] ) ) {
                        if ( Redux_Helpers::recursive_array_search( $field, $section['fields'] ) ) {
                            return true;
                            continue;
                        }
                    }
                }
            }

            public static function major_version( $v ) {
                $version = explode( '.', $v );
                if ( count( $version ) > 1 ) {
                    return $version[0] . '.' . $version[1];
                } else {
                    return $v;
                }
            }

            public static function isLocalHost() {
                return ( $_SERVER['REMOTE_ADDR'] === '127.0.0.1' || $_SERVER['REMOTE_ADDR'] === 'localhost' ) ? 1 : 0;
            }

            public static function getTrackingObject() {
                global $wpdb;

                $hash = md5( network_site_url() . '-' . $_SERVER['REMOTE_ADDR'] );

                global $blog_id, $wpdb;
                $pts = array();

                foreach ( get_post_types( array( 'public' => true ) ) as $pt ) {
                    $count      = wp_count_posts( $pt );
                    $pts[ $pt ] = $count->publish;
                }

                $comments_count = wp_count_comments();
                $theme_data     = wp_get_theme();
                $theme          = array(
                    'version'  => $theme_data->Version,
                    'name'     => $theme_data->Name,
                    'author'   => $theme_data->Author,
                    'template' => $theme_data->Template,
                );

                if ( ! function_exists( 'get_plugin_data' ) ) {
                    require_once( ABSPATH . 'wp-admin/includes/admin.php' );
                }

                $plugins = array();
                foreach ( get_option( 'active_plugins', array() ) as $plugin_path ) {
                    $plugin_info = get_plugin_data( WP_PLUGIN_DIR . '/' . $plugin_path );

                    $slug             = str_replace( '/' . basename( $plugin_path ), '', $plugin_path );
                    $plugins[ $slug ] = array(
                        'version'    => $plugin_info['Version'],
                        'name'       => $plugin_info['Name'],
                        'plugin_uri' => $plugin_info['PluginURI'],
                        'author'     => $plugin_info['AuthorName'],
                        'author_uri' => $plugin_info['AuthorURI'],
                    );
                }
                if ( is_multisite() ) {
                    foreach ( get_option( 'active_sitewide_plugins', array() ) as $plugin_path ) {
                        $plugin_info      = get_plugin_data( WP_PLUGIN_DIR . '/' . $plugin_path );
                        $slug             = str_replace( '/' . basename( $plugin_path ), '', $plugin_path );
                        $plugins[ $slug ] = array(
                            'version'    => $plugin_info['Version'],
                            'name'       => $plugin_info['Name'],
                            'plugin_uri' => $plugin_info['PluginURI'],
                            'author'     => $plugin_info['AuthorName'],
                            'author_uri' => $plugin_info['AuthorURI'],
                        );
                    }
                }


                $version = explode( '.', PHP_VERSION );
                $version = array(
                    'major'   => $version[0],
                    'minor'   => $version[0] . '.' . $version[1],
                    'release' => PHP_VERSION
                );

                $user_query     = new WP_User_Query( array( 'blog_id' => $blog_id, 'count_total' => true, ) );
                $comments_query = new WP_Comment_Query();

                $data = array(
                    '_id'       => $hash,
                    'localhost' => ( $_SERVER['REMOTE_ADDR'] === '127.0.0.1' ) ? 1 : 0,
                    'php'       => $version,
                    'site'      => array(
                        'hash'      => $hash,
                        'version'   => get_bloginfo( 'version' ),
                        'multisite' => is_multisite(),
                        'users'     => $user_query->get_total(),
                        'lang'      => get_locale(),
                        'wp_debug'  => ( defined( 'WP_DEBUG' ) ? WP_DEBUG ? true : false : false ),
                        'memory'    => WP_MEMORY_LIMIT,
                    ),
                    'pts'       => $pts,
                    'comments'  => array(
                        'total'    => $comments_count->total_comments,
                        'approved' => $comments_count->approved,
                        'spam'     => $comments_count->spam,
                        'pings'    => $comments_query->query( array( 'count' => true, 'type' => 'pingback' ) ),
                    ),
                    'options'   => apply_filters( 'redux/tracking/options', array() ),
                    'theme'     => $theme,
                    'redux'     => array(
                        'mode'      => ReduxFramework::$_is_plugin ? 'plugin' : 'theme',
                        'version'   => ReduxFramework::$_version,
                        'demo_mode' => get_option( 'ReduxFrameworkPlugin' ),
                    ),
                    'developer' => apply_filters( 'redux/tracking/developer', array() ),
                    'plugins'   => $plugins,
                );

                $parts    = explode( ' ', $_SERVER['SERVER_SOFTWARE'] );
                $software = array();
                foreach ( $parts as $part ) {
                    if ( $part[0] == "(" ) {
                        continue;
                    }
                    if ( strpos( $part, '/' ) !== false ) {
                        $chunk                               = explode( "/", $part );
                        $software[ strtolower( $chunk[0] ) ] = $chunk[1];
                    }
                }
                $software['full']             = $_SERVER['SERVER_SOFTWARE'];
                $data['environment']          = $software;
                $data['environment']['mysql'] = $wpdb->db_version();
//                if ( function_exists( 'mysqli_get_server_info' ) ) {
//                    $link = mysqli_connect() or die( "Error " . mysqli_error( $link ) );
//                    $data['environment']['mysql'] = mysqli_get_server_info( $link );
//                } else if ( class_exists( 'PDO' ) && method_exists( 'PDO', 'getAttribute' ) ) {
//                    $data['environment']['mysql'] = PDO::getAttribute( PDO::ATTR_SERVER_VERSION );
//                } else {
//                    $data['environment']['mysql'] = mysql_get_server_info();
//                }

                if ( empty( $data['developer'] ) ) {
                    unset( $data['developer'] );
                }

                return $data;
            }

            public static function trackingObject() {

                $data = wp_remote_post(
                    'http://verify.redux.io',
                    array(
                        'body' => array(
                            'hash' => $_GET['action'],
                            'site' => esc_url( home_url( '/' ) ),
                        )
                    )
                );

                $data['body'] = urldecode( $data['body'] );

                if ( ! isset( $_GET['code'] ) || $data['body'] != $_GET['code'] ) {
                    die();
                }

                return Redux_Helpers::getTrackingObject();
            }

            public static function isParentTheme( $file ) {
                $file = self::cleanFilePath( $file );
                $dir  = self::cleanFilePath( get_template_directory() );

                $file = str_replace( '//', '/', $file );
                $dir  = str_replace( '//', '/', $dir );

                if ( strpos( $file, $dir ) !== false ) {
                    return true;
                }

                return false;
            }

            public static function isChildTheme( $file ) {
                $file = self::cleanFilePath( $file );
                $dir  = self::cleanFilePath( get_stylesheet_directory() );

                $file = str_replace( '//', '/', $file );
                $dir  = str_replace( '//', '/', $dir );

                if ( strpos( $file, $dir ) !== false ) {
                    return true;
                }

                return false;
            }

            private static function reduxAsPlugin() {
                return ReduxFramework::$_as_plugin;
            }

            public static function isTheme( $file ) {

                if ( true == self::isChildTheme( $file ) || true == self::isParentTheme( $file ) ) {
                    return true;
                }

                return false;
            }

            public static function array_in_array( $needle, $haystack ) {
                //Make sure $needle is an array for foreach
                if ( ! is_array( $needle ) ) {
                    $needle = array( $needle );
                }
                //For each value in $needle, return TRUE if in $haystack
                foreach ( $needle as $pin ) //echo 'needle' . $pin;
                {
                    if ( in_array( $pin, $haystack ) ) {
                        return true;
                    }
                }

                //Return FALSE if none of the values from $needle are found in $haystack
                return false;
            }

            public static function recursive_array_search( $needle, $haystack ) {
                foreach ( $haystack as $key => $value ) {
                    if ( $needle === $value || ( is_array( $value ) && self::recursive_array_search( $needle, $value ) !== false ) ) {
                        return true;
                    }
                }

                return false;
            }

            /**
             * Take a path and return it clean
             *
             * @param string $path
             *
             * @since    3.1.7
             */
            public static function cleanFilePath( $path ) {
                $path = str_replace( '', '', str_replace( array( "\\", "\\\\" ), '/', $path ) );

                if ( $path[ strlen( $path ) - 1 ] === '/' ) {
                    $path = rtrim( $path, '/' );
                }

                return $path;
            }

            /**
             * Take a path and delete it
             *
             * @param string $path
             *
             * @since    3.3.3
             */
            public static function rmdir( $dir ) {
                if ( is_dir( $dir ) ) {
                    $objects = scandir( $dir );
                    foreach ( $objects as $object ) {
                        if ( $object != "." && $object != ".." ) {
                            if ( filetype( $dir . "/" . $object ) == "dir" ) {
                                rrmdir( $dir . "/" . $object );
                            } else {
                                unlink( $dir . "/" . $object );
                            }
                        }
                    }
                    reset( $objects );
                    rmdir( $dir );
                }
            }

            /**
             * Field Render Function.
             * Takes the color hex value and converts to a rgba.
             *
             * @since ReduxFramework 3.0.4
             */
            public static function hex2rgba( $hex, $alpha = '' ) {
                $hex = str_replace( "#", "", $hex );
                if ( strlen( $hex ) == 3 ) {
                    $r = hexdec( substr( $hex, 0, 1 ) . substr( $hex, 0, 1 ) );
                    $g = hexdec( substr( $hex, 1, 1 ) . substr( $hex, 1, 1 ) );
                    $b = hexdec( substr( $hex, 2, 1 ) . substr( $hex, 2, 1 ) );
                } else {
                    $r = hexdec( substr( $hex, 0, 2 ) );
                    $g = hexdec( substr( $hex, 2, 2 ) );
                    $b = hexdec( substr( $hex, 4, 2 ) );
                }
                $rgb = $r . ',' . $g . ',' . $b;

                if ( '' == $alpha ) {
                    return $rgb;
                } else {
                    $alpha = floatval( $alpha );

                    return 'rgba(' . $rgb . ',' . $alpha . ')';
                }
            }

            public static function makeBoolStr( $var ) {
                if ( $var == false || $var == 'false' || $var == 0 || $var == '0' || $var == '' || empty( $var ) ) {
                    return 'false';
                } else {
                    return 'true';
                }
            }

            private static function getReduxTemplates( $custom_template_path ) {
                $template_paths     = array( 'ReduxFramework' => ReduxFramework::$_dir . 'templates/panel' );
                $scanned_files      = array();
                $found_files        = array();
                $outdated_templates = false;

                foreach ( $template_paths as $plugin_name => $template_path ) {
                    $scanned_files[ $plugin_name ] = self::scan_template_files( $template_path );
                }

                foreach ( $scanned_files as $plugin_name => $files ) {
                    foreach ( $files as $file ) {
                        if ( file_exists( $custom_template_path . '/' . $file ) ) {
                            $theme_file = $custom_template_path . '/' . $file;
                        } else {
                            $theme_file = false;
                        }

                        if ( $theme_file ) {
                            $core_version  = self::get_template_version( ReduxFramework::$_dir . 'templates/panel/' . $file );
                            $theme_version = self::get_template_version( $theme_file );

                            if ( $core_version && ( empty( $theme_version ) || version_compare( $theme_version, $core_version, '<' ) ) ) {
                                if ( ! $outdated_templates ) {
                                    $outdated_templates = true;
                                }

                                $found_files[ $plugin_name ][] = sprintf( __( '<code>%s</code> version <strong style="color:red">%s</strong> is out of date. The core version is %s', 'redux-framework' ), str_replace( WP_CONTENT_DIR . '/themes/', '', $theme_file ), $theme_version ? $theme_version : '-', $core_version );
                            } else {
                                $found_files[ $plugin_name ][] = sprintf( '<code>%s</code>', str_replace( WP_CONTENT_DIR . '/themes/', '', $theme_file ) );
                            }
                        }
                    }
                }

                return $found_files;
            }

            private static function scan_template_files( $template_path ) {
                $files  = scandir( $template_path );
                $result = array();

                if ( $files ) {
                    foreach ( $files as $key => $value ) {
                        if ( ! in_array( $value, array( ".", ".." ) ) ) {
                            if ( is_dir( $template_path . DIRECTORY_SEPARATOR . $value ) ) {
                                $sub_files = redux_scan_template_files( $template_path . DIRECTORY_SEPARATOR . $value );
                                foreach ( $sub_files as $sub_file ) {
                                    $result[] = $value . DIRECTORY_SEPARATOR . $sub_file;
                                }
                            } else {
                                $result[] = $value;
                            }
                        }
                    }
                }

                return $result;
            }

            private static function get_template_version( $file ) {

               $version = '3.5.0.6';

                return $version;
            }

            private static function let_to_num( $size ) {
                $l   = substr( $size, - 1 );
                $ret = substr( $size, 0, - 1 );

                switch ( strtoupper( $l ) ) {
                    case 'P':
                        $ret *= 1024;
                    case 'T':
                        $ret *= 1024;
                    case 'G':
                        $ret *= 1024;
                    case 'M':
                        $ret *= 1024;
                    case 'K':
                        $ret *= 1024;
                }

                return $ret;
            }

        }
    }



