<?php
require_once('contact-form.php');
?><!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>The Ski Locker ~ Chamonix</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.css" rel="stylesheet">
    <link rel="stylesheet" href="fonts/font-awesome/css/font-awesome.min.css">

    <!-- Raleway Font -->
    <link href='http://fonts.googleapis.com/css?family=Raleway:300' rel='stylesheet' type='text/css'>

    <!-- Stylesheet -->
    <link href="css/style.css" rel="stylesheet">

  </head>

  <body>
    <div id="navbar" class="navbar navbar-inverse">
     <div class="container">
       <div class="navbar-header">
         <a class="navbar-brand" href="index.html"></a>
         <a class="icon" data-toggle="collapse" data-target="#heart" href="#"><i class="icon-heart pull-right"></i></a>
        <span class="tagline">the ski locker. a creative space in Chamonix</span></div>

        <div id="heart" class="collapse dropdown">
          <div class="arrow-up"></div>
          <ul class="menu">
            <li><a href="https://twitter.com/TheSki_locker">Twitter</a></li>
            <li><a href="http://www.facebook.com/theskilocker">Facebook</a></li>
            <li><a href="https://github.com/ChamGeeks">Github</a></li>
          </ul>
        </div>
      </div>
    </div>

    <!-- Home Section -->
      <!-- Modal For Gallery -->
      <div class="modal fade" id="modal1" tabindex="-1" role="dialog">
        <div class="wrapper">
          <img data-dismiss="modal" class="img-responsive icon" src="img/icon-pencil.png" alt="">
          <h2 class="modal-title"><a href="https://www.iconfinder.com/search/?q=iconset%3Aflat-ui-free">Flat UI Icons</a></h2>

        </div><!-- /.content -->
      </div><!-- /.modal -->
      <div class="modal fade" id="modal2" tabindex="-1" role="dialog">
        <div class="wrapper">
          <img data-dismiss="modal" class="img-responsive icon" src="img/icon-pencil.png" alt="">
          <h2 class="modal-title"><a href="https://www.iconfinder.com/search/?q=iconset%3Aflat-ui-free">Flat UI Icons</a></h2>
        </div><!-- /.content -->
      </div><!-- /.modal -->
      <div class="modal fade" id="modal3" tabindex="-1" role="dialog">
        <div class="wrapper">
          <img data-dismiss="modal" class="img-responsive icon" src="img/icon-book.png" alt="">
          <h2 class="modal-title"><a href="https://www.iconfinder.com/search/?q=iconset%3Aflat-ui-free">Flat UI Icons</a></h2>
        </div><!-- /.content -->
      </div><!-- /.modal -->


    <div id="home" class="section welcome">

      <div class="container">
        <div class="content">
    			<!-- FILTER CONTROLS -->

    			<div class="controls">
  				  <a href="#" class="col-md-4 col-sm-4 col-xs-12 filter active" data-filter="locker">The Ski Locker</a>
  				  <a href="#" class="col-md-4 col-sm-4 col-xs-12 filter" data-filter="contact">Contact</a>
  				  <a href="#" class="col-md-4 col-sm-4 col-xs-12 filter" data-filter="chamonix">Chamonix</a>
  				  <span class="stretch"></span>
    			</div>

    		</div>
    		<div id="hero-panels" class="gallery row">
    		  <ul>
    		    <li class="col-md-4 col-sm-4 col-xs-12 mix locker" data-cat="locker">
                <a href="#theskilocker" class="mix-cover gray">
                  <span class="valign"></span>
                  <img src="img/icon-clock.png" alt="icon clock">
        		      <span class="overlay"><span class="valign"></span>Our story</span>
                </a>
      		  </li>
    		    <li class="col-md-4 col-sm-4 col-xs-12 mix locker" data-cat="locker">
                <a href="index.html#pricing" class="mix-cover gray">
                  <span class="valign"></span>
                  <img src="img/icon-pencil.png" alt="icon pencil">
        		      <div class="overlay"><span class="valign"></span>Prices</div>
                </a>
            </li>
      		  <li class="col-md-4 col-sm-4 col-xs-12 mix locker" data-cat="locker">
                <a href="index.html#team" class="mix-cover gray">
                  <span class="valign"></span>
                  <img src="img/icon-book.png" alt="icon book">
        		      <div class="overlay"><span class="valign"></span>We work here</div>
                </a>
            </li>
      		  <li class="col-md-4 col-sm-4 col-xs-12 mix contact" data-cat="contact">
                <a href="index.html#contact" class="mix-cover green">
                  <img class="placeholder" src="http://placehold.it/340x300" alt="sintel snowscape">
                  <div class="overlay"><span class="valign"></span>Press and media</div>
        		    </a>
      		  </li>
      		  <li class="col-md-4 col-sm-4 col-xs-12 mix contact" data-cat="contact">
                <a href="index.html#contact" class="mix-cover">
                  <img class="placeholder" src="http://placehold.it/340x300" alt="placeholder">
                  <div class="overlay"><span class="valign"></span>Book a space</div>
        		    </a>
      		  </li>
      		  <li class="col-md-4 col-sm-4 col-xs-12 mix contact" data-cat="contact">
                <a href="index.html#contact" class="mix-cover">
                  <img class="placeholder" src="http://placehold.it/340x300" alt="placeholder">
                  <div class="overlay"><span class="valign"></span>Get in touch</div>
        		    </a>
      		  </li>
      		  <li class="col-md-4 col-sm-4 col-xs-12 mix chamonix" data-cat="chamonix">
                <a href="#" class="mix-cover">
                  <img class="placeholder" src="http://placehold.it/340x300" alt="placeholder">
                  <div class="overlay"><span class="valign"></span>Getting to Chamonix</div>
        		    </a>
      		  </li>
      		  <li class="col-md-4 col-sm-4 col-xs-12 mix chamonix" data-cat="chamonix">
                <a href="#" class="mix-cover">
                  <img class="placeholder" src="http://placehold.it/340x300" alt="placeholder">
                  <div class="overlay"><span class="valign"></span>Where to stay</div>
        		    </a>
      		  </li>
      		  <li class="col-md-4 col-sm-4 col-xs-12 mix chamonix" data-cat="chamonix">
                <a href="#" class="mix-cover">
                  <img class="placeholder" src="http://placehold.it/340x300" alt="placeholder">
                  <div class="overlay"><span class="valign"></span>Activities</div>
        		    </a>
      		  </li>
    		  </ul>
        </div>
      </div>

    </div><!-- /.container -->

    <!-- Services Section -->
    <div id="theskilocker" class="section">
      <div class="container">

        <div class="content">
          <div class="section">
            <div class="row">
              <div class="col-md-5 col-sm-5 col-xs-12">
                <h1>The Ski Locker</h1>
                <p>We that created The Ski Locker is group of entrepreneurs trying to make our living in Chamonix. Most of us at the Ski Locker has been working in London, Stockholm or Paris.
                  The cities are nice, but not a great creative enviroment for mountain lovers like us. We quickly notices the importance of using our passion for the mountains as a key to unlock our creativity and well being.<br/>
                This is not a secret we want to keep to ourselves. Join us and experience deep powder snow, amazing trail runs and the chance to create amazing projects in Chamonix.</p>
              </div>
              <div class="col-md-7 col-sm-7 col-xs-12">
                <img class="slide-in right" src="img/mountian.jpg" alt="The mountains unlock out creativity">
              </div>
            </div>
          </div>
        </div>


        <div class="section">
          <div id="team" class="row">

            <div class="row">
              <div class="col-md-6 col-sm-6 col-xs-12 pull-right">
                <h1>We work here</h1>
                <p>There are many different people working here. Right now we are looking for new recruits, Do you are want to join us?<br/>
                We will update this list as soon as the desks are full and ready for the winter season 2015.</p>
              </div>
              <div class="col-md-6 col-sm-6 col-xs-12 pull-right">
                <img class="slide-in left img-responsive" src="img/theskilocker-worker.jpg" alt="The people working at the Ski Locker in Chamonix">
              </div>
            </div>
          </div> <!-- /#team -->
        </div>

        <div class="section">
          <div class="row last">
            <div class="col-md-5 col-sm-5 col-xs-12">
              <h1>Activities in Chamonix.</h1>
              <p>Chamonix is not like very ski resort, it is active and full of life all year around. Many of the activities is about sport but we also have several cultural events like the Cosmo Jazz festival or the Yoga festival during summer.</p>
                <h2>Winter</h2>
                <p>When the majority of the people in the world think about Chamonix, they think about deep powder turns and great skiers riding down steep slopes or couloirs. It is only a small part of what Chamonix can offer.
                  <br/>The lift Aiguille du Midi that is only 100m from the Ski Locker gives you access to famous off-pist skiing like the "Vallé Blanche" or steeper runs like Couloir des Cosmiques and Glacier Rond. Of course, if you are a fan of pizza you can simply ski tour cross the Italian border and eat lunch there.</p>
                <h2>Summer</h2>
                <p>When you visit Chamonix you won't get surprised why the summer is more popular than the winter in Chamonix. The cold mountains warms up and you are all of a sudden possible to hike, trail run and mountain bike long distances all over the valley.
                  <br/>Chamonix is not only famous for it's winter sports, the climbing in Chamonix are also very populare for mountainers from all over the world.</p>
            </div>
            <div class="col-md-7 col-sm-7 col-xs-12">
              <img class="slide-in right" src="img/petter-wallberg.jpg" alt="Petter Wallberg skiing in Chamonix">
            </div>
          </div>
        </div>

      </div><!-- /.content -->
    </div>


    <div id="pricing" class="section">
      <div class="fixed-container">

        <div class="content text-center">
          <h1>Long-term Pricing Table</h1>
          <p>All prices are per MONTH and EXCKLUDING VAT 25%</p><br /><br /><br /><br />

          <div class="pricing">
            <div class="row">

              <div class="col-md-4 col-sm-4 col-xs-12">
                <div class="wrapper">
                  <h3 class="text-center">Year</h3>
                  <table class="table">
                    <thead>
                      <tr><th><span class="price"><i class="icon-euro"></i>200</span></th></tr>
                    </thead>
                    <tbody>
                      <tr><td>Personal workstation</td></tr>
                      <tr><td>Meeting room</td></tr>
                      <tr><td>Ski Locker</td></tr>
                      <tr><td>Logo on website</td></tr>
                      <tr><td>Blog account</td></tr>
                      <tr><td>Window sign</td></tr>
                      <tr><td><a class="btn" href="#">BOOK NOW</a></td></tr>
                    </tbody>
                  </table>
                </div>
              </div>

              <div class="col-md-4 col-sm-4 col-xs-12">
                <div class="wrapper">
                  <h3 class="text-center">Season</h3>
                  <table class="table">
                    <thead>
                      <tr><th><span class="price"><i class="icon-euro"></i>230</span></th></tr>
                    </thead>
                    <tbody>
                      <tr><td>Personal workstation</td></tr>
                      <tr><td>Meeting room</td></tr>
                      <tr><td>Ski Locker</td></tr>
                      <tr><td>Logo on website</td></tr>
                      <tr><td><s>Blog account</s></td></tr>
                      <tr><td><s>Window sign</s></td></tr>
                      <tr><td><a class="btn" href="#">BOOK NOW</a></td></tr>
                    </tbody>
                  </table>
                </div>
              </div>

              <div class="col-md-4 col-sm-4 col-xs-12">
                <div class="wrapper">
                  <h3 class="text-center">Month</h3>
                  <table class="table">
                    <thead>
                      <tr><th><span class="price"><i class="icon-euro"></i>270</span></th></tr>
                    </thead>
                    <tbody>
                      <tr><td><s>Personal</s> Flexible workstation</td></tr>
                      <tr><td>Meeting room</td></tr>
                      <tr><td>Ski Locker</td></tr>
                      <tr><td><s>Logo on website</s></td></tr>
                      <tr><td><s>Blog account</s></td></tr>
                      <tr><td><s>Window sign</s></td></tr>
                      <tr><td><a class="btn" href="#">BOOK NOW</a></td></tr>
                    </tbody>
                  </table>
                </div>
              </div>
              <div class="clearfix"></div>
            </div>
          </div>
        </div>
      </div>
    </div><!-- /.container -->


    <div id="pricing-short-term" class="section">
      <div class="fixed-container">

        <div class="content text-center">
          <h1>Short-term Pricing Table</h1>
          <p>All prices are EXCLUDING VAT 25%</p><br /><br /><br /><br />

          <div class="pricing">
            <div class="row">
              <div class="col-md-4 col-sm-4 col-xs-12">
                <div class="wrapper">
                  <h3 class="text-center">Meeting Room</h3>
                  <table class="table">
                    <thead>
                      <tr><th><span class="price"><i class="icon-euro"></i>50/h</span></th></tr>
                    </thead>
                    <tbody>
                      <tr><td>Whiteboard</td></tr>
                      <tr><td>LED screen with Chromecast</td></tr>
                      <tr><td>Sound proof</td></tr>
                      <tr><td>6 chairs</td></tr>
                      <tr><td><a class="btn" href="#">BOOK NOW</a></td></tr>
                    </tbody>
                  </table>
                </div>
              </div>

              <div class="col-md-4 col-sm-4 col-xs-12">
                <div class="wrapper">
                  <h3 class="text-center">Per Hour</h3>
                  <table class="table">
                    <thead>
                      <tr><th><span class="price"><i class="icon-euro"></i>15</span></th></tr>
                    </thead>
                    <tbody>
                      <tr><td>Flexible workstation</td></tr>
                      <tr><td>Internet</td></tr>
                      <tr><td>Printer</td></tr>
                      <tr><td><s>Ski Locker</s></td></tr>
                      <tr><td><a class="btn" href="#">BOOK NOW</a></td></tr>
                    </tbody>
                  </table>
                </div>
              </div>

              <div class="col-md-4 col-sm-4 col-xs-12">
                <div class="wrapper">
                  <h3 class="text-center">Per Day</h3>
                  <table class="table">
                    <thead>
                      <tr><th><span class="price"><i class="icon-euro"></i>30</span></th></tr>
                    </thead>
                    <tbody>
                      <tr><td>Flexible workstation</td></tr>
                      <tr><td>Internet</td></tr>
                      <tr><td>Printer</td></tr>
                      <tr><td>Ski Locker</td></tr>
                      <tr><td><a class="btn" href="#">BOOK NOW</a></td></tr>
                    </tbody>
                  </table>
                </div>
              </div>
              <div class="clearfix"></div>
            </div>
          </div>
        </div>
      </div>
    </div><!-- /.container -->

    <!-- Contact Section -->
    <div id="contact" class="section">
      <div class="container">

        <div class="content">
          <h1>Contact The Ski Locker</h1>

          <?php
          if($message) {
            echo '<div class="alert alert-color alert-'. $message[0] .'"><p>'.
              $message[1]
            .'</p></div>';
          }
          ?>

          <form role="form" action="/#contact" method="post">
            <div class="form-group">
              <input type="text" class="form-control" name="full_name" id="full_name" placeholder="Full Name" required>
              <label for="full_name"><i class="icon-tag"></i></label>
              <div class="clearfix"></div>
            </div>
            <div class="form-group">
              <input type="email" class="form-control" id="email" name="email" placeholder="Enter email" required>
              <label for="email"><i class="icon-inbox"></i></label>
              <div class="clearfix"></div>
            </div>
            <div class="form-group textarea">
              <textarea rows="6" class="form-control" name="message" id="message" placeholder="Write Message" required></textarea>
              <label for="message"><i class="icon-pencil"></i></label>
              <div class="clearfix"></div>
            </div>

            <input type="text" name="simple" style="display: none;">
            <button type="submit" class="btn btn-large">Send Message</button>
          </form>

        </div>
      </div>
    </div><!-- /.container -->



    <div id="footer" class="section">
      <div class="container">

        <div class="row">
          <div class="col-md-12 col-sm-12 col-xs-12">

            <h3>The Ski Locker</h3>
            <p>
              The Ski Locker was created to give creative people living and visiting Chamonix the chance to meet, ski and work together. Everyone is welcome, even if you just want to hang out.
              <br />
            </p>
            <div class="social-media">
              <a href="https://twitter.com/TheSki_locker" data-toggle="tooltip" title="twitter">
                <i class="icon-twitter"></i>
              </a>
              <a href="http://www.facebook.com/theskilocker" data-toggle="tooltip" title="facebook">
                <i class="icon-facebook"></i>
              </a>
              <a href="https://github.com/ChamGeeks" data-toggle="tooltip" title="github">
                <i class="icon-github"></i>
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="js/jquery-1.9.1.min.js"></script>
    <script src="js/jquery.mixitup.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
    <script src="js/init.js"></script>
  </body>
</html>
