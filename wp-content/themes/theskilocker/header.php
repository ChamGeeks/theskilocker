<!doctype html>
<html <?php language_attributes(); ?> class="no-js">
  <head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <title><?php wp_title(''); ?><?php if(wp_title('', false)) { echo ' : '; } ?><?php bloginfo('name'); ?></title>

    <link href="//www.google-analytics.com" rel="dns-prefetch">
    <link href="/favicon.ico" rel="shortcut icon">

    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">
    <link href="//fonts.googleapis.com/css?family=Raleway:300" rel="stylesheet" type="text/css">

    <?php wp_head(); ?>

  </head>
  <body <?php body_class(); ?>>

    <!-- wrapper -->
    <div class="container">

      <!-- header -->
      <header class="header row" role="banner">

          <!-- logo -->
          <a href="<?php echo home_url(); ?>" class="logo col-md-4">
            <!-- svg logo - toddmotto.com/mastering-svg-use-for-a-retina-web-fallbacks-with-png-script -->
            <img src="<?php echo get_template_directory_uri(); ?>/img/logo.png" alt="The ski locker" class="logo-img">
          </a>
          <!-- /logo -->

          <!-- nav -->
          <nav class="col-md-8" role="navigation">
            <?php html5blank_nav(); ?>
          </nav>
          <!-- /nav -->

      </header>
      <!-- /header -->
