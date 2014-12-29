<div id="simple-flat-advanced-design-settings">
    <div class="dh_ptp_accordion">
        <h3><?php _e('General Settings', PTP_LOC); ?></h3>
        <div>
            <table>
                <tr>
                    <td class="settings-title"><?php _e('Featured Label Text', PTP_LOC); ?></td>
                    <?php $mb->the_field('most-popular-label-text'); ?>
                    <td>
                        <?php $value = (!is_null($mb->get_the_value()))?$metabox->get_the_value():__('Most Popular', PTP_LOC); ?>
                        <input type="text" name="<?php $metabox->the_name(); ?>" id="<?php $metabox->the_name(); ?>" value="<?php echo $value;?>" />
                    </td>
                </tr>
                <tr>
                    <td class="settings-title"><?php _e('Border Radius', PTP_LOC); ?></td>
                    <?php $mb->the_field('rounded-corners'); ?>
                    <td><select class="form-control" name="<?php $metabox->the_name(); ?>">
                            <option value="0px" <?php
                            if(!is_null($mb->get_the_value())) {
                                if($mb->get_the_value() == '0px') {
                                    echo 'selected';
                                }
                            } else {
                                echo 'selected';
                            }
                            ?> ><?php _e('No Rounded Corners', PTP_LOC); ?></option>
                            <?php
                                for($i=1;$i<=20;++$i){
                                    if($mb->get_the_value() == $i . 'px') {
                                        echo '<option value="' . $i . 'px" selected>' . $i . 'px</option>';
                                    } else {
                                        echo '<option value="' . $i . 'px" >' . $i . 'px</option>';
                                    }
                                }
                            ?>
                        </select>
                    </td>
                </tr>
                
                
                   <!--  Automatically match Row Height  -->
                  <tr>
                     <?php $mb->the_field('match-column-height-dg1'); ?>
                    <td class="settings-title">
                        <label for="match-column-height-dg1" style="margin: 0; font-weight: normal;"><?php _e('Automatically match Row Height', PTP_LOC); ?></label>
                    </td>
                    <td>
                        <?php $mb->the_field('match-column-height-dg1'); ?>
                        <input type="checkbox" onchange="return consistent_match_column_height(this) " class="tt-match-column-height-checkbox" name="<?php $metabox->the_name(); ?>" id="match-column-height-dg1" value="1" <?php      if (!$meta) { echo 'checked="checked"'; } else  if ($metabox->get_the_value()) echo 'checked="checked"'; ?>/>
                    </td>
                </tr>
            </table>
        </div>
        <h3><?php _e('Font Sizes', PTP_LOC); ?></h3>
        <div>
            <table>
                <tr>
                    <td class="settings-title"><?php _e('Featured Label Font Size', PTP_LOC); ?></td>
                    <td>
                        <?php $mb->the_field('most-popular-font-size'); ?>
                        <input class="form-control float-input" type="text" name="<?php $metabox->the_name(); ?>" value="<?php if(!is_null($mb->get_the_value())) echo $metabox->the_value(); else echo "0.9"; ?>"/>
                    </td>
                    <td>
                        <?php $mb->the_field('most-popular-font-size-type'); ?>
                        <select  name="<?php $metabox->the_name(); ?>">
                            <option value="em" <?php
                            if(!is_null($mb->get_the_value())) {
                                if($mb->get_the_value() == 'em') {
                                    echo 'selected';
                                }
                            } else {
                                echo 'selected';
                            }
                            ?> >em</option>
                            <option value="px" <?php
                            if(!is_null($mb->get_the_value())) {
                                if($mb->get_the_value() == 'px') {
                                    echo 'selected';
                                }
                            }
                            ?>>px</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="settings-title"><?php _e('Plan Name Font Size', PTP_LOC); ?></td>
                    <td>
                        <?php $mb->the_field('plan-name-font-size'); ?>
                        <input class="form-control float-input" type="text" name="<?php $metabox->the_name(); ?>" value="<?php if(!is_null($mb->get_the_value())) echo $metabox->the_value(); else echo "1"; ?>"/>
                    </td>
                    <td>
                        <?php $mb->the_field('plan-name-font-size-type'); ?>
                        <select  name="<?php $metabox->the_name(); ?>">
                            <option value="em" <?php
                            if(!is_null($mb->get_the_value())) {
                                if($mb->get_the_value() == 'em') {
                                    echo 'selected';
                                }
                            } else {
                                echo 'selected';
                            }
                            ?> >em</option>
                            <option value="px" <?php
                            if(!is_null($mb->get_the_value())) {
                                if($mb->get_the_value() == 'px') {
                                    echo 'selected';
                                }
                            }
                            ?>>px</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="settings-title"><?php _e('Price Font Size', PTP_LOC); ?></td>
                    <td>
                        <?php $mb->the_field('price-font-size'); ?>
                        <input class="form-control float-input" type="text" name="<?php $metabox->the_name(); ?>" value="<?php if(!is_null($mb->get_the_value())) echo $metabox->the_value(); else echo "1.25"; ?>"/>
                    </td>
                    <td>
                        <?php $mb->the_field('price-font-size-type'); ?>
                        <select  name="<?php $metabox->the_name(); ?>">
                            <option value="em" <?php
                            if(!is_null($mb->get_the_value())) {
                                if($mb->get_the_value() == 'em') {
                                    echo 'selected';
                                }
                            } else {
                                echo 'selected';
                            }
                            ?> >em</option>
                            <option value="px" <?php
                            if(!is_null($mb->get_the_value())) {
                                if($mb->get_the_value() == 'px') {
                                    echo 'selected';
                                }
                            }
                            ?>>px</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="settings-title"><?php _e('Bullet Item Font Size', PTP_LOC); ?></td>
                    <td>
                        <?php $mb->the_field('bullet-item-font-size'); ?>
                        <input class="form-control float-input" type="text" name="<?php $metabox->the_name(); ?>" value="<?php if(!is_null($mb->get_the_value())) echo $metabox->the_value(); else echo "0.875"; ?>"/>
                    </td>
                    <td>
                        <?php $mb->the_field('bullet-item-font-size-type'); ?>
                        <select  name="<?php $metabox->the_name(); ?>">
                            <option value="em" <?php
                            if(!is_null($mb->get_the_value())) {
                                if($mb->get_the_value() == 'em') {
                                    echo 'selected';
                                }
                            } else {
                                echo 'selected';
                            }
                            ?> >em</option>
                            <option value="px" <?php
                            if(!is_null($mb->get_the_value())) {
                                if($mb->get_the_value() == 'px') {
                                    echo 'selected';
                                }
                            }
                            ?>>px</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="settings-title"><?php _e('Button Font Size', PTP_LOC); ?></td>
                    <td>
                        <?php $mb->the_field('button-font-size'); ?>
                        <input class="form-control float-input" type="text" name="<?php $metabox->the_name(); ?>" value="<?php if(!is_null($mb->get_the_value())) echo $metabox->the_value(); else echo "1"; ?>"/>
                    </td>
                    <td>
                        <?php $mb->the_field('button-font-size-type'); ?>
                        <select  name="<?php $metabox->the_name(); ?>">
                            <option value="em" <?php
                            if(!is_null($mb->get_the_value())) {
                                if($mb->get_the_value() == 'em') {
                                    echo 'selected';
                                }
                            } else {
                                echo 'selected';
                            }
                            ?> >em</option>
                            <option value="px" <?php
                            if(!is_null($mb->get_the_value())) {
                                if($mb->get_the_value() == 'px') {
                                    echo 'selected';
                                }
                            }
                            ?>>px</option>
                        </select>
                    </td>
                </tr>
            </table>
        </div>
        
        <h3><?php _e("Button Colors", PTP_LOC); ?></h3>
        <div>
            <table>
               <!-- Headline -->
                <tr class="table-headline"><td><br/><?php _e('Button Color (Unfeatured Columns)', PTP_LOC); ?></td></tr>
                <tr>
                    <td class="settings-title"><?php _e('Button Color', PTP_LOC); ?></td>
                    <?php $mb->the_field('button-color'); ?>
                    <?php $value = (!is_null($mb->get_the_value()))?$mb->get_the_value():'#3498db'; ?>
                    <td><input type="text" name="<?php $mb->the_name(); ?>" class="button-color" value="<?php echo $value; ?>" class="my-color-field form-control" data-default-color="#3498db" /></td>
                </tr>
                <tr>
                    <td class="settings-title"><?php _e('Button Border Color', PTP_LOC); ?></td>
                    <?php $mb->the_field('button-border-color'); ?>
                    <?php $value = (!is_null($mb->get_the_value()))?$mb->get_the_value():'#2980b9'; ?>
                    <td><input type="text" name="<?php $mb->the_name(); ?>" class="button-border-color" value="<?php echo $value; ?>" class="my-color-field" data-default-color="#2980b9" /></td>
                </tr>
                <tr>
                    <td class="settings-title"><?php _e('Button Hover Color', PTP_LOC); ?></td>
                    <?php $mb->the_field('button-hover-color'); ?>
                    <?php $value = (!is_null($mb->get_the_value()))?$mb->get_the_value():'#2980b9'; ?>
                    <td><input type="text" name="<?php $mb->the_name(); ?>" class="button-border-color" value="<?php echo $value; ?>" class="my-color-field" data-default-color="#2980b9" /></td>
                </tr>
                <tr>
                    <td class="settings-title"><?php _e('Button Font Color', PTP_LOC); ?></td>
                    <?php $mb->the_field('button-font-color'); ?>
                    <?php $value = (!is_null($mb->get_the_value()))?$mb->get_the_value():'#ffffff'; ?>
                    <td><input type="text" name="<?php $mb->the_name(); ?>" class="colorpicker-no-palettes" value="<?php echo $value; ?>" class="my-color-field" data-default-color="#ffffff" /></td>
                </tr>
                
                <!-- Headline -->
                <tr class="table-headline"><td><br/><?php _e('Button Color (Featured Column)', PTP_LOC); ?></td></tr>
                <tr>
                    <td class="settings-title"><?php _e('Featured-Button Color', PTP_LOC); ?></td>
                    <?php $mb->the_field('featured-button-color'); ?>
                    <?php $value = (!is_null($mb->get_the_value()))?$mb->get_the_value():'#e74c3c'; ?>
                    <td><input type="text" name="<?php $mb->the_name(); ?>" class="button-color" value="<?php echo $value; ?>" class="my-color-field form-control" data-default-color="#e74c3c" /></td>
                </tr>
                <tr>
                    <td class="settings-title"><?php _e('Featured-Button Border Color', PTP_LOC); ?></td>
                    <?php $mb->the_field('featured-button-border-color'); ?>
                    <?php $value = (!is_null($mb->get_the_value()))?$mb->get_the_value():'#c0392b'; ?>
                    <td><input type="text" name="<?php $mb->the_name(); ?>" class="button-border-color" value="<?php echo $value; ?>" class="my-color-field" data-default-color="#c0392b" /></td>
                </tr>
                <tr>
                    <td class="settings-title"><?php _e('Featured-Button Hover Color', PTP_LOC); ?></td>
                    <?php $mb->the_field('featured-button-hover-color'); ?>
                    <?php $value = (!is_null($mb->get_the_value()))?$mb->get_the_value():'#c0392b'; ?>
                    <td><input type="text" name="<?php $mb->the_name(); ?>" class="button-border-color" value="<?php echo $value; ?>" class="my-color-field" data-default-color="#c0392b" /></td>
                </tr>
                <tr>
                    <td class="settings-title"><?php _e('Featured-Button Font Color', PTP_LOC); ?></td>
                    <?php $mb->the_field('featured-button-font-color'); ?>
                    <?php $value = (!is_null($mb->get_the_value()))?$mb->get_the_value():'#ffffff'; ?>
                    <td><input type="text" name="<?php $mb->the_name(); ?>" class="colorpicker-no-palettes" value="<?php echo $value; ?>" class="my-color-field" data-default-color="#ffffff" /></td>
                </tr>
                
            </table>
        </div>
        
        <?php 
        /*   
         * 
         *    Hide the greyed out area
         * 
        <h3><?php _e("Font & Background Colors (Premium Only)", PTP_LOC); ?></h3>
        <div>
            <table>
                <tr class="table-headline">
                    <td><?php _e('Background Colors (Unfeatured Columns)', PTP_LOC); ?></td>
                </tr>
                <tr class="ept-demo">
                    <td class="settings-title"><label for="unfeatured-border-color-demo"><?php _e('Border Color', PTP_LOC); ?></label></td>
                    <td><div class="wp-picker-container"><a tabindex="0" class="wp-color-result" title="Select Color" data-current="Current Color" style="background-color: #dddddd;"></a></div></td>
                </tr>
                <tr class="ept-demo">
                    <td class="settings-title"><label for="title-area-background-color-demo"><?php _e('Title Background Color', PTP_LOC); ?></label></td>
                    <td><div class="wp-picker-container"><a tabindex="0" class="wp-color-result" title="Select Color" data-current="Current Color" style="background-color: #dddddd;"></a></div></td>
                </tr>
                <tr class="ept-demo">
                    <td class="settings-title"><label for="pricing-background-color-demo"><?php _e('Pricing Background Color', PTP_LOC); ?></label></td>
                    <td><div class="wp-picker-container"><a tabindex="0" class="wp-color-result" title="Select Color" data-current="Current Color" style="background-color: #eeeeee;"></a></div></td>
                </tr>
                <tr class="ept-demo">
                    <td class="settings-title"><label for="unfeatured-button-area-background-color-demo"><?php _e('Button Area Background Color', PTP_LOC); ?></label></td>
                    <td><div class="wp-picker-container"><a tabindex="0" class="wp-color-result" title="Select Color" data-current="Current Color" style="background-color: #eeeeee;"></a></div></td>
                </tr>
                <tr class="ept-demo">
                    <td class="settings-title"><label for="featured-background-color-demo"><?php _e('Bullet Item Background Color', PTP_LOC); ?></label></td>
                    <td><div class="wp-picker-container"><a tabindex="0" class="wp-color-result" title="Select Color" data-current="Current Color" style="background-color: #ffffff;"></a></div></td>
                </tr>
                
                <!-- Headline -->
                <tr class="table-headline">
                    <td><br/><?php _e('Font Colors (Unfeatured Columns)', PTP_LOC); ?></td>
                </tr>
                <tr class="ept-demo">
                    <td class="settings-title"><label for="title-area-font-color-demo"><?php _e('Title Font Color', PTP_LOC); ?></label></td>
                    <td><div class="wp-picker-container"><a tabindex="0" class="wp-color-result" title="Select Color" data-current="Current Color" style="background-color: #333333;"></a></div></td>
                </tr>
                <tr class="ept-demo">
                    <td class="settings-title"><label for="pricing-area-font-color-demo"><?php _e('Pricing Font Color', PTP_LOC); ?></label></td>
                    <td><div class="wp-picker-container"><a tabindex="0" class="wp-color-result" title="Select Color" data-current="Current Color" style="background-color: #333333;"></a></div></td>
                </tr>
                <tr class="ept-demo">
                    <td class="settings-title"><label for="featured-font-color-demo"><?php _e('Feature Color', PTP_LOC); ?></label></td>
                    <td><div class="wp-picker-container"><a tabindex="0" class="wp-color-result" title="Select Color" data-current="Current Color" style="background-color: #333333;"></a></div></td>
                </tr>
                
                 <!-- Headline -->
                <tr class="table-headline">
                    <td><?php _e('Background Colors (Featured Columns)', PTP_LOC); ?></td>
                </tr>
                <tr class="ept-demo">
                    <td class="settings-title"><label for="featured-border-color-demo"><?php _e('Border Color', PTP_LOC); ?></label></td>
                    <td><div class="wp-picker-container"><a tabindex="0" class="wp-color-result" title="Select Color" data-current="Current Color" style="background-color: #dddddd;"></a></div></td>
                </tr>
                <tr class="ept-demo">
                    <td class="settings-title"><label for="featured-title-area-background-color-demo"><?php _e('Title Background Color', PTP_LOC); ?></label></td>
                    <td><div class="wp-picker-container"><a tabindex="0" class="wp-color-result" title="Select Color" data-current="Current Color" style="background-color: #dddddd;"></a></div></td>
                </tr>
                <tr class="ept-demo">
                    <td class="settings-title"><label for="featured-pricing-background-color-demo"><?php _e('Pricing Background Color', PTP_LOC); ?></label></td>
                    <td><div class="wp-picker-container"><a tabindex="0" class="wp-color-result" title="Select Color" data-current="Current Color" style="background-color: #eeeeee;"></a></div></td>
                </tr>
                <tr class="ept-demo">
                    <td class="settings-title"><label for="featured-button-area-background-color-demo"><?php _e('Button Area Background Color', PTP_LOC); ?></label></td>
                    <td><div class="wp-picker-container"><a tabindex="0" class="wp-color-result" title="Select Color" data-current="Current Color" style="background-color: #eeeeee;"></a></div></td>
                </tr>
                <tr class="ept-demo">
                    <td class="settings-title"><label for="featured-feature-background-color-demo"><?php _e('Bullet Item Background Color', PTP_LOC); ?></label></td>
                    <td><div class="wp-picker-container"><a tabindex="0" class="wp-color-result" title="Select Color" data-current="Current Color" style="background-color: #ffffff;"></a></div></td>
                </tr>
                <tr class="ept-demo">
                    <td class="settings-title"><label for="featured-feature-label-border-color-demo"><?php _e('Featured Label Border Color', PTP_LOC); ?></label></td>
                    <td><div class="wp-picker-container"><a tabindex="0" class="wp-color-result" title="Select Color" data-current="Current Color" style="background-color: #7f8c8d;"></a></div></td>
                </tr>
                <tr class="ept-demo">
                    <td class="settings-title"><label for="most-popular-area-background-color-demo"><?php _e('Featured Label Background Color', PTP_LOC); ?></label></td>
                    <td><div class="wp-picker-container"><a tabindex="0" class="wp-color-result" title="Select Color" data-current="Current Color" style="background-color: #7f8c8d;"></a></div></td>
                </tr>
                
                <!-- Headline -->
                <tr class="table-headline">
                    <td><br/><?php _e('Font Colors (Featured Columns)', PTP_LOC); ?></td>
                </tr>
                <tr class="ept-demo">
                    <td class="settings-title"><label for="featured-title-area-font-color-demo"><?php _e('Title Font Color', PTP_LOC); ?></label></td>
                    <td><div class="wp-picker-container"><a tabindex="0" class="wp-color-result" title="Select Color" data-current="Current Color" style="background-color: #333333;"></a></div></td>
                </tr>
                <tr class="ept-demo">
                    <td class="settings-title"><label for="featured-pricing-area-font-color-demo"><?php _e('Pricing Font Color', PTP_LOC); ?></label></td>
                    <td><div class="wp-picker-container"><a tabindex="0" class="wp-color-result" title="Select Color" data-current="Current Color" style="background-color: #333333;"></a></div></td>
                </tr>
                <tr class="ept-demo">
                    <td class="settings-title"><label for="featured-feature-font-color-demo"><?php _e('Feature Color', PTP_LOC); ?></label></td>
                    <td><div class="wp-picker-container"><a tabindex="0" class="wp-color-result" title="Select Color" data-current="Current Color" style="background-color: #333333;"></a></div></td>
                </tr>
                <tr class="ept-demo">
                    <td class="settings-title"><label for="most-popular-font-color-demo"><?php _e('Feature Label Font Color', PTP_LOC); ?></label></td>
                    <td><div class="wp-picker-container"><a tabindex="0" class="wp-color-result" title="Select Color" data-current="Current Color" style="background-color: #ffffff;"></a></div></td>
                </tr>
                
            </table>
        </div>

        <h3><?php _e("Advanced Settings (Premium Only)", PTP_LOC); ?></h3>
        <div>
            <table>
                <tr class="ept-demo">
                    <td class="settings-title"><label for="design1-hide-empty-rows-demo"><?php _e('Hide Empty Rows', PTP_LOC); ?></label></td>
                    <td><input type="checkbox" name="design1-hide-empty-rows-demo" id="design1-hide-empty-rows-demo" value="1"/></td>
                </tr>
                <tr class="ept-demo">
                    <td class="settings-title"><label for="design1-call-action-buttons-demo"><?php _e('Hide Call To Action Buttons', PTP_LOC); ?></label></td>
                    <td><input type="checkbox" name="design1-call-action-buttons-demo" id="design1-call-action-buttons-demo" value="1" /></td>
                </tr>
                <tr class="ept-demo">
                    <td class="settings-title"><label for="design1-open-link-in-new-tab-demo"><?php _e('Open Link in New Tab', PTP_LOC); ?></label></td>
                    <td><input type="checkbox" name="design1-open-link-in-new-tab-demo" id="design1-open-link-in-new-tab-demo" value="1"/></td>
                </tr>
                <tr class="ept-demo">
                    <td class="settings-title"><label for="design1-no-spacing-betwen-columns-demo"><?php _e('No Spacing Between Columns', PTP_LOC); ?></label></td>
                    <td><input type="checkbox" name="design1-no-spacing-betwen-columns-demo" id="design1-no-spacing-betwen-columns-demo" value="1"/></td>
                </tr>
                <tr class="ept-demo">
                    <td class="settings-title"><label for="design1-hover-effects-demo"><?php _e('Enlarge Column on Hover', PTP_LOC); ?></label></td>
                    <td><input type="checkbox" name="design1-hover-effects-demo" id="design1-hover-effects-demo" value="1"/></td>
                </tr>
                <tr class="ept-demo">
                    <td class="settings-title"><label for="design1-shake-buttons-on-hover-demo"><?php _e('Shake Button on Hover', PTP_LOC); ?></label></td>
                    <td><input type="checkbox" name="design1-shake-buttons-on-hover-demo" id="design1-shake-buttons-on-hover-demo" value="1"/></td>
                </tr>
            </table>
        </div>
         */ ?> 
         
     <!-- ept-custom-css-setting -->
        <h3><?php _e('Custom CSS', PTP_LOC); ?></h3>
        <div >
 
            <table>
                <tr>
                    <?php $mb->the_field('ept-custom-css-setting-dg1'); ?>
                    <td class="settings-title">
                        <label for="custom-css-setting" style="margin: 0; font-weight: bold;"><?php _e('Custom Pricing Table CSS', PTP_LOC); ?></label>
                    </td>
                    <td class="custom-css-setting-td">
                        
                        <textarea  class="custom-css-setting-textbox" name="<?php $metabox->the_name(); ?>"  rows="10" cols="60" <?php if (!$metabox->get_the_value()) echo  'placeholder=" Type your custom css here"' ?> ><?php if ($metabox->get_the_value()) echo " ".$metabox->get_the_value();else {
                         echo " ";
                     } ?></textarea>
                        </td>
                </tr>
           
            </table>
        
           </div>
    </div>

    <script type="text/javascript">
       /* jQuery(document).ready(function($){            
            // Alert
            var alert_text = "<?php //_e("Please upgrade to Easy Pricing Tables Premium to use this setting.", PTP_LOC); ?>";
            $('.ept-demo').on('click', function(){
                alert(alert_text);
                return false;
            });
            $('.ept-demo .wp-picker-container a').click(function(event){
                alert(alert_text);
                return false;
            });
            
             $('.custom-css-setting-textbox').on('click', function(){
                alert(alert_text);
                return false;
            });
             $('.custom-css-setting-td').on('click', function(){
                alert(alert_text);
                return false;
            });
        }); */
    </script>
</div>
